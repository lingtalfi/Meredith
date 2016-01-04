<?php

require_once __DIR__ . "/../../../../init.php";


use Meredith\Exception\MeredithException;
use Meredith\Supervisor\MeredithSupervisor;
use QuickPdo\QuickPdo;


if (isset($_GET['table'])) {

    $formId = $_GET['table'];

    if (true === MeredithSupervisor::inst()->isGranted($formId, 'fetch')) {


        $ret = [];
        $arr = $_POST;
        if (
            isset($arr['draw']) &&
            isset($arr['start']) &&
            isset($arr['length']) &&
            isset($arr['search'])
        ) {

            try {

                $stmt = "";


                $ret['draw'] = (int)$arr["draw"];
                $start = (int)$arr["start"];
                $length = (int)$arr["length"];
                $search = $arr["search"];


                if (
                    is_array($search) &&
                    array_key_exists("value", $search) &&
                    array_key_exists("regex", $search)
                ) {


                    $searchValue = $search['value']; // I will not be using regex in this script


                    // getMainController either works as expected, or throws an Exception in your face ...
                    $mc = MeredithSupervisor::inst()->getMainController($formId);


                    // ... therefore the following statement is considered safe
                    $table = $formId;

                    $columns = $mc->getListHandler()->getColumns();
                    $orderableCols = $mc->getListHandler()->getOrderableColumns();
                    $searchableCols = $mc->getListHandler()->getSearchableColumns();
                    $columns = $mc->getListHandler()->getColumns();
                    $orderable = [];
                    foreach ($columns as $col) {
                        if (in_array($col, $orderableCols, true)) {
                            $orderable[] = true;
                        }
                        else {
                            $orderable[] = false;
                        }
                    }
                    $searchable = [];
                    foreach ($columns as $col) {
                        if (in_array($col, $searchableCols, true)) {
                            $searchable[] = true;
                        }
                        else {
                            $searchable[] = false;
                        }
                    }
                    $nbColumns = count($columns);


                    // I'm not using those (I prefer to manage those settings server side)
//            for ($i = 0; $i < $nbColumns; $i++) {
//                $arr['columns'][$i]['data'];
//                $arr['columns'][$i]['name'];
//                $arr['columns'][$i]['searchable'];
//                $arr['columns'][$i]['orderable'];
//                $arr['columns'][$i]['search']['value'];
//                $arr['columns'][$i]['search']['regex'];
//            }

                    //------------------------------------------------------------------------------/
                    // SEARCH - MODULE
                    //------------------------------------------------------------------------------/
                    $sSearch = "";
                    $markers = [];
                    if ('' !== $searchValue) {
                        $c = 0;
                        foreach ($searchable as $index => $isSearchable) {
                            if (true === $isSearchable) {
                                if ('' === $sSearch) {
                                    $sSearch = " where";
                                }
                                if (0 !== $c) {
                                    $sSearch .= " or";
                                }
                                $colName = $columns[$index];
                                $sSearch .= " " . $colName . " like :$colName";
                                $markers[$colName] = '%' . str_replace('%', '\%', $searchValue) . '%';
                                $c++;
                            }
                        }
                    }


                    //------------------------------------------------------------------------------/
                    // ORDER BY - MODULE
                    //------------------------------------------------------------------------------/
                    $sOrder = "";
                    if (isset($arr['order']) && is_array($arr['order'])) {
                        if ($arr['order']) {
                            $c = 0;
                            $sOrder .= " order by";
                            foreach ($arr['order'] as $colNum => $info) {
                                if (
                                    is_array($info) &&
                                    array_key_exists('column', $info) &&
                                    array_key_exists('dir', $info)
                                ) {
                                    $isOrderable = (array_key_exists($colNum, $orderable) && true === $orderable[$colNum]);
                                    if (true === $isOrderable) {
                                        if (
                                            $colNum <= $nbColumns && $colNum >= 0 &&
                                            ('asc' === $info['dir'] || 'desc' === $info['dir'])
                                        ) {
                                            if ($c !== 0) {
                                                $sOrder .= ",";
                                            }
                                            $colName = $columns[$colNum];
                                            $sOrder .= " " . $colName . " " . $info['dir'];
                                            $c++;
                                        }
                                    }
                                }
                            }
                        }
                    }


                    //------------------------------------------------------------------------------/
                    // REQUEST COMPUTING
                    //------------------------------------------------------------------------------/
                    if (false !== $info = QuickPdo::fetch("select count(*) as count from $table")) {
                        $ret['recordsTotal'] = (int)$info['count'];
                        $ret['recordsFiltered'] = $ret['recordsTotal'];

                        $stmt .= "select * from $table";
                        $stmt .= $sSearch;


                        $stmt .= $sOrder;
                        $stmt .= " limit $start, $length";

                        if (false !== $res = QuickPdo::fetchAll($stmt, $markers)) {
                            $ret['data'] = [];
                            foreach ($res as $k => $row) {
                                $row = ['DT_RowId' => $row['id']] + $row;
                                $ret['data'][$k] = $row;
                            }
                        }

                        // recordFiltered
                        $stmtCountFiltered = "select count(*) as count from $table";
                        $stmtCountFiltered .= $sSearch;
                        if (false !== $info = QuickPdo::fetch($stmtCountFiltered, $markers)) {
                            $ret['recordsFiltered'] = (int)$info['count'];
                        }
                    }
                }


            } catch (\Exception $e) {
                $ret['error'] = MeredithSupervisor::inst()->translate("Oops! An error occurred, please retry later");
                MeredithSupervisor::inst()->log("Oops! An error occurred, please retry later: $stmt -- " . $e->getMessage());
            }
        }

    }
    else {
        throw new MeredithException("Permission not granted to access rows with $formId");
    }
}
else {
    throw new \Exception("An error occurred"); // but we don't care
}

echo json_encode($ret);