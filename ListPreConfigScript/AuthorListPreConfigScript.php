<?php

namespace Meredith\ListPreConfigScript;

/*
 * LingTalfi 2016-01-05 
 */
use Meredith\ListButtonCode\ListButtonCodeInterface;
use Meredith\MainController\MainControllerInterface;

class AuthorListPreConfigScript implements ListPreConfigScriptInterface
{


    private $headerButtons;
    private $dir;

    public function __construct()
    {
        $this->headerButtons = [];
    }

    public static function create()
    {
        return new static();
    }


    public function render(MainControllerInterface $mc)
    {
        $f = __DIR__ . "/AuthorListPreConfigScript/preconfig-script.php";
        $buttons = $this->headerButtons; // variable for template
        ob_start();
        require_once $f;
        return ob_get_clean();
    }

    //------------------------------------------------------------------------------/
    // 
    //------------------------------------------------------------------------------/
    public function addHeaderButton(ListButtonCodeInterface $b)
    {
        $this->headerButtons[] = $b;
        return $this;
    }


}
