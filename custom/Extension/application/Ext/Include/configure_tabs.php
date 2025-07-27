<?php
/**
 * Configure Module Tabs for Real Estate
 */

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $moduleList, $modInvisList, $app_list_strings;

// Define the displayed tabs in order
$moduleList = array(
    'Home',
    'Properties',
    'Contacts', 
    'Opportunities', // Transactions
    'Leads',
    'Calendar',
    'Documents',
    'Emails',
    'Meetings',
    'Calls',
    'Tasks',
    'Notes',
    'Reports',
    'MockMLS',
    'Accounts',
    'Campaigns',
    'Cases'
);

// Remove Properties and MockMLS from hidden modules
$key = array_search('Properties', $modInvisList);
if ($key !== false) {
    unset($modInvisList[$key]);
}

$key = array_search('MockMLS', $modInvisList);
if ($key !== false) {
    unset($modInvisList[$key]);
}

// Ensure these modules are always visible
$modInvisList = array_values(array_diff($modInvisList, array('Properties', 'MockMLS')));