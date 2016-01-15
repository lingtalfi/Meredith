<?php

namespace Meredith\FormDataProcessor;

/*
 * LingTalfi 2016-01-02
 */
class FormDataProcessor implements FormDataProcessorInterface
{


    /**
     * @var array
     *          - 0: name
     *          - 1: is auto incremented
     *          - 2: is part of idf
     */
    private $fields;
    private $getSuccessMsgCb;
    private $getDefaultErrorMsgCb;
    private $getDuplicateEntryMsgCb;
    private $getOnInsertBeforeCb;
    private $extensions;
    private $table;

    public function __construct()
    {
        $this->fields = [];
        $this->extensions = [];
    }


    public static function create()
    {
        return new static();
    }


    public function addField($name, $isAutoIncremented = false, $isIdf = false, $defaultValue = null)
    {
        $this->fields[] = [$name, $isAutoIncremented, $isIdf, $defaultValue];
        return $this;
    }

    public function setExtension($extensionId, $extension)
    {
        $this->extensions[$extensionId] = $extension;
        return $this;
    }

    //------------------------------------------------------------------------------/
    // 
    //------------------------------------------------------------------------------/
    public function getDefaultValues()
    {
        $ret = [];
        foreach ($this->fields as $info) {
            if (false === $info[1]) {
                $ret[$info[0]] = $info[3];
            }
        }
        return $ret;
    }


    /**
     * @return array of identifying fields.
     *
     *      Identifying fields are the unique fields needed to UPDATE a row in the database.
     *      Typically, there is only one identifying field named id, but for tables without id,
     *      we would have an array of fields.
     *
     */
    public function getIdentfyingFields()
    {
        $ret = [];
        foreach ($this->fields as $info) {
            if (true === $info[2]) {
                $ret[] = $info[0];
            }
        }
        return $ret;
    }

    public function getNonAutoIncrementedFields()
    {
        $ret = [];
        foreach ($this->fields as $info) {
            if (false === $info[1]) {
                $ret[] = $info[0];
            }
        }
        return $ret;
    }

    /**
     * @param $formId
     * @param string $type (insert|update)
     * @return string|false
     */
    public function getSuccessMessage($formId, $type)
    {
        if (null !== $this->getSuccessMsgCb) {
            return call_user_func($this->getSuccessMsgCb, $formId, $type);
        }
        return false;
    }

    /**
     * @param $formId
     * @param string $type (insert|update)
     * @return string|false
     */
    public function getDefaultErrorMessage($formId, $type)
    {
        if (null !== $this->getSuccessMsgCb) {
            return call_user_func($this->getSuccessMsgCb, $formId, $type);
        }
        return false;
    }

    /**
     * @param $formId
     * @param string $type (insert|update)
     * @return string|false
     */
    public function getDuplicateEntryMessage($formId, $type)
    {
        if (null !== $this->getDuplicateEntryMsgCb) {
            return call_user_func($this->getDuplicateEntryMsgCb, $formId, $type);
        }
        return false;
    }

    /**
     * @param $extensionId
     * @return mixed|false
     */
    public function getExtension($extensionId)
    {
        if (array_key_exists($extensionId, $this->extensions)) {
            return $this->extensions[$extensionId];
        }
        return false;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function onInsertBefore($table, array $values, &$cancelMsg)
    {
        if (null !== $this->getOnInsertBeforeCb) {
            return call_user_func_array($this->getOnInsertBeforeCb, [$table, $values, &$cancelMsg]);
        }
    }


    //------------------------------------------------------------------------------/
    // 
    //------------------------------------------------------------------------------/
    public function setGetDuplicateEntryMsgCb(callable $getDuplicateEntryMsgCb)
    {
        $this->getDuplicateEntryMsgCb = $getDuplicateEntryMsgCb;
        return $this;
    }

    public function setGetSuccessMsgCb(callable $getSuccessMsgCb)
    {
        $this->getSuccessMsgCb = $getSuccessMsgCb;
        return $this;
    }

    public function setGetDefaultErrorMsgCb(callable $cb)
    {
        $this->getDefaultErrorMsgCb = $cb;
        return $this;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function setGetOnInsertBeforeCb(callable $getOnInsertBeforeCb)
    {
        $this->getOnInsertBeforeCb = $getOnInsertBeforeCb;
        return $this;
    }


}
