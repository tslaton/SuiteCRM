<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['contacts_transactions_roles'] = array(
    'table' => 'contacts_transactions_roles',
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
        'transaction_id' => array(
            'name' => 'transaction_id',
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
            'name' => 'contacts_transactions_rolespk',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'idx_cont_trans_cont',
            'type' => 'index',
            'fields' => array('contact_id'),
        ),
        array(
            'name' => 'idx_cont_trans_trans',
            'type' => 'index',
            'fields' => array('transaction_id'),
        ),
        array(
            'name' => 'idx_cont_trans_role',
            'type' => 'index',
            'fields' => array('contact_role'),
        ),
    ),
    'relationships' => array(
        'contacts_transactions_roles' => array(
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
        ),
    ),
);