<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Link field for Contacts module
$dictionary['Contact']['fields']['properties_roles'] = array(
    'name' => 'properties_roles',
    'type' => 'link',
    'relationship' => 'properties_contacts_roles',
    'source' => 'non-db',
    'module' => 'Properties',
    'bean_name' => 'Properties',
    'vname' => 'LBL_PROPERTIES_ROLES',
    'id_name' => 'property_id',
    'link_type' => 'many',
    'side' => 'right',
);