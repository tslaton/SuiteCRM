<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$layout_defs['Properties'] = array(
    'subpanel_setup' => array(
        'contacts' => array(
            'order' => 100,
            'module' => 'Contacts',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'contacts',
            'top_buttons' => array(
                0 => array(
                    'widget_class' => 'SubPanelTopButtonQuickCreate',
                ),
                1 => array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect',
                ),
            ),
        ),
        'opportunities' => array(
            'order' => 200,
            'module' => 'Opportunities',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_TRANSACTIONS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'opportunities',
            'top_buttons' => array(
                0 => array(
                    'widget_class' => 'SubPanelTopButtonQuickCreate',
                ),
                1 => array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect',
                ),
            ),
        ),
        'activities' => array(
            'order' => 300,
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
        ),
        'history' => array(
            'order' => 400,
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
        ),
    ),
);