<?php

namespace Meredith\ListHandler;

use Bat\CaseTool;
use Meredith\ContentTransformer\ContentTransformerInterface;
use Meredith\Exception\MeredithException;
use Meredith\ListButtonCode\ListButtonCodeInterface;
use Meredith\OnModalOpenAfter\OnModalOpenAfterInterface;
use Meredith\TableStyleRenderer\TableStyleRendererInterface;

/**
 * LingTalfi 2015-12-28
 */
class BaseListHandler implements ListHandlerInterface
{
    private $contentTransformers;
    private $headerButtons;
    private $onModalOpenAfter;

    /**
     * @var array of column
     *          column:
     *              0: str:name
     *              1: bool:hasContent (true)
     *              2: str:label (null=auto)
     *
     * the hasContent property means that the column is generated from the database,
     * as opposed to manually rendered (like an action column for instance)
     */
    private $columns;
    private $notOrderable;
    private $notSortable;

    public function __construct()
    {
        $this->headerButtons = [];
        $this->contentTransformers = [];
        $this->columns = [];
        $this->notOrderable = [];
        $this->notSearchable = [];
    }

    public static function create()
    {
        return new static();
    }


    public function addColumn($name, $hasContent = true, $label = null)
    {
        if (null === $label) {
            $label = $this->getAutoLabel($name);
        }
        $this->columns[] = [
            $name,
            $hasContent,
            $label,
        ];
        return $this;
    }

    public function getColumnLabels()
    {
        $ret = [];
        foreach ($this->columns as $info) {
            $ret[] = $info[2];
        }
        return $ret;
    }

    public function getColumnNames2Types()
    {
        $ret = [];
        foreach ($this->columns as $info) {
            $ret[$info[0]] = $info[1];
        }
        return $ret;
    }

    public function getColumns()
    {
        $ret = [];
        foreach ($this->columns as $info) {
            $ret[] = $info[0];
        }
        return $ret;
    }

    public function getOrderableColumns()
    {
        $ret = [];
        foreach ($this->columns as $info) {
            if (true === $info[1]) { // only columns with content will possibly be searchable or orderable
                if (!in_array($info[0], $this->notOrderable, true)) {
                    $ret[] = $info[0];
                }
            }
        }
        return $ret;
    }

    public function getSearchableColumns()
    {
        $ret = [];
        foreach ($this->columns as $info) {
            if (true === $info[1]) { // only columns with content will possibly be searchable or orderable
                if (!in_array($info[0], $this->notSearchable, true)) {
                    $ret[] = $info[0];
                }
            }
        }
        return $ret;
    }


    public function getContentTransformers()
    {
        return $this->contentTransformers;
    }

    
    /**
     * @return ListButtonCodeInterface[]
     */
    public function getHeaderButtons()
    {
        return $this->headerButtons;
    }

    /**
     * @return OnModalOpenAfterInterface
     */
    public function getOnModalOpenAfter()
    {
        return $this->onModalOpenAfter;
    }


    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    public function addHeaderButtons(ListButtonCodeInterface $headerButton)
    {
        $this->headerButtons[] = $headerButton;
        return $this;
    }

    /*
     * target: int|string, position or name of the column
     */
    public function setContentTransformer($target, ContentTransformerInterface $contentTransformer)
    {
        if (is_string($target)) {
            $target = $this->columnToTarget($target);
        }
        $this->contentTransformers[$target] = $contentTransformer;
        return $this;
    }


    public function setOnModalOpenAfter(OnModalOpenAfterInterface $onModalOpenAfter)
    {
        $this->onModalOpenAfter = $onModalOpenAfter;
        return $this;
    }

    public function setNotOrderable(array $notOrderable)
    {
        $this->notOrderable = $notOrderable;
        return $this;
    }

    public function setNotSortable(array $notSortable)
    {
        $this->notSearchable = $notSortable;
        return $this;
    }



    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    protected function getAutoLabel($s)
    {
        return ucfirst(strtolower(CaseTool::unsnake($s)));
    }

    //------------------------------------------------------------------------------/
    //
    //------------------------------------------------------------------------------/
    private function columnToTarget($str)
    {
        $c = 0;
        foreach ($this->columns as $v) {
            if ($str === $v[0]) {
                return $c;
            }
            $c++;
        }
        throw new MeredithException("Target $str cannot resolve to an existing column index");
    }

}