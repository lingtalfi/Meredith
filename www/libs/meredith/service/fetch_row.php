<?php


require_once __DIR__ . "/../../../../init.php";


use Meredith\Exception\MeredithException;
use Meredith\Supervisor\MeredithSupervisor;
use QuickPdo\QuickPdo;
use Tim\TimServer\TimServer;
use Tim\TimServer\TimServerInterface;


TimServer::create()->start(function (TimServerInterface $server) {


    if (
        isset($_POST['formId']) &&
        isset($_POST['id'])
    ) {
        $id = (int)$_POST['id'];
        $view = (string)$_POST['formId'];


        if (true === MeredithSupervisor::inst()->isGranted($view, 'fetch')) {

            if (false !== $info = QuickPdo::fetch("select * from $view where id=$id")) {
                $server->success($info);
            }
            else {
                $server->error(MeredithSupervisor::inst()->translate("An error occurred with the database, please retry later"));
            }
        }
        else {
            throw new MeredithException("Permission not granted to access rows with $view");
        }

    }
    else {
        $server->error(MeredithSupervisor::inst()->translate("Invalid data"));
    }
})->output();