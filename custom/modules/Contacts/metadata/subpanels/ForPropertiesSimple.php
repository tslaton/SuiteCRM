<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Contacts'),
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
            'widget_class' => 'SubPanelEmailLink',
            'width' => '25%',
        ),
        'phone_work' => array(
            'name' => 'phone_work',
            'vname' => 'LBL_OFFICE_PHONE',
            'width' => '20%',
        ),
        'phone_mobile' => array(
            'name' => 'phone_mobile',
            'vname' => 'LBL_MOBILE_PHONE',
            'width' => '20%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'Contacts',
            'width' => '5%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'Contacts',
            'width' => '5%',
        ),
    ),
);