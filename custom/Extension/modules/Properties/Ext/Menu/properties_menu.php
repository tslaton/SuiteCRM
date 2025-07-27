<?php
/**
 * Properties Module Menu Items
 */

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;

// Clear existing menu to rebuild it
$module_menu = array();

// Primary actions
if (ACLController::checkAccess('Properties', 'edit', true)) {
    $module_menu[] = array(
        'index.php?module=Properties&action=EditView&return_module=Properties&return_action=DetailView',
        isset($mod_strings['LNK_NEW_PROPERTY']) ? $mod_strings['LNK_NEW_PROPERTY'] : 'Create Property',
        'CreateProperty',
        'Properties'
    );
}

if (ACLController::checkAccess('Properties', 'list', true)) {
    $module_menu[] = array(
        'index.php?module=Properties&action=index',
        isset($mod_strings['LNK_PROPERTY_LIST']) ? $mod_strings['LNK_PROPERTY_LIST'] : 'View Properties',
        'Properties',
        'Properties'
    );
}

// Import functionality
if (ACLController::checkAccess('Properties', 'import', true)) {
    $module_menu[] = array(
        'index.php?module=Import&action=Step1&import_module=Properties&return_module=Properties&return_action=index',
        $app_strings['LBL_IMPORT'],
        'Import',
        'Properties'
    );
}

// Demo data loader (admin only)
global $current_user;
if (is_admin($current_user)) {
    $module_menu[] = array(
        'index.php?module=Properties&action=loadDemoData',
        'Load Demo Properties',
        'CreateProperty',
        'Properties'
    );
}