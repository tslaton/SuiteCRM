<?php
/**
 * Documents relationship for Properties
 */

// Add Documents link field to Properties
$dictionary['Properties']['fields']['documents'] = array(
    'name' => 'documents',
    'type' => 'link',
    'relationship' => 'documents_properties',
    'source' => 'non-db',
    'module' => 'Documents',
    'bean_name' => 'Document',
    'vname' => 'LBL_DOCUMENTS',
);