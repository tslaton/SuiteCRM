<?php
/**
 * Include custom relationship metadata for documents_properties
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include custom relationship metadata files
if (file_exists('custom/metadata/documents_propertiesMetaData.php')) {
    include('custom/metadata/documents_propertiesMetaData.php');
}