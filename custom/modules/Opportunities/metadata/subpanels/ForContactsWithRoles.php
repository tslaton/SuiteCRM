<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Opportunities'),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '25%',
        ),
        'contact_role' => array(
            'name' => 'contact_role',
            'vname' => 'LBL_CONTACT_ROLE',
            'width' => '15%',
            'sortable' => false,
            'custom_type' => 'enum',
            'custom_options' => 'transaction_contact_role_list',
        ),
        'property_name' => array(
            'name' => 'property_name',
            'vname' => 'LBL_PROPERTY_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'property_id',
            'target_module' => 'Properties',
            'width' => '20%',
        ),
        'sales_stage' => array(
            'name' => 'sales_stage',
            'vname' => 'LBL_SALES_STAGE',
            'width' => '15%',
        ),
        'amount_usdollar' => array(
            'name' => 'amount_usdollar',
            'vname' => 'LBL_AMOUNT',
            'width' => '15%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'Opportunities',
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'Opportunities',
            'width' => '4%',
        ),
    ),
);