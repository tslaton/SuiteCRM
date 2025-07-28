<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include documents-properties relationship metadata
if (file_exists('custom/metadata/documents_propertiesMetaData.php')) {
    require_once('custom/metadata/documents_propertiesMetaData.php');
}