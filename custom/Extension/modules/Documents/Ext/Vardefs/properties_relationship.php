<?php
/**
 * Properties relationship for Documents
 */

// Add Properties link field to Documents
$dictionary['Document']['fields']['properties'] = array(
    'name' => 'properties',
    'type' => 'link',
    'relationship' => 'documents_properties',
    'source' => 'non-db',
    'module' => 'Properties',
    'bean_name' => 'Properties',
    'vname' => 'LBL_PROPERTIES',
);