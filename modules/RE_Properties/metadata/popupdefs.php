<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$popupMeta = array(
    'moduleMain' => 'RE_Properties',
    'varName' => 'RE_Properties',
    'orderBy' => 're_properties.name',
    'whereClauses' => array(
        'name' => 're_properties.name',
        'property_address' => 're_properties.property_address',
        'property_city' => 're_properties.property_city',
        'property_state' => 're_properties.property_state',
        'status' => 're_properties.status',
        'property_type' => 're_properties.property_type',
    ),
    'searchInputs' => array(
        'name',
        'property_address',
        'property_city',
        'property_state',
        'status',
        'property_type',
    ),
    'searchdefs' => array(
        'name' => array(
            'name' => 'name',
            'width' => '20%',
        ),
        'property_address' => array(
            'name' => 'property_address',
            'width' => '20%',
        ),
        'property_city' => array(
            'name' => 'property_city',
            'width' => '10%',
        ),
        'property_state' => array(
            'name' => 'property_state',
            'width' => '10%',
        ),
        'status' => array(
            'name' => 'status',
            'width' => '10%',
        ),
        'property_type' => array(
            'name' => 'property_type',
            'width' => '10%',
        ),
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'width' => '20%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
            'name' => 'name',
        ),
        'PROPERTY_ADDRESS' => array(
            'type' => 'varchar',
            'label' => 'LBL_PROPERTY_ADDRESS',
            'width' => '20%',
            'default' => true,
            'name' => 'property_address',
        ),
        'PROPERTY_CITY' => array(
            'type' => 'varchar',
            'label' => 'LBL_PROPERTY_CITY',
            'width' => '10%',
            'default' => true,
            'name' => 'property_city',
        ),
        'PROPERTY_TYPE' => array(
            'type' => 'enum',
            'label' => 'LBL_PROPERTY_TYPE',
            'width' => '10%',
            'default' => true,
            'name' => 'property_type',
        ),
        'STATUS' => array(
            'type' => 'enum',
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'default' => true,
            'name' => 'status',
        ),
        'LISTING_PRICE' => array(
            'type' => 'currency',
            'label' => 'LBL_LISTING_PRICE',
            'currency_format' => true,
            'width' => '10%',
            'default' => true,
            'name' => 'listing_price',
        ),
    ),
);