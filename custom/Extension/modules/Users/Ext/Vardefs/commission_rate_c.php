<?php
/**
 * Custom field definition for default commission rate on Users
 * Stores the agent's default commission rate
 */

$dictionary['User']['fields']['commission_rate_c'] = array(
    'name' => 'commission_rate_c',
    'vname' => 'LBL_DEFAULT_COMMISSION_RATE',
    'type' => 'decimal',
    'len' => '5,2',
    'size' => '20',
    'precision' => '2',
    'comment' => 'Default commission rate percentage for this user',
    'audited' => true,
    'reportable' => true,
    'duplicate_merge' => 'enabled',
    'massupdate' => false,
    'importable' => 'true',
    'help' => 'Default commission rate as percentage (e.g., 2.5 for 2.5%)',
    'studio' => true,
    'default' => '2.5',
);