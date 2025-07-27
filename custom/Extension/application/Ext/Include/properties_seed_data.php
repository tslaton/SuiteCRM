<?php
// Hook into the demo data population process
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// This file will be included during installation if demo data is selected
if (!empty($GLOBALS['installing_demo_data']) || !empty($GLOBALS['populate_demo_data'])) {
    if (file_exists('modules/Properties/PropertiesDemoData.php')) {
        require_once('modules/Properties/PropertiesDemoData.php');
        $properties_demo = new PropertiesDemoData();
        $properties_demo->create_demo_data();
    }
}