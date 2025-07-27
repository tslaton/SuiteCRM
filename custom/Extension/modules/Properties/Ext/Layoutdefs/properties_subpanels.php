<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Related Contacts subpanel (1st position)
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

// Related Transactions (Opportunities) subpanel (2nd position)
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

// Documents & CMAs subpanel (3rd position)
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

// Activities subpanel (4th position)
$layout_defs['Properties']['subpanel_setup']['activities'] = array(
    'order' => 400,
    'sort_order' => 'desc',
    'sort_by' => 'date_start',
    'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
    'type' => 'collection',
    'subpanel_name' => 'activities',
    'module' => 'Activities',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
            'view' => 'Activities',
        ),
    ),
    'collection_list' => array(
        'meetings' => array(
            'module' => 'Meetings',
        ),
        'tasks' => array(
            'module' => 'Tasks',
        ),
        'calls' => array(
            'module' => 'Calls',
        ),
        'notes' => array(
            'module' => 'Notes',
        ),
    ),
);

// History subpanel (5th position)
$layout_defs['Properties']['subpanel_setup']['history'] = array(
    'order' => 500,
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
    'type' => 'collection',
    'subpanel_name' => 'history',
    'module' => 'History',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopCreateButton',
            'view' => 'History',
        ),
    ),
    'collection_list' => array(
        'meetings' => array(
            'module' => 'Meetings',
        ),
        'tasks' => array(
            'module' => 'Tasks',
        ),
        'calls' => array(
            'module' => 'Calls',
        ),
        'notes' => array(
            'module' => 'Notes',
        ),
        'emails' => array(
            'module' => 'Emails',
        ),
    ),
);

// Remove old subpanels
unset($layout_defs['Properties']['subpanel_setup']['contacts']);
unset($layout_defs['Properties']['subpanel_setup']['transactions']);