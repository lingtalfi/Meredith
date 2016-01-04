<?php

namespace Meredith\ListHandler;

use Meredith\ListButtonCode\ColvisListButtonCode;

/**
 * LingTalfi 2015-12-28
 */
class AuthorListHandler extends BaseListHandler
{
    public function __construct()
    {
        parent::__construct();
        $this->addHeaderButtons(ColvisListButtonCode::create());
    }
}