<?php

namespace Meredith\MainController;

use Meredith\Column2LabelAdaptor\Column2LabelAdaptorInterface;
use Meredith\Exception\MeredithException;
use Meredith\ActionColumn\ActionColumnInterface;
use Meredith\FormDataProcessor\FormDataProcessorInterface;
use Meredith\FormHandler\FormHandlerInterface;
use Meredith\ListHandler\ListHandlerInterface;
use Meredith\OnFormReady\OnFormReadyInterface;
use Meredith\TableColumns\TableColumnsFactoryInterface;

/**
 * LingTalfi 2015-12-28
 */
class MeredithMainController implements MainControllerInterface
{
    private $formId;
    private $formDataProcessor;
    private $formHandler;
    private $listHandler;
    private $tableColumnsFactory;
    private $onFormReady;
 

    public function __construct()
    {
        //
    }


    public static function create()
    {
        return new static();
    }

    public function init($formId)
    {
        $this->formId = $formId;
        return $this;
    }

    /**
     * @return FormDataProcessorInterface
     */
    public function getFormDataProcessor()
    {
        return $this->formDataProcessor;
    }
    

    public function getFormId()
    {
        return $this->formId;
    }

    
    /**
     * @return FormHandlerInterface
     */
    public function getFormHandler()
    {
        return $this->formHandler;
    }


    /**
     * @return ListHandlerInterface
     */
    public function getListHandler()
    {
        return $this->listHandler;
    }

    /**
     * @return OnFormReadyInterface
     */
    public function getOnFormReady()
    {
        return $this->onFormReady;
    }

    public function getTableColumnsFactory()
    {
        return $this->tableColumnsFactory;
    }

 


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    public function setFormHandler(FormHandlerInterface $formHandler)
    {
        $this->formHandler = $formHandler;
        return $this;
    }

    public function setFormId($formId)
    {
        $this->formId = $formId;
        return $this;
    }

    public function setListHandler(ListHandlerInterface $listHandler)
    {
        $this->listHandler = $listHandler;
        return $this;
    }

    public function setFormDataProcessor(FormDataProcessorInterface $p)
    {
        $this->formDataProcessor = $p;
        return $this;
    }


    public function setOnFormReady(OnFormReadyInterface $onFormReady)
    {
        $this->onFormReady = $onFormReady;
        return $this;
    }
}