<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopButtonQuickCreate'),
        array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '25%',
        ),
        'email1' => array(
            'name' => 'email1',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'width' => '25%',
        ),
        'phone_work' => array(
            'name' => 'phone_work',
            'vname' => 'LBL_OFFICE_PHONE',
            'width' => '15%',
        ),
        'phone_mobile' => array(
            'name' => 'phone_mobile',
            'vname' => 'LBL_MOBILE_PHONE',
            'width' => '15%',
        ),
        'contact_role' => array(
            'name' => 'contact_role',
            'vname' => 'LBL_CONTACT_ROLE',
            'width' => '20%',
            'sortable' => false,
            'default' => true,
        ),
    ),
);