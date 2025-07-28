<?php
/**
 * Custom Menu for Opportunities Module
 * Adds Kanban/Pipeline view option
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

// Start with empty menu
$module_menu = array();

// Add standard menu items
if (ACLController::checkAccess('Opportunities', 'edit', true)) {
    $module_menu[] = array(
        "index.php?module=Opportunities&action=EditView&return_module=Opportunities&return_action=DetailView",
        $mod_strings['LNK_NEW_OPPORTUNITY'],
        "Create"
    );
}

if (ACLController::checkAccess('Opportunities', 'list', true)) {
    $module_menu[] = array(
        "index.php?module=Opportunities&action=index&return_module=Opportunities&return_action=DetailView",
        $mod_strings['LNK_OPPORTUNITY_LIST'],
        "List"
    );
    
    // Add Kanban/Pipeline view
    $module_menu[] = array(
        "index.php?module=Opportunities&action=kanban",
        $mod_strings['LNK_KANBAN_VIEW'] ?? 'Pipeline View',
        "View-Pipeline"
    );
}

if (ACLController::checkAccess('Opportunities', 'import', true)) {
    $module_menu[] = array(
        "index.php?module=Import&action=Step1&import_module=Opportunities&return_module=Opportunities&return_action=index",
        $mod_strings['LNK_IMPORT_OPPORTUNITIES'],
        "Import"
    );
}