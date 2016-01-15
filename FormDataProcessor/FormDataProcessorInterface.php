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
     * @param $type (insert|update)
     * @return string|false
     */
    public function getDefaultErrorMessage($formId, $type);

    /**
     * @param $formId
     * @param string $type (insert|update)
     * @return string|false
     */
    public function getDuplicateEntryMessage($formId, $type);


    /**
     * @param $extensionId
     * @return false|mixed
     */
    public function getExtension($extensionId);

    /**
     * Returns the (main) table that should be inserted/updated.
     * Null means auto.
     *
     * @return null|string
     */
    public function getTable();

    /**
     * This method is executed just before an insert.
     * It has the power to skip the insert, if cancelMsg is set to a non null value.
     *
     * This method is the opportunity for you to check special unique constraints
     * for instance.
     *
     *
     * @param string $table,
     * @param array $values
     * @param $cancelMsg
     * @return void
     */
    public function onInsertBefore($table, array $values, &$cancelMsg);
}
