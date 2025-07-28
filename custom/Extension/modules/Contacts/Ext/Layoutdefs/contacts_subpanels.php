<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $layout_defs;

// Properties subpanel with roles
$layout_defs['Contacts']['subpanel_setup']['properties_roles'] = array(
    'order' => 100,
    'module' => 'Properties',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'name',
    'title_key' => 'LBL_PROPERTIES_SUBPANEL_TITLE',
    'get_subpanel_data' => 'properties_roles',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);

// Transactions subpanel with roles
$layout_defs['Contacts']['subpanel_setup']['transactions_roles'] = array(
    'order' => 200,
    'module' => 'Opportunities',
    'subpanel_name' => 'ForContactsWithRoles',
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_TRANSACTIONS_SUBPANEL_TITLE',
    'get_subpanel_data' => 'transactions_roles',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);