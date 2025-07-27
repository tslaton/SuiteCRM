<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Properties';
$viewdefs[$module_name] = array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => false,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'default' => array(
                0 => array(
                    0 => array(
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                    1 => array(
                        'name' => 'mls_id',
                        'label' => 'LBL_MLS_ID',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'street_address',
                        'label' => 'LBL_STREET_ADDRESS',
                    ),
                    1 => array(
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'city',
                        'label' => 'LBL_CITY',
                    ),
                    1 => array(
                        'name' => 'state',
                        'label' => 'LBL_STATE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'zip_code',
                        'label' => 'LBL_ZIP_CODE',
                    ),
                    1 => array(
                        'name' => 'price',
                        'label' => 'LBL_PRICE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'bedrooms',
                        'label' => 'LBL_BEDROOMS',
                    ),
                    1 => array(
                        'name' => 'bathrooms',
                        'label' => 'LBL_BATHROOMS',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'square_footage',
                        'label' => 'LBL_SQUARE_FOOTAGE',
                    ),
                    1 => array(
                        'name' => 'main_photo',
                        'label' => 'LBL_MAIN_PHOTO',
                        'type' => 'image',
                    ),
                ),
                6 => array(
                    0 => array(
                        'name' => 'description',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
                7 => array(
                    0 => 'assigned_user_name',
                ),
            ),
        ),
    ),
);