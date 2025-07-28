<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if (ACLController::checkAccess('Properties', 'edit', true)) {
    $module_menu[] = array('index.php?module=Properties&action=EditView&return_module=Properties&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'Properties');
}
if (ACLController::checkAccess('Properties', 'list', true)) {
    $module_menu[] = array('index.php?module=Properties&action=index&return_module=Properties&return_action=DetailView', $mod_strings['LNK_LIST'], 'View', 'Properties');
}
if (ACLController::checkAccess('Properties', 'import', true)) {
    $module_menu[] = array('index.php?module=Import&action=Step1&import_module=Properties&return_module=Properties&return_action=index', $mod_strings['LNK_IMPORT_PROPERTIES'], 'Import', 'Properties');
}