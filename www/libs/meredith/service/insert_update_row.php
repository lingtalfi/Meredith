<?php


require_once __DIR__ . "/../../../../init.php";


use Bat\ArrayTool;
use Meredith\Exception\MeredithException;
use Meredith\Supervisor\MeredithSupervisor;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;
use Tim\TimServer\OpaqueTimServer;
use Tim\TimServer\TimServerInterface;


OpaqueTimServer::create()
    ->start(function (TimServerInterface $server) {

        if (isset($_POST['formId'])) {
            $formId = (string)$_POST['formId'];

            //------------------------------------------------------------------------------/
            // allow manual override
            //------------------------------------------------------------------------------/
            if (false !== $processor = MeredithSupervisor::inst()->getFormProcessor($formId)) {

                $processor->process($_POST);
                if (null !== $msg = $processor->getSuccessMsg()) {
                    $server->success([
                        'msg' => $msg,
                    ]);
                }
                else {
                    $server->error($processor->getErrorMsg());
                }
            }
            //------------------------------------------------------------------------------/
            // automated workflow
            //------------------------------------------------------------------------------/
            else {

                $table = $formId; // should also handle when you need to update multiple tables
                $arr = $_POST; // maybe add an obfuscating layer here (if you are paranoid...)


                $mc = MeredithSupervisor::inst()->getMainController($formId);
                $dp = $mc->getFormDataProcessor();
                $defaultValues = $dp->getDefaultValues();
                $table = $mc->getReferenceTable();


                $arr = array_replace($defaultValues, $arr);

                $idf = $dp->getIdentfyingFields();


                $nac = $dp->getNonAutoIncrementedFields();
                $nac2Values = array_intersect_key($arr, array_flip($nac));
                    

                $isSuccess = false;
                try {
                    $mode = 'insert';
                    if (false === ($missing = ArrayTool::getMissingKeys($arr, $idf))) {
                        $mode = 'update';

                        // update
                        if (true === MeredithSupervisor::inst()->isGranted($formId, 'update')) {

                            $idf2Values = array_intersect_key($arr, array_flip($idf));
                            $where = [];
                            foreach ($idf2Values as $k => $v) {
                                $where[] = [$k, '=', $v];
                            }

                            if (true === QuickPdo::update($table, $nac2Values, $where)) {
                                $msg = $dp->getSuccessMessage($formId, 'update');
                                if (false === $msg) {
                                    $msg = MeredithSupervisor::inst()->translate("The record has been successfully updated");
                                }
                                $server->success([
                                    'msg' => $msg,
                                ]);
                                $isSuccess = true;
                            }
                            else {
                                $server->error(MeredithSupervisor::inst()->translate("An error occurred with the database, please retry later"));
                            }
                        }
                        else {
                            throw new MeredithException("Permission not granted to update with $formId");
                        }
                    }
                    else {
                        // insert
                        if (true === MeredithSupervisor::inst()->isGranted($formId, 'insert')) {


                            $cancelMsg = null;
                            $dp->onInsertBefore($table, $nac2Values, $cancelMsg);
                            if (null === $cancelMsg) {


                                if (false !== $id = QuickPdo::insert($table, $nac2Values)) {
                                    $msg = $dp->getSuccessMessage($formId, 'insert');
                                    if (false === $msg) {
                                        $msg = MeredithSupervisor::inst()->translate("The record has been successfully recorded");
                                    }
                                    $server->success([
                                        'msg' => $msg,
                                    ]);
                                    $isSuccess = true;
                                }
                                else {
                                    $server->error(MeredithSupervisor::inst()->translate("An error occurred with the database, please retry later"));
                                }
                            }
                            else {
                                $server->error($cancelMsg);
                            }
                        }
                        else {
                            throw new MeredithException("Permission not granted to insert with $formId");
                        }
                    }
                    
                    
                    
                    
                    
                } catch (\PDOException $e) {
                    if ('23000' === $e->getCode()) { // integrity constraint violation

                        MeredithSupervisor::inst()->log($e);

                        $msg = false;
                        if ('mysql' === QuickPdoInfoTool::getDriver()) {
                            if (1062 === $e->errorInfo[1]) { // Duplicate entry '%s' for key %d 
                                $msg = $dp->getDuplicateEntryMessage($formId, $mode);
                                if (false === $msg) {
                                    $msg = "A similar item already exists in the database";
                                }
                            }
                        }
                        if (false === $msg) {
                            $msg = $dp->getDefaultErrorMessage($formId, $mode);
                            if (false === $msg) {
                                $msg = 'A problem occurred with the database';
                            }
                        }
                        $server->error($msg);
                    }
                    else {
                        throw $e;
                    }
                }
            }
        }
        else {
            $server->error(MeredithSupervisor::inst()->translate("Invalid data: undefined formId"));
        }
    })->output();