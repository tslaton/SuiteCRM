<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = array(
    'top_buttons' => array(),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '25%',
        ),
        'mls_id' => array(
            'name' => 'mls_id',
            'vname' => 'LBL_MLS_ID',
            'width' => '15%',
        ),
        'street_address' => array(
            'name' => 'street_address',
            'vname' => 'LBL_STREET_ADDRESS',
            'width' => '25%',
        ),
        'price' => array(
            'name' => 'price',
            'vname' => 'LBL_PRICE',
            'width' => '15%',
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'width' => '15%',
        ),
    ),
);