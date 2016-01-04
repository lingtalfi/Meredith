<?php

namespace Meredith\MainController;

use Meredith\FormDataProcessor\FormDataProcessorInterface;
use Meredith\FormHandler\FormHandlerInterface;
use Meredith\ListHandler\ListHandlerInterface;
use Meredith\OnFormReady\OnFormReadyInterface;

/**
 * LingTalfi 2015-12-28
 */
interface MainControllerInterface
{


    /**
     * @return FormDataProcessorInterface
     */
    public function getFormDataProcessor();
    /**
     * @return FormHandlerInterface
     */
    public function getFormHandler();

    public function getFormId();

    /**
     * @return ListHandlerInterface
     */
    public function getListHandler();
    /**
     * @return OnFormReadyInterface
     */
    public function getOnFormReady();


}