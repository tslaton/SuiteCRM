<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Add the contacts_roles link field
$dictionary['Properties']['fields']['contacts_roles'] = array(
    'name' => 'contacts_roles',
    'type' => 'link',
    'relationship' => 'properties_contacts_roles_link',
    'source' => 'non-db',
    'module' => 'Contacts',
    'bean_name' => 'Contact',
    'vname' => 'LBL_CONTACTS',
    'link_class' => 'PropertiesContactsRolesRelationship',
    'link_file' => 'custom/modules/Properties/PropertiesContactsRolesRelationship.php',
);

// Define the custom relationship
$dictionary['Properties']['relationships']['properties_contacts_roles_link'] = array(
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
);