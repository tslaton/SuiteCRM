<?php
/**
 * Real Estate Navigation Menu Customization
 * Prioritizes real estate-specific modules
 */

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $app_strings, $app_list_strings, $moduleList, $modInvisList, $beanList, $beanFiles;

// Clear existing module list to rebuild it
$moduleList = array();

// Primary Real Estate Modules (always visible)
$moduleList[] = 'Home';
$moduleList[] = 'Properties';
$moduleList[] = 'Contacts';
$moduleList[] = 'Opportunities'; // Transactions
$moduleList[] = 'Leads';
$moduleList[] = 'MockMLS';

// Secondary Business Modules
$moduleList[] = 'Calendar';
$moduleList[] = 'Documents';
$moduleList[] = 'Emails';
$moduleList[] = 'Tasks';
$moduleList[] = 'Meetings';
$moduleList[] = 'Calls';
$moduleList[] = 'Notes';

// Administrative Modules (grouped)
$moduleList[] = 'Accounts';
$moduleList[] = 'Campaigns';
$moduleList[] = 'Cases';
$moduleList[] = 'Projects';
$moduleList[] = 'Reports';

// System Modules (usually hidden or in user menu)
$moduleList[] = 'Users';
$moduleList[] = 'Employees';
$moduleList[] = 'Administration';

// Make Properties and MockMLS visible in tabs if they were hidden
if (($key = array_search('Properties', $modInvisList)) !== false) {
    unset($modInvisList[$key]);
}
if (($key = array_search('MockMLS', $modInvisList)) !== false) {
    unset($modInvisList[$key]);
}

// Ensure module labels are set
if (!isset($app_list_strings['moduleList']['Properties'])) {
    $app_list_strings['moduleList']['Properties'] = 'Properties';
}
if (!isset($app_list_strings['moduleList']['MockMLS'])) {
    $app_list_strings['moduleList']['MockMLS'] = 'MLS Data';
}

// Rename Opportunities to Transactions in the module list
$app_list_strings['moduleList']['Opportunities'] = 'Transactions';

// Custom menu grouping for dropdown organization
$app_list_strings['moduleListGroupings'] = array(
    'Real Estate' => array('Properties', 'Contacts', 'Opportunities', 'Leads'),
    'Activities' => array('Calendar', 'Tasks', 'Meetings', 'Calls', 'Emails', 'Notes'),
    'Resources' => array('Documents', 'MockMLS', 'Reports'),
    'Other' => array('Accounts', 'Campaigns', 'Cases', 'Projects'),
);