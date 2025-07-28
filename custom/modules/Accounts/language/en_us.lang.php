<?php
/**
 * Custom language file for Accounts module
 * Updates Opportunities references to Transactions
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Override Opportunities subpanel title in Accounts
$mod_strings['LBL_OPPORTUNITIES_SUBPANEL_TITLE'] = 'Transactions';