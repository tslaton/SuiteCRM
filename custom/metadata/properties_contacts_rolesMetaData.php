<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['properties_contacts_roles'] = array(
    'table' => 'properties_contacts_roles',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'property_id' => array(
            'name' => 'property_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'contact_id' => array(
            'name' => 'contact_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        'contact_role' => array(
            'name' => 'contact_role',
            'type' => 'enum',
            'options' => 'property_contact_role_list',
            'len' => 50,
            'default' => 'other',
            'comment' => 'Role of the contact in relation to the property',
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
            'name' => 'properties_contacts_rolespk',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'idx_prop_cont_prop',
            'type' => 'index',
            'fields' => array('property_id'),
        ),
        array(
            'name' => 'idx_prop_cont_cont',
            'type' => 'index',
            'fields' => array('contact_id'),
        ),
        array(
            'name' => 'idx_prop_cont_role',
            'type' => 'index',
            'fields' => array('contact_role'),
        ),
    ),
    'relationships' => array(
        'properties_contacts_roles' => array(
            'lhs_module' => 'Properties',
            'lhs_table' => 'properties',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'properties_contacts_roles',
            'join_key_lhs' => 'property_id',
            'join_key_rhs' => 'contact_id',
            'relationship_role_column' => 'contact_role',
        ),
    ),
);