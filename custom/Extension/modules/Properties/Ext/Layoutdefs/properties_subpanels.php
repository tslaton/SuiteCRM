<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Related Contacts subpanel
$layout_defs['Properties']['subpanel_setup']['contacts_roles'] = array(
    'order' => 100,
    'module' => 'Contacts',
    'subpanel_name' => 'default',
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

// Related Transactions (Opportunities) subpanel
$layout_defs['Properties']['subpanel_setup']['opportunities'] = array(
    'order' => 200,
    'module' => 'Opportunities',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_TRANSACTIONS_SUBPANEL_TITLE',
    'get_subpanel_data' => 'opportunities',
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

// Documents & CMAs subpanel
$layout_defs['Properties']['subpanel_setup']['documents'] = array(
    'order' => 300,
    'module' => 'Documents',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'document_name',
    'title_key' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
    'get_subpanel_data' => 'documents',
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

// Remove old subpanels
unset($layout_defs['Properties']['subpanel_setup']['contacts']);
unset($layout_defs['Properties']['subpanel_setup']['transactions']);