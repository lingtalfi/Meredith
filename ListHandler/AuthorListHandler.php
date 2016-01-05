<?php

namespace Meredith\ListHandler;

use Meredith\ListButtonCode\ColvisListButtonCode;
use Meredith\ListButtonCode\DeleteSelectedRowsListButtonCode;
use Meredith\ListPreConfigScript\AuthorListPreConfigScript;

/**
 * LingTalfi 2015-12-28
 */
class AuthorListHandler extends BaseListHandler
{
    public function __construct()
    {
        parent::__construct();
        $this->setPreConfigScript(AuthorListPreConfigScript::create()
                ->addHeaderButton(ColvisListButtonCode::create())
                ->addHeaderButton(DeleteSelectedRowsListButtonCode::create())
        );
    }


}