<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Contacts to Opportunities/Transactions relationship with roles
$dictionary['Contact']['relationships']['contacts_transactions_roles'] = array(
    'lhs_module' => 'Contacts',
    'lhs_table' => 'contacts',
    'lhs_key' => 'id',
    'rhs_module' => 'Opportunities',
    'rhs_table' => 'opportunities',
    'rhs_key' => 'id',
    'relationship_type' => 'many-to-many',
    'join_table' => 'contacts_transactions_roles',
    'join_key_lhs' => 'contact_id',
    'join_key_rhs' => 'transaction_id',
    'relationship_role_column' => 'contact_role',
    'relationship_role_column_value' => array('buyer', 'seller', 'buyer_agent', 'seller_agent', 'inspector', 'attorney', 'title_agent', 'other'),
);

// Link field for Contacts module
$dictionary['Contact']['fields']['transactions_roles'] = array(
    'name' => 'transactions_roles',
    'type' => 'link',
    'relationship' => 'contacts_transactions_roles',
    'source' => 'non-db',
    'module' => 'Opportunities',
    'bean_name' => 'Opportunity',
    'vname' => 'LBL_TRANSACTIONS_ROLES',
    'id_name' => 'transaction_id',
    'link_type' => 'many',
    'side' => 'left',
);