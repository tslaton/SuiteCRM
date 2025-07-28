<?php
/**
 * Properties relationship for Contacts
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Add Properties link field to Contacts
$dictionary['Contact']['fields']['properties'] = array(
    'name' => 'properties',
    'type' => 'link',
    'relationship' => 'properties_contacts',
    'source' => 'non-db',
    'module' => 'Properties',
    'bean_name' => 'Properties',
    'vname' => 'LBL_PROPERTIES',
    'side' => 'right',
);