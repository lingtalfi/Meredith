<?php

namespace Meredith\FormRenderer\ControlsRenderer;

use Meredith\FormRenderer\ControlsRenderer\Control\Bootstrap\InputControl;
use Meredith\FormRenderer\ControlsRenderer\Control\ControlInterface;
use Meredith\FormRenderer\ControlsRenderer\Control\InputControlInterface;
use Meredith\FormRenderer\ControlsRenderer\Control\MonoStatusControlInterface;

/**
 * LingTalfi 2015-12-31
 */
class BootstrapControlsRenderer extends ControlsRenderer
{


    protected function renderControl(ControlInterface $c)
    {
        if ($c instanceof InputControlInterface) {
            return $this->renderInputControl($c);
        }
        elseif ($c instanceof MonoStatusControlInterface) {
            return $this->renderMonoStatusControl($c);
        }
        else {
            $this->log(sprintf("Doesn't know how to render control of class %s", get_class($c)));
        }
        return '';
    }


    private function renderInputControl(InputControlInterface $c)
    {
        $label = $c->getLabel();
        $required = "";
        if (true === $c->getIsRequired()) {
            $label .= ' <span class="text-danger">*</span>';
            $required = ' required="required"';
        }
        $type = $c->getType();
        $name = htmlspecialchars($c->getName());
        $id = htmlspecialchars($name);
        $placeholder = htmlspecialchars($c->getPlaceholder());
        $value = htmlspecialchars($c->getValue());
        $help = (null !== $h = $c->getHelp()) ? '<span class="help-block">' . $h . '</span>' : '';

        return <<<EEE
<!-- input field -->
<div class="form-group">
    <label class="control-label col-lg-3">$label</label>

    <div class="col-lg-9">
        <input type="$type" name="$name" class="form-control" id="$id"
               $required placeholder="$placeholder"
               value="$value">
        $help
    </div>
</div>
<!-- /input field -->
EEE;

    }


    private function renderMonoStatusControl(MonoStatusControlInterface $c)
    {
        $label = $c->getLabel();
        $value = htmlspecialchars($c->getValue());
        $name = htmlspecialchars($c->getName());
        $checked = (true === (bool)$value) ? 'checked="checked"' : '';

        return <<<EEE
<!-- Switchery single -->
<div class="form-group">
    <div class="col-lg-9">
        <div class="checkbox checkbox-switchery switchery-xs">
            <label>
                <input type="checkbox" name="$name" value="$value" class="switchery" $checked>
                $label
            </label>
        </div>
    </div>
</div>
<!-- /switchery single -->
EEE;

    }
}