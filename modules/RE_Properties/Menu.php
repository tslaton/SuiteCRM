<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if (ACLController::checkAccess('RE_Properties', 'edit', true)) {
    $module_menu[] = array(
        'index.php?module=RE_Properties&action=EditView&return_module=RE_Properties&return_action=DetailView',
        $mod_strings['LNK_NEW_PROPERTY'],
        'Create',
        'RE_Properties'
    );
}

if (ACLController::checkAccess('RE_Properties', 'list', true)) {
    $module_menu[] = array(
        'index.php?module=RE_Properties&action=index',
        $mod_strings['LNK_PROPERTY_LIST'],
        'List',
        'RE_Properties'
    );
}

if (ACLController::checkAccess('RE_Properties', 'import', true)) {
    $module_menu[] = array(
        'index.php?module=Import&action=Step1&import_module=RE_Properties&return_module=RE_Properties&return_action=index',
        $app_strings['LBL_IMPORT'],
        'Import',
        'RE_Properties'
    );
}