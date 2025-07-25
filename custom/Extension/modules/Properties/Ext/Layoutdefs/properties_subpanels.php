<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Update contacts subpanel to use the new relationship with roles
$layout_defs['Properties']['subpanel_setup']['contacts_roles'] = array(
    'order' => 100,
    'module' => 'Contacts',
    'subpanel_name' => 'ForPropertiesSimple',
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

// Update transactions subpanel
$layout_defs['Properties']['subpanel_setup']['transactions'] = array(
    'order' => 200,
    'module' => 'Opportunities',
    'subpanel_name' => 'ForProperties',
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_TRANSACTIONS_SUBPANEL_TITLE',
    'get_subpanel_data' => 'transactions',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
            'additional_form_fields' => array(
                'property_id' => '{$fields.id.value}',
                'property_name' => '{$fields.name.value}',
            ),
        ),
    ),
);

// Remove old subpanels
unset($layout_defs['Properties']['subpanel_setup']['contacts']);
unset($layout_defs['Properties']['subpanel_setup']['opportunities']);