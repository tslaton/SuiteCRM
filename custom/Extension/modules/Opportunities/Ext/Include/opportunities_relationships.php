<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include opportunities relationship metadata when in Opportunities module
if (file_exists('custom/metadata/contacts_opportunities_rolesMetaData.php')) {
    require_once('custom/metadata/contacts_opportunities_rolesMetaData.php');
}