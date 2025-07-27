<?php
/**
 * Properties-Opportunities many-to-many relationship
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Add Properties link field to Opportunities
$dictionary['Opportunity']['fields']['properties'] = array(
    'name' => 'properties',
    'type' => 'link',
    'relationship' => 'properties_opportunities',
    'source' => 'non-db',
    'module' => 'Properties',
    'bean_name' => 'Properties',
    'vname' => 'LBL_PROPERTIES',
    'side' => 'right',
);