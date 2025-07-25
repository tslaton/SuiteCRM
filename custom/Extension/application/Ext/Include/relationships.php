<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include custom relationship metadata files
if (file_exists('custom/metadata/properties_contacts_rolesMetaData.php')) {
    require_once('custom/metadata/properties_contacts_rolesMetaData.php');
}

if (file_exists('custom/metadata/contacts_transactions_rolesMetaData.php')) {
    require_once('custom/metadata/contacts_transactions_rolesMetaData.php');
}