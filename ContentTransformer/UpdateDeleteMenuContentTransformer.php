<?php

namespace Meredith\ContentTransformer;

use Meredith\Tool\MeredithTool;

/**
 * LingTalfi 2015-12-29
 */
class UpdateDeleteMenuContentTransformer implements ContentTransformerInterface
{

    private $updateText;
    private $deleteText;

    public function __construct()
    {
        $this->updateText = "Update";
        $this->deleteText = "Delete";
    }

    public static function create()
    {
        return new static();
    }


    public function render($targetPos)
    {
        $update = MeredithTool::jsQuoteEscape($this->updateText);
        $delete = MeredithTool::jsQuoteEscape($this->deleteText);
        return <<<EEE
            meredithColumnDefsFactory.actionMenu({
                updateText: "$update",
                deleteText: "$delete"
            })
EEE;

    }

    public function setDeleteText($deleteText)
    {
        $this->deleteText = $deleteText;
        return $this;
    }

    public function setUpdateText($updateText)
    {
        $this->updateText = $updateText;
        return $this;
    }

}