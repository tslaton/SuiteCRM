<?php
/**
 * Properties subpanel for Opportunities
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$layout_defs["Opportunities"]["subpanel_setup"]['properties'] = array(
    'order' => 100,
    'module' => 'Properties',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_PROPERTIES_SUBPANEL_TITLE',
    'get_subpanel_data' => 'properties',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);