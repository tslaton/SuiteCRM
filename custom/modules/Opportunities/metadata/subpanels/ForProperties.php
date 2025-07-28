<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '30%',
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
        'date_closed' => array(
            'name' => 'date_closed',
            'vname' => 'LBL_DATE_CLOSED',
            'width' => '15%',
        ),
        'assigned_user_name' => array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'assigned_user_id',
            'target_module' => 'Employees',
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