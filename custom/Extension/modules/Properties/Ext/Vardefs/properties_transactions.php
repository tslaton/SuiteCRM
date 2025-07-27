<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Properties to Opportunities/Transactions relationship (one-to-many)
$dictionary['Properties']['relationships']['properties_transactions'] = array(
    'lhs_module' => 'Properties',
    'lhs_table' => 'properties',
    'lhs_key' => 'id',
    'rhs_module' => 'Opportunities',
    'rhs_table' => 'opportunities',
    'rhs_key' => 'property_id',
    'relationship_type' => 'one-to-many',
);

// Link field for Properties module
$dictionary['Properties']['fields']['transactions'] = array(
    'name' => 'transactions',
    'type' => 'link',
    'relationship' => 'properties_transactions',
    'source' => 'non-db',
    'module' => 'Opportunities',
    'bean_name' => 'Opportunity',
    'vname' => 'LBL_TRANSACTIONS',
    'side' => 'left',
);