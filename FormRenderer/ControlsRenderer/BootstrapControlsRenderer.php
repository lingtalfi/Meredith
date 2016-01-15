<?php

namespace Meredith\FormRenderer\ControlsRenderer;

use Bat\CaseTool;
use DirScanner\YorgDirScannerTool;
use Meredith\FormRenderer\ControlsRenderer\Control\ColisControlInterface;
use Meredith\FormRenderer\ControlsRenderer\Control\ControlInterface;
use Meredith\FormRenderer\ControlsRenderer\Control\InputControlInterface;
use Meredith\FormRenderer\ControlsRenderer\Control\MonoStatusControlInterface;
use Meredith\FormRenderer\ControlsRenderer\Control\SingleSelectControlInterface;
use Meredith\FormRenderer\ControlsRenderer\Control\UrlWithDropZoneControlInterface;

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
        elseif ($c instanceof SingleSelectControlInterface) {
            return $this->renderSingleSelectStatusControl($c);
        }
        elseif ($c instanceof ColisControlInterface) {
            return $this->renderColisControl($c);
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
<!-- switchery single -->
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

    
    
    private function renderSingleSelectStatusControl(SingleSelectControlInterface $c)
    {
        $label = $c->getLabel();
        $value = $c->getValue();
        $name = htmlspecialchars($c->getName());
        $v2l = $c->getValues2Labels();


        $s = '';
        $s .= <<<EEE
<!-- single select -->
<div class="form-group">
    <label class="control-label col-lg-2">$label</label>
    <div class="col-lg-10">
        <select class="form-control" name="$name">
EEE;

        foreach ($v2l as $v => $l) {
            $sSel = ($v === $value) ? ' selected="selected"' : '';
            $val = htmlspecialchars($v);
            $s .= "<option" . $sSel . " value=\"$val\">$l</option>";
        }
        $s .= <<<EEE
        </select>
    </div>
</div>
<!-- /single select -->
EEE;
        return $s;

    }







    private function renderColisControl(ColisControlInterface $c)
    {
        $label = $c->getLabel();
        $required = "";
        if (true === $c->getIsRequired()) {
            $label .= ' <span class="text-danger">*</span>';
            $required = ' required="required"';
        }
        $name = htmlspecialchars($c->getName());
        $id = htmlspecialchars($name);
        $placeholder = htmlspecialchars($c->getPlaceholder());
        $value = htmlspecialchars($c->getValue());
        $help = (null !== $h = $c->getHelp()) ? '<span class="help-block">' . $h . '</span>' : '';
        
        
        $extensions = $c->getExtensions();
        $profileId = $c->getProfileId();
        $itemNames = $c->getItemNames();
        $maxSize = $c->getMaxSize();
        $chunkSize = $c->getChunkSize();
        $onPreviewDisplayAfter = $c->getOnPreviewDisplayAfterJsCallback();
        
        
        
        $colisDataId = 'colis-' . CaseTool::toSnake($id);
        
        $itemNames = json_encode($itemNames);
        $previewOptions = json_encode($c->getPreviewOptions());


        
        return <<<EEE
<!-- input field -->
<div class="form-group">
    <label class="control-label col-lg-3">$label</label>

    <div class="col-lg-9">
    
        <input data-colis-id="$colisDataId" type="text" name="$name" class="form-control colis_selector typeahead-basic" id="$id"
               $required placeholder="$placeholder"
               value="$value">
        $help
    </div>
</div>
<script>
   (function ($) {
        $(document).ready(function () {

            var itemList = $itemNames;
            
            var previewOptions = $previewOptions;
            
            
            
            window.colisClasses.preview.prototype.buildTemplate = function (jWrapper) {
                jWrapper.append('<div class="colis_preview alert alert-primary"><ul class="colis_polaroids"></ul></div>');
                this.jPreview = jWrapper.find('.colis_preview');
            };
            

            $('.colis_selector[data-colis-id="$colisDataId"]').colis({
                urlInfo: "/libs/colis/service/ling/colis_info_mixed.php",
                requestPayload: {
                    id: "$profileId"
                },
                selector: {
                    items: itemList,
                    options: {
                        classNames: {
                            menu: 'tt-dropdown-menu'
                        }
                    }
                },
                uploader: {
                    url: "/libs/colis/service/ling/colis_upload_mixed.php",
                    multipart_params: {
                        id: "$profileId"
                    },
                    filters: {
                        // Specify what files to browse for
                        mime_types: [
                            {title: "my files", extensions: "$extensions"}
                        ],
                        // Maximum file size
                        max_file_size: '$maxSize'
                    }, 
                    chunk_size: "$chunkSize"                                       
                },
                preview: previewOptions,
                onPreviewDisplayAfter: $onPreviewDisplayAfter
            });

        });
    })(jQuery);
</script>        
<!-- /input field -->
EEE;

    }
    
    

}