<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$layout_defs['RE_Properties'] = array(
    'subpanel_setup' => array(
        'contacts' => array(
            'order' => 10,
            'module' => 'Contacts',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'last_name, first_name',
            'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'contacts',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'accounts' => array(
            'order' => 20,
            'module' => 'Accounts',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'title_key' => 'LBL_ACCOUNTS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'accounts',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'opportunities' => array(
            'order' => 30,
            'module' => 'Opportunities',
            'subpanel_name' => 'default',
            'sort_order' => 'desc',
            'sort_by' => 'date_entered',
            'title_key' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
            'get_subpanel_data' => 'opportunities',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'activities' => array(
            'order' => 40,
            'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
            'type' => 'collection',
            'module' => 'Activities',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateTaskButton'),
                array('widget_class' => 'SubPanelTopScheduleMeetingButton'),
                array('widget_class' => 'SubPanelTopScheduleCallButton'),
                array('widget_class' => 'SubPanelTopComposeEmailButton'),
            ),
            'collection_list' => array(
                'meetings' => array(
                    'module' => 'Meetings',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'meetings',
                ),
                'tasks' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'tasks',
                ),
                'calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'calls',
                ),
            ),
        ),
        'history' => array(
            'order' => 50,
            'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
            'type' => 'collection',
            'module' => 'History',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateNoteButton'),
                array('widget_class' => 'SubPanelTopArchiveEmailButton'),
                array('widget_class' => 'SubPanelTopSummaryButton'),
            ),
            'collection_list' => array(
                'meetings' => array(
                    'module' => 'Meetings',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'meetings',
                ),
                'tasks' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'tasks',
                ),
                'calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'calls',
                ),
                'notes' => array(
                    'module' => 'Notes',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'notes',
                ),
                'emails' => array(
                    'module' => 'Emails',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'emails',
                ),
            ),
        ),
        'documents' => array(
            'order' => 60,
            'module' => 'Documents',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'document_name',
            'title_key' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'documents',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
    ),
);