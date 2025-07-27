<?php
/**
 * Documents - Properties relationship metadata
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['documents_properties'] = array(
    'true_relationship_type' => 'many-to-many',
    'relationships' => array(
        'documents_properties' => array(
            'lhs_module' => 'Documents',
            'lhs_table' => 'documents',
            'lhs_key' => 'id',
            'rhs_module' => 'Properties',
            'rhs_table' => 'properties',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'documents_properties',
            'join_key_lhs' => 'document_id',
            'join_key_rhs' => 'property_id',
        ),
    ),
    'table' => 'documents_properties',
    'fields' => array(
        array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
        ),
        array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => true,
        ),
        array(
            'name' => 'document_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        array(
            'name' => 'property_id',
            'type' => 'varchar',
            'len' => 36,
        ),
        array(
            'name' => 'document_revision_id',
            'type' => 'varchar',
            'len' => 36,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'documents_propertiesspk',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'documents_properties_property_id',
            'type' => 'alternate_key',
            'fields' => array('property_id', 'document_id'),
        ),
        array(
            'name' => 'documents_properties_document_id',
            'type' => 'alternate_key',
            'fields' => array('document_id', 'property_id'),
        ),
    ),
);