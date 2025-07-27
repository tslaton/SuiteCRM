<?php
/**
 * Custom Properties Detail View
 * Adds CMA Generation button
 */

require_once('modules/Properties/views/view.detail.php');

class CustomPropertiesViewDetail extends PropertiesViewDetail
{
    public function display()
    {
        // Add CMA button to the detail view buttons
        $this->addCMAButton();
        
        parent::display();
        
        // Hardcode the first subpanel title
        $this->hardcodeContactsSubpanelTitle();
    }
    
    /**
     * Add Generate CMA button to the detail view
     */
    private function addCMAButton()
    {
        global $mod_strings;
        
        // Create the CMA button
        $cmaButton = array(
            'customCode' => '<input type="button" class="button" ' .
                'onClick="window.location=\'index.php?module=Properties&action=generatecma&record={$fields.id.value}\';" ' .
                'value="Generate CMA" title="Generate Comparative Market Analysis">',
        );
        
        // Add button to the buttons array
        if (!isset($this->dv->defs['templateMeta']['form']['buttons'])) {
            $this->dv->defs['templateMeta']['form']['buttons'] = array();
        }
        
        // Insert after Edit button (position 1)
        array_splice($this->dv->defs['templateMeta']['form']['buttons'], 2, 0, array($cmaButton));
    }
    
    /**
     * Hardcode the contacts subpanel title
     */
    private function hardcodeContactsSubpanelTitle()
    {
        echo '<script type="text/javascript">
            $(document).ready(function() {
                // Find the first subpanel title and replace it
                var firstSubpanel = $("#subpanel_list li:first-child .panel-heading a .col-xs-10 div");
                if (firstSubpanel.length > 0) {
                    // Keep the icon but replace the text
                    var icon = firstSubpanel.find(".suitepicon").detach();
                    firstSubpanel.html("");
                    firstSubpanel.append(icon);
                    firstSubpanel.append(" RELATED CONTACTS");
                }
                
                // Also handle when subpanel is toggled
                $("#subpanel_list li:first-child .panel-heading a").on("click", function() {
                    setTimeout(function() {
                        var firstSubpanel = $("#subpanel_list li:first-child .panel-heading a .col-xs-10 div");
                        if (firstSubpanel.length > 0 && !firstSubpanel.text().includes("RELATED CONTACTS")) {
                            var icon = firstSubpanel.find(".suitepicon").detach();
                            firstSubpanel.html("");
                            firstSubpanel.append(icon);
                            firstSubpanel.append(" RELATED CONTACTS");
                        }
                    }, 100);
                });
            });
        </script>';
    }
}