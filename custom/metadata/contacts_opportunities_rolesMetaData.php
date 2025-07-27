<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['contacts_opportunities_roles'] = array(
    'table' => 'contacts_opportunities_roles',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'contact_id' => array(
            'name' => 'contact_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'opportunity_id' => array(
            'name' => 'opportunity_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'contact_role' => array(
            'name' => 'contact_role',
            'type' => 'enum',
            'options' => 'transaction_contact_role_list',
            'len' => 50,
            'default' => 'other',
            'comment' => 'Role of the contact in the transaction',
        ),
        'commission_percentage' => array(
            'name' => 'commission_percentage',
            'type' => 'decimal',
            'len' => '5,2',
            'comment' => 'Commission percentage for agents',
        ),
        'commission_amount' => array(
            'name' => 'commission_amount',
            'type' => 'currency',
            'len' => '26,6',
            'comment' => 'Commission amount for agents',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'contacts_opportunities_rolespk',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'idx_cont_opp_cont',
            'type' => 'index',
            'fields' => array('contact_id'),
        ),
        array(
            'name' => 'idx_cont_opp_opp',
            'type' => 'index',
            'fields' => array('opportunity_id'),
        ),
        array(
            'name' => 'idx_cont_opp_role',
            'type' => 'index',
            'fields' => array('contact_role'),
        ),
    ),
    'relationships' => array(
        'contacts_opportunities_roles' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'Opportunities',
            'rhs_table' => 'opportunities',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'contacts_opportunities_roles',
            'join_key_lhs' => 'contact_id',
            'join_key_rhs' => 'opportunity_id',
            'relationship_role_column' => 'contact_role',
        ),
    ),
);