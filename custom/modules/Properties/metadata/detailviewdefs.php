<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Properties';
$viewdefs[$module_name] = array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    3 => 'FIND_DUPLICATES',
                    4 => array(
                        'customCode' => '<input type="button" class="button" onClick="window.location=\'index.php?module=Opportunities&action=EditView&return_module=Properties&return_action=DetailView&return_id={$fields.id.value}&property_id={$fields.id.value}&property_name={$fields.name.value}\'" value="{$MOD.LBL_CREATE_TRANSACTION}">',
                    ),
                    5 => array(
                        'customCode' => '<input type="button" class="button" onClick="window.open(\'index.php?module=Properties&action=generateqr&record={$fields.id.value}\', \'QRCode\', \'width=850,height=800,resizable=yes,scrollbars=yes\');" value="Open House QR Code">',
                    ),
                ),
            ),
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
                    0 => 'name',
                    1 => 'mls_id',
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
                    0 => 'description',
                ),
                7 => array(
                    0 => 'assigned_user_name',
                    1 => array(
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
                8 => array(
                    0 => array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
            ),
        ),
    ),
);