<?php
/**
 * Mock MLS Module Vardefs
 * This file loads the vardefs from the Extension framework
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Load vardefs from Extension framework
if (file_exists('custom/Extension/modules/MockMLS/Ext/Vardefs/mock_mls_vardefs.php')) {
    require_once('custom/Extension/modules/MockMLS/Ext/Vardefs/mock_mls_vardefs.php');
}