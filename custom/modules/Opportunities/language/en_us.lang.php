<?php
/**
 * Custom language file for Opportunities module
 * Renamed to Transactions for real estate terminology
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Override module labels to use "Transaction" instead of "Opportunity"
$mod_strings['LBL_MODULE_NAME'] = 'Transactions';
$mod_strings['LBL_MODULE_TITLE'] = 'Transactions: Home';
$mod_strings['LBL_SEARCH_FORM_TITLE'] = 'Transaction Search';
$mod_strings['LBL_LIST_FORM_TITLE'] = 'Transaction List';
$mod_strings['LBL_OPPORTUNITY_NAME'] = 'Transaction Name:';
$mod_strings['LBL_OPPORTUNITY'] = 'Transaction:';
$mod_strings['LBL_NAME'] = 'Transaction Name';
$mod_strings['LBL_LIST_OPPORTUNITY_NAME'] = 'Name';
$mod_strings['LBL_LIST_AMOUNT'] = 'Transaction Amount';
$mod_strings['UPDATE'] = 'Transaction - Currency Update';
$mod_strings['LBL_AMOUNT'] = 'Transaction Amount:';
$mod_strings['LBL_DUPLICATE'] = 'Possible Duplicate Transaction';
$mod_strings['MSG_DUPLICATE'] = 'The transaction record you are about to create might be a duplicate of a transaction record that already exists. Transaction records containing similar names are listed below.<br>Click Save to continue creating this new transaction, or click Cancel to return to the module without creating the transaction.';
$mod_strings['LBL_NEW_FORM_TITLE'] = 'Create Transaction';
$mod_strings['LNK_NEW_OPPORTUNITY'] = 'Create Transaction';
$mod_strings['LNK_OPPORTUNITY_LIST'] = 'View Transactions';
$mod_strings['ERR_DELETE_RECORD'] = 'A record number must be specified to delete the transaction.';
$mod_strings['LBL_TOP_OPPORTUNITIES'] = 'My Top Open Transactions';
$mod_strings['OPPORTUNITY_REMOVE_PROJECT_CONFIRM'] = 'Are you sure you want to remove this transaction from the project?';
$mod_strings['LBL_DEFAULT_SUBPANEL_TITLE'] = 'Transactions';
$mod_strings['LBL_MY_CLOSED_OPPORTUNITIES'] = 'My Closed Transactions';
$mod_strings['LBL_TOTAL_OPPORTUNITIES'] = 'Total Transactions';
$mod_strings['LBL_CLOSED_WON_OPPORTUNITIES'] = 'Closed Won Transactions';
$mod_strings['LBL_CAMPAIGN_OPPORTUNITY'] = 'Campaigns';
$mod_strings['LNK_IMPORT_OPPORTUNITIES'] = 'Import Transactions';

// Update Sales Stage dropdown options for real estate
$app_list_strings['sales_stage_dom'] = array(
    'Inquiry' => 'Inquiry',
    'Showing' => 'Showing',
    'Offer Made' => 'Offer Made',
    'Under Contract' => 'Under Contract',
    'Inspection/Appraisal' => 'Inspection/Appraisal',
    'Clear to Close' => 'Clear to Close',
    'Closed' => 'Closed',
);

// Update Sales Stage key dropdown (for database storage)
$app_list_strings['sales_stage_key_dom'] = array(
    'Inquiry' => 'Inquiry',
    'Showing' => 'Showing',
    'Offer Made' => 'Offer Made',
    'Under Contract' => 'Under Contract',
    'Inspection/Appraisal' => 'Inspection/Appraisal',
    'Clear to Close' => 'Clear to Close',
    'Closed' => 'Closed',
);

// Update probability mappings for new stages
$app_list_strings['sales_probability_dom'] = array(
    'Inquiry' => '10',
    'Showing' => '20',
    'Offer Made' => '40',
    'Under Contract' => '60',
    'Inspection/Appraisal' => '75',
    'Clear to Close' => '90',
    'Closed' => '100',
);