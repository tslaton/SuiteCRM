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
            'width' => '20%',
        ),
        'contact_role' => array(
            'name' => 'contact_role',
            'vname' => 'LBL_CONTACT_ROLE',
            'width' => '15%',
            'sortable' => false,
            'custom_type' => 'enum',
            'custom_options' => 'transaction_contact_role_list',
        ),
        'email1' => array(
            'name' => 'email1',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'widget_class' => 'SubPanelEmailLink',
            'width' => '20%',
        ),
        'phone_work' => array(
            'name' => 'phone_work',
            'vname' => 'LBL_OFFICE_PHONE',
            'width' => '15%',
        ),
        'commission_percentage' => array(
            'name' => 'commission_percentage',
            'vname' => 'LBL_COMMISSION_PERCENTAGE',
            'width' => '10%',
            'sortable' => false,
        ),
        'commission_amount' => array(
            'name' => 'commission_amount',
            'vname' => 'LBL_COMMISSION_AMOUNT',
            'width' => '10%',
            'sortable' => false,
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'Contacts',
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'Contacts',
            'width' => '4%',
        ),
    ),
);