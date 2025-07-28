<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['MyPropertiesDashlet'] = array(
    'module' => 'Properties',
    'title' => translate('LBL_MY_PROPERTIES', 'Properties'),
    'description' => 'A customizable view of My Properties',
    'icon' => 'icon_Properties_32.gif',
    'category' => 'Module Views'
);