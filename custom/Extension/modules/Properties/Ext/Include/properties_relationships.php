<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include properties relationship metadata when in Properties module
if (file_exists('custom/metadata/properties_contacts_rolesMetaData.php')) {
    require_once('custom/metadata/properties_contacts_rolesMetaData.php');
}

// Include documents-properties relationship metadata
if (file_exists('custom/metadata/documents_propertiesMetaData.php')) {
    require_once('custom/metadata/documents_propertiesMetaData.php');
}