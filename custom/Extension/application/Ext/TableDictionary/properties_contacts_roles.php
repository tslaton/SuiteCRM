<?php
/**
 * Include custom relationship metadata for properties_contacts_roles
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include custom relationship metadata files
if (file_exists('custom/metadata/properties_contacts_rolesMetaData.php')) {
    include('custom/metadata/properties_contacts_rolesMetaData.php');
}