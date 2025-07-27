<?php
/**
 * Add Real Estate Settings to Administration Panel
 */

$admin_option_defs = array();
$admin_option_defs['Administration']['real_estate_settings'] = array(
    'Administration',
    'LBL_REAL_ESTATE_SETTINGS_LINK_NAME',
    'LBL_REAL_ESTATE_SETTINGS_LINK_DESCRIPTION',
    './index.php?module=Administration&action=real_estate_settings',
);

$admin_group_header[] = array(
    'LBL_REAL_ESTATE_SETTINGS_GROUP_TITLE',
    '',
    false,
    $admin_option_defs,
    'LBL_REAL_ESTATE_SETTINGS_GROUP_DESC'
);