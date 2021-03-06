<?php

namespace Ling\Meredith\ListPreConfigScript;

/*
 * LingTalfi 2016-01-05
 * Render code to run before calling (wass0) /libs/meredith/js/meredith-config.js asset 
 */
use Ling\Meredith\MainController\MainControllerInterface;

interface ListPreConfigScriptInterface
{

    public function render(MainControllerInterface $mc);
}
