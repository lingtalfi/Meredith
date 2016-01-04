<?php

namespace Meredith\FormDataProcessor;

/*
 * LingTalfi 2016-01-02
 */
interface FormDataProcessorInterface
{


    /**
     * Return the default values for the attached fields, excluding auto-incremented fields.
     * Originally created for unchecked checkboxes.
     *
     * @return array of name => defaultValue
     */
    public function getDefaultValues();


    /**
     * @return array of identifying fields.
     *
     *      Identifying fields are the unique fields needed to UPDATE a row in the database.
     *      Typically, there is only one identifying field named id, but for tables without id,
     *      we would have an array of fields.
     *
     */
    public function getIdentfyingFields();

    public function getNonAutoIncrementedFields();


    /**
     * @param $formId
     * @param string $type (insert|update)
     * @return string|false
     */
    public function getSuccessMessage($formId, $type);

    /**
     * @param $formId
     * @param string $type (insert|update)
     * @return string|false
     */
    public function getDuplicateEntryMessage($formId, $type);
}
