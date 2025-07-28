<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Add property_id field to Opportunities/Transactions
$dictionary['Opportunity']['fields']['property_id'] = array(
    'name' => 'property_id',
    'type' => 'id',
    'vname' => 'LBL_PROPERTY_ID',
    'audited' => true,
    'comment' => 'ID of the related property',
);

// Link field for Opportunities module
$dictionary['Opportunity']['fields']['property'] = array(
    'name' => 'property',
    'type' => 'link',
    'relationship' => 'properties_transactions',
    'source' => 'non-db',
    'module' => 'Properties',
    'bean_name' => 'Properties',
    'vname' => 'LBL_PROPERTY',
    'id_name' => 'property_id',
    'side' => 'right',
);

// Relate field to show property name
$dictionary['Opportunity']['fields']['property_name'] = array(
    'name' => 'property_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_PROPERTY_NAME',
    'save' => true,
    'id_name' => 'property_id',
    'link' => 'property',
    'table' => 'properties',
    'module' => 'Properties',
    'rname' => 'name',
);