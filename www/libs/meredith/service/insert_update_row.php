<?php


require_once __DIR__ . "/../../../../init.php";


use Bat\ArrayTool;
use Meredith\Exception\MeredithException;
use Meredith\Supervisor\MeredithSupervisor;
use QuickPdo\QuickPdo;
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
                $defaultValues = $mc->getFormDataProcessor()->getDefaultValues();
                $arr = array_replace($defaultValues, $arr);

                $idf = $mc->getFormDataProcessor()->getIdentfyingFields();


                $nac = $mc->getFormDataProcessor()->getNonAutoIncrementedFields();
                $nac2Values = array_intersect_key($arr, array_flip($nac));


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
                                $msg = $mc->getFormDataProcessor()->getSuccessMessage($formId, 'update');
                                if (false === $msg) {
                                    $msg = MeredithSupervisor::inst()->translate("The record has been successfully updated");
                                }
                                $server->success([
                                    'msg' => $msg,
                                ]);
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
                            if (false !== $id = QuickPdo::insert($table, $nac2Values)) {
                                $msg = $mc->getFormDataProcessor()->getSuccessMessage($formId, 'insert');
                                if (false === $msg) {
                                    $msg = MeredithSupervisor::inst()->translate("The record has been successfully recorded");
                                }
                                $server->success([
                                    'msg' => $msg,
                                ]);
                            }
                            else {
                                $server->error(MeredithSupervisor::inst()->translate("An error occurred with the database, please retry later"));
                            }
                        }
                        else {
                            throw new MeredithException("Permission not granted to insert with $formId");
                        }
                    }
                } catch (\PDOException $e) {
                    if ('23000' === $e->getCode()) { // integrity constraint violation 
                        $msg = $mc->getFormDataProcessor()->getDuplicateEntryMessage($formId, $mode);
                        if (false === $msg) {
                            $msg = "A similar item already exists in the database";
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