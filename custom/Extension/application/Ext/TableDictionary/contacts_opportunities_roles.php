<?php
/**
 * Include custom relationship metadata for contacts_transactions_roles
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include custom relationship metadata files
if (file_exists('custom/metadata/contacts_opportunities_rolesMetaData.php')) {
    include('custom/metadata/contacts_opportunities_rolesMetaData.php');
}