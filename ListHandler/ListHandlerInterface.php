<?php

namespace Meredith\ListHandler;

use Meredith\ContentTransformer\ContentTransformerInterface;
use Meredith\ListButtonCode\ListButtonCodeInterface;
use Meredith\ListPreConfigScript\ListPreConfigScriptInterface;
use Meredith\MainController\MainControllerInterface;
use Meredith\OnModalOpenAfter\OnModalOpenAfterInterface;
use Meredith\TableStyleRenderer\TableStyleRendererInterface;

/**
 * LingTalfi 2015-12-28
 */
interface ListHandlerInterface
{

    public function getColumnLabels();

    public function getColumnNames2Types();

    public function getColumns();


    public function getOrderableColumns();

    public function getSearchableColumns();


    /**
     * Return an array of
     *      target position => ContentTransformerInterface
     *
     * The target position is any target of type integer, as define
     * in datatable docs, which means it is a number representing
     * the targeted column position.
     * This number can be either positive: 0 being the first column, 1 being the second, ...,
     * or negative, -1 being the last column, -2 the one before the last, ...
     *
     *
     * @return ContentTransformerInterface[]
     */
    public function getContentTransformers();

    /**
     * @return OnModalOpenAfterInterface
     */
    public function getOnModalOpenAfter();

    /**
     * @return ListPreConfigScriptInterface
     */
    public function getPreConfigScript();


    //------------------------------------------------------------------------------/
    // ADD COSMETIC RELATED METHODS
    //------------------------------------------------------------------------------/
    /**
     * @return string|null
     */
    public function getMainAlias();

    /**
     * Return the cosmetic fields aware from clause.
     *
     * @param MainControllerInterface $mc
     * @return string
     */
    public function getFrom(MainControllerInterface $mc);

    /**
     * @return array of fields to use in the sql request:
     *              those fields are aware of aliases.
     *              Unchanged fields are returned with the alias prefix,
     *              and cosmetic fields are returned as is
     */
    public function getRequestFields();


    //------------------------------------------------------------------------------/
    // ADD REQUEST CUSTOMIZATION METHODS 
    //------------------------------------------------------------------------------/
    /**
     * @return string|null
     */
    public function getWhere();
    
}