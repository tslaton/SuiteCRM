<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Properties to Contacts relationship with roles
$dictionary['Properties']['relationships']['properties_contacts_roles'] = array(
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
    'relationship_role_column_value' => array('buyer', 'seller', 'agent', 'inspector', 'attorney', 'other'),
);

// Link field for Properties module
$dictionary['Properties']['fields']['contacts_roles'] = array(
    'name' => 'contacts_roles',
    'type' => 'link',
    'relationship' => 'properties_contacts_roles',
    'source' => 'non-db',
    'module' => 'Contacts',
    'bean_name' => 'Contact',
    'vname' => 'LBL_CONTACTS_ROLES',
    'id_name' => 'contact_id',
    'link_type' => 'many',
    'side' => 'left',
);