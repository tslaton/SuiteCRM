<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

// Main module menu items
if (ACLController::checkAccess('Properties', 'edit', true)) {
    $module_menu[] = array("index.php?module=Properties&action=EditView&return_module=Properties&return_action=DetailView", $mod_strings['LNK_NEW_PROPERTY'], "CreateProperties", 'Properties');
}

if (ACLController::checkAccess('Properties', 'list', true)) {
    $module_menu[] = array("index.php?module=Properties&action=index&return_module=Properties&return_action=DetailView", $mod_strings['LNK_PROPERTY_LIST'], "Properties", 'Properties');
}

if (ACLController::checkAccess('Properties', 'import', true)) {
    $module_menu[] = array("index.php?module=Import&action=Step1&import_module=Properties&return_module=Properties&return_action=index", $app_strings['LBL_IMPORT'], "Import", 'Properties');
}