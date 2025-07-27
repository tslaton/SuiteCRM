<?php
/**
 * Custom field definition for commission rate in Transactions (Opportunities)
 * Allows overriding the default commission rate per transaction
 */

$dictionary['Opportunity']['fields']['commission_rate_c'] = array(
    'name' => 'commission_rate_c',
    'vname' => 'LBL_COMMISSION_RATE',
    'type' => 'decimal',
    'len' => '5,2',
    'size' => '20',
    'precision' => '2',
    'comment' => 'Commission rate percentage for this transaction',
    'audited' => true,
    'reportable' => true,
    'duplicate_merge' => 'enabled',
    'massupdate' => true,
    'importable' => 'true',
    'help' => 'Commission rate as percentage (e.g., 2.5 for 2.5%)',
    'studio' => true,
);