<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Link field for Opportunities module
$dictionary['Opportunity']['fields']['contacts_roles'] = array(
    'name' => 'contacts_roles',
    'type' => 'link',
    'relationship' => 'contacts_opportunities_roles',
    'source' => 'non-db',
    'module' => 'Contacts',
    'bean_name' => 'Contact',
    'vname' => 'LBL_CONTACTS_ROLES',
    'id_name' => 'contact_id',
    'link_type' => 'many',
    'side' => 'right',
);