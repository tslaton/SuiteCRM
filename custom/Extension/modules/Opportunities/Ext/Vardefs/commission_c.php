<?php
/**
 * Custom field definition for commission tracking in Transactions (Opportunities)
 */

$dictionary['Opportunity']['fields']['commission_c'] = array(
    'name' => 'commission_c',
    'vname' => 'LBL_COMMISSION',
    'type' => 'currency',
    'dbType' => 'decimal',
    'len' => '26,6',
    'size' => '20',
    'precision' => '6',
    'comment' => 'Commission amount for the transaction',
    'audited' => true,
    'reportable' => true,
    'duplicate_merge' => 'enabled',
    'massupdate' => true,
    'importable' => 'true',
    'default' => '0.000000',
);