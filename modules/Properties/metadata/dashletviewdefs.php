<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['MyPropertiesDashlet']['searchFields'] = array(
    'name' => array('default' => ''),
    'mls_id' => array('default' => ''),
    'city' => array('default' => ''),
    'state' => array('default' => ''),
    'status' => array('default' => ''),
    'assigned_user_id' => array('default' => ''),
);

$dashletData['MyPropertiesDashlet']['columns'] = array(
    'name' => array(
        'width' => '25%',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
    ),
    'mls_id' => array(
        'width' => '10%',
        'label' => 'LBL_MLS_ID',
        'default' => true,
    ),
    'street_address' => array(
        'width' => '20%',
        'label' => 'LBL_STREET_ADDRESS',
        'default' => true,
    ),
    'city' => array(
        'width' => '10%',
        'label' => 'LBL_CITY',
        'default' => true,
    ),
    'state' => array(
        'width' => '5%',
        'label' => 'LBL_STATE',
        'default' => true,
    ),
    'price' => array(
        'width' => '10%',
        'label' => 'LBL_PRICE',
        'currency_format' => true,
        'default' => true,
    ),
    'status' => array(
        'width' => '10%',
        'label' => 'LBL_STATUS',
        'default' => true,
    ),
    'bedrooms' => array(
        'width' => '5%',
        'label' => 'LBL_BEDROOMS',
        'default' => false,
    ),
    'bathrooms' => array(
        'width' => '5%',
        'label' => 'LBL_BATHROOMS',
        'default' => false,
    ),
    'square_footage' => array(
        'width' => '10%',
        'label' => 'LBL_SQUARE_FOOTAGE',
        'default' => false,
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'default' => false,
    ),
    'assigned_user_name' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO',
        'default' => false,
    ),
);