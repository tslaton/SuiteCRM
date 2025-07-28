<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Properties';
$searchdefs[$module_name] = array(
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'mls_id' => array(
                'name' => 'mls_id',
                'default' => true,
                'width' => '10%',
            ),
            'city' => array(
                'name' => 'city',
                'default' => true,
                'width' => '10%',
            ),
            'status' => array(
                'name' => 'status',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'mls_id' => array(
                'name' => 'mls_id',
                'default' => true,
                'width' => '10%',
            ),
            'street_address' => array(
                'name' => 'street_address',
                'default' => true,
                'width' => '10%',
            ),
            'city' => array(
                'name' => 'city',
                'default' => true,
                'width' => '10%',
            ),
            'state' => array(
                'name' => 'state',
                'default' => true,
                'width' => '10%',
            ),
            'zip_code' => array(
                'name' => 'zip_code',
                'default' => true,
                'width' => '10%',
            ),
            'price' => array(
                'name' => 'price',
                'default' => true,
                'width' => '10%',
            ),
            'status' => array(
                'name' => 'status',
                'default' => true,
                'width' => '10%',
            ),
            'bedrooms' => array(
                'name' => 'bedrooms',
                'default' => true,
                'width' => '10%',
            ),
            'bathrooms' => array(
                'name' => 'bathrooms',
                'default' => true,
                'width' => '10%',
            ),
            'square_footage' => array(
                'name' => 'square_footage',
                'default' => true,
                'width' => '10%',
            ),
            'assigned_user_id' => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(false),
                ),
                'default' => true,
                'width' => '10%',
            ),
        ),
    ),
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30',
        ),
    ),
);