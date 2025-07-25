<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('modules/Opportunities/views/view.edit.php');

class CustomOpportunitiesViewEdit extends OpportunitiesViewEdit
{
    public function preDisplay()
    {
        parent::preDisplay();
        
        // Check if we're creating from a property
        if (empty($this->bean->id) && !empty($_REQUEST['property_id'])) {
            // Pre-fill property fields
            $this->bean->property_id = $_REQUEST['property_id'];
            
            if (!empty($_REQUEST['property_name'])) {
                $this->bean->property_name = $_REQUEST['property_name'];
            } else {
                // Load property to get the name
                $property = BeanFactory::getBean('Properties', $_REQUEST['property_id']);
                if ($property && !empty($property->id)) {
                    $this->bean->property_name = $property->name;
                    
                    // Pre-fill transaction name with property address
                    if (empty($this->bean->name)) {
                        $this->bean->name = 'Transaction - ' . $property->street_address;
                    }
                    
                    // Pre-fill amount with property price
                    if (empty($this->bean->amount) && !empty($property->price)) {
                        $this->bean->amount = $property->price;
                    }
                }
            }
        }
    }
}