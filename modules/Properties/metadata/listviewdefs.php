<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Properties';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'width' => '15%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'MLS_ID' => array(
        'width' => '10%',
        'label' => 'LBL_MLS_ID',
        'default' => true,
    ),
    'STREET_ADDRESS' => array(
        'width' => '20%',
        'label' => 'LBL_STREET_ADDRESS',
        'default' => true,
    ),
    'CITY' => array(
        'width' => '10%',
        'label' => 'LBL_CITY',
        'default' => true,
    ),
    'STATE' => array(
        'width' => '5%',
        'label' => 'LBL_STATE',
        'default' => true,
    ),
    'PRICE' => array(
        'width' => '10%',
        'label' => 'LBL_PRICE',
        'currency_format' => true,
        'default' => true,
    ),
    'STATUS' => array(
        'width' => '10%',
        'label' => 'LBL_STATUS',
        'default' => true,
    ),
    'BEDROOMS' => array(
        'width' => '5%',
        'label' => 'LBL_BEDROOMS',
        'default' => false,
    ),
    'BATHROOMS' => array(
        'width' => '5%',
        'label' => 'LBL_BATHROOMS',
        'default' => false,
    ),
    'SQUARE_FOOTAGE' => array(
        'width' => '10%',
        'label' => 'LBL_SQUARE_FOOTAGE',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '9%',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
);