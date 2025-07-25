<?php
/**
 * Custom language file for Contacts module
 * Updates Opportunities references to Transactions
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Override Opportunities references in Contacts
$mod_strings['LBL_CONTACT_OPP_FORM_TITLE'] = 'Contact-Transaction:';
$mod_strings['LBL_OPP_NAME'] = 'Transaction Name:';
$mod_strings['LBL_OPPORTUNITY_ROLE_ID'] = 'Transaction Role ID:';
$mod_strings['LBL_OPPORTUNITY_ROLE'] = 'Transaction Role';
$mod_strings['LNK_NEW_OPPORTUNITY'] = 'Create Transaction';
$mod_strings['LBL_OPPORTUNITIES_SUBPANEL_TITLE'] = 'Transactions';
$mod_strings['LBL_OPPORTUNITIES'] = 'Transactions';