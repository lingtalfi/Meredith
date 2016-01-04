<?php

namespace Meredith\FormRenderer\ControlsRenderer\Control;

/**
 * LingTalfi 2015-12-31
 *
 * Knows the information necessary to GENERATE the html control.
 * It is a describing class.
 *
 *
 * Not all controls behave the same, and some controls should have more specific
 * configuration values (for instance, a select needs items).
 * The philosophy here is to create various interfaces, and let the renderer objects
 * test the instance being rendered.
 *
 */
interface ControlInterface
{

    public function getName();

    public function getLabel();

    public function getValue();

    public function getPlaceholder();

    public function getHelp();

    /**
     * Used only to display/not display the asterisk
     */
    public function getIsRequired();

}