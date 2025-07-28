<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Add property_id field to Opportunities
$dictionary['Opportunity']['fields']['property_id'] = array(
    'name' => 'property_id',
    'vname' => 'LBL_PROPERTY_ID',
    'type' => 'id',
    'len' => 36,
    'comment' => 'Property associated with this transaction',
    'reportable' => true,
);

// Add property name field for display
$dictionary['Opportunity']['fields']['property_name'] = array(
    'name' => 'property_name',
    'rname' => 'name',
    'id_name' => 'property_id',
    'vname' => 'LBL_PROPERTY_NAME',
    'type' => 'relate',
    'table' => 'properties',
    'module' => 'Properties',
    'dbType' => 'varchar',
    'link' => 'properties',
    'len' => '255',
    'source' => 'non-db',
    'unified_search' => true,
);

// Add property relationship link
$dictionary['Opportunity']['fields']['properties'] = array(
    'name' => 'properties',
    'type' => 'link',
    'relationship' => 'properties_opportunities',
    'source' => 'non-db',
    'module' => 'Properties',
    'bean_name' => 'Properties',
    'vname' => 'LBL_PROPERTIES',
    'id_name' => 'property_id',
    'link_type' => 'one',
    'side' => 'right',
);