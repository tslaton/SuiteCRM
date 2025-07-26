<?php
/**
 * Mock MLS Module Menu
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if (ACLController::checkAccess('MockMLS', 'edit', true)) {
    $module_menu[] = array('index.php?module=MockMLS&action=EditView&return_module=MockMLS&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'MockMLS');
}

if (ACLController::checkAccess('MockMLS', 'list', true)) {
    $module_menu[] = array('index.php?module=MockMLS&action=index', $mod_strings['LNK_LIST'], 'View', 'MockMLS');
}

if (ACLController::checkAccess('MockMLS', 'import', true)) {
    $module_menu[] = array('index.php?module=Import&action=Step1&import_module=MockMLS&return_module=MockMLS&return_action=index', $app_strings['LBL_IMPORT'], 'Import', 'MockMLS');
}