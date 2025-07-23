<?php
$module_name = 'RE_Properties';
$listViewDefs [$module_name] = array(
    'COVER_IMAGE' => array(
        'type' => 'image',
        'studio' => 'visible',
        'width' => '5%',
        'label' => 'LBL_COVER_IMAGE',
        'sortable' => false,
        'default' => true,
    ),
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'PROPERTY_ADDRESS' => array(
        'type' => 'varchar',
        'label' => 'LBL_LIST_ADDRESS',
        'width' => '15%',
        'default' => true,
    ),
    'PROPERTY_CITY' => array(
        'type' => 'varchar',
        'label' => 'LBL_LIST_CITY',
        'width' => '10%',
        'default' => true,
    ),
    'PROPERTY_STATE' => array(
        'type' => 'varchar',
        'label' => 'LBL_LIST_STATE',
        'width' => '5%',
        'default' => true,
    ),
    'PROPERTY_TYPE' => array(
        'type' => 'enum',
        'label' => 'LBL_LIST_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'STATUS' => array(
        'type' => 'enum',
        'label' => 'LBL_LIST_STATUS',
        'width' => '10%',
        'default' => true,
    ),
    'LISTING_PRICE' => array(
        'type' => 'currency',
        'label' => 'LBL_LIST_PRICE',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
    ),
    'BEDROOMS' => array(
        'type' => 'int',
        'label' => 'LBL_LIST_BEDROOMS',
        'width' => '5%',
        'default' => true,
    ),
    'BATHROOMS' => array(
        'type' => 'decimal',
        'label' => 'LBL_LIST_BATHROOMS',
        'width' => '5%',
        'default' => true,
    ),
    'SQUARE_FOOTAGE' => array(
        'type' => 'int',
        'label' => 'LBL_LIST_SQFT',
        'width' => '10%',
        'default' => true,
    ),
    'LISTING_DATE' => array(
        'type' => 'date',
        'label' => 'LBL_LISTING_DATE',
        'width' => '10%',
        'default' => false,
    ),
    'MLS_ID' => array(
        'type' => 'varchar',
        'label' => 'LBL_MLS_ID',
        'width' => '10%',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '9%',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
);