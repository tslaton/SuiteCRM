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
}