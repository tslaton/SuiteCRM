<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $layout_defs;

// Property subpanel
$layout_defs['Opportunities']['subpanel_setup']['property'] = array(
    'order' => 50,
    'module' => 'Properties',
    'subpanel_name' => 'ForTransactionSingle',
    'title_key' => 'LBL_PROPERTY_SUBPANEL_TITLE',
    'get_subpanel_data' => 'property',
);

// Contacts subpanel with roles
$layout_defs['Opportunities']['subpanel_setup']['contacts_roles'] = array(
    'order' => 100,
    'module' => 'Contacts',
    'subpanel_name' => 'ForTransactionsWithRoles',
    'sort_order' => 'asc',
    'sort_by' => 'last_name',
    'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
    'get_subpanel_data' => 'contacts_roles',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);