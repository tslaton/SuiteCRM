<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings;

$popupMeta = array(
    'moduleMain' => 'Properties',
    'varName' => 'PROPERTIES',
    'orderBy' => 'name',
    'whereClauses' => array(
        'name' => 'properties.name',
        'mls_id' => 'properties.mls_id',
        'city' => 'properties.city',
        'status' => 'properties.status',
    ),
    'searchInputs' => array(
        'name',
        'mls_id',
        'city',
        'status',
    ),
    'create' => array(
        'formBase' => 'PropertiesFormBase.php',
        'formBaseClass' => 'PropertiesFormBase',
        'getFormBodyParams' => array('', '', 'PropertiesSave'),
        'createButton' => $mod_strings['LNK_NEW_RECORD']
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'width' => '20%',
            'label' => 'LBL_NAME',
            'link' => true,
            'default' => true,
        ),
        'MLS_ID' => array(
            'width' => '15%',
            'label' => 'LBL_MLS_ID',
            'default' => true,
        ),
        'STREET_ADDRESS' => array(
            'width' => '25%',
            'label' => 'LBL_STREET_ADDRESS',
            'default' => true,
        ),
        'CITY' => array(
            'width' => '15%',
            'label' => 'LBL_CITY',
            'default' => true,
        ),
        'STATUS' => array(
            'width' => '15%',
            'label' => 'LBL_STATUS',
            'default' => true,
        ),
        'ASSIGNED_USER_NAME' => array(
            'width' => '10%',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'default' => true,
        ),
    ),
    'searchdefs' => array(
        'name',
        'mls_id',
        'city',
        'status',
        array(
            'name' => 'assigned_user_id',
            'type' => 'enum',
            'label' => 'LBL_ASSIGNED_TO',
            'function' => array(
                'name' => 'get_user_array',
                'params' => array(false)
            )
        ),
    )
);