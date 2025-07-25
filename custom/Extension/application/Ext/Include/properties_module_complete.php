<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Register the module
$beanList['Properties'] = 'Properties';
$beanFiles['Properties'] = 'modules/Properties/Properties.php';

// Add to module list
$moduleList[] = 'Properties';

// Add module strings
$app_strings['moduleList']['Properties'] = 'Properties';
$app_strings['moduleListSingular']['Properties'] = 'Property';

// Make sure it's not in the invisible list
if (isset($modInvisList) && is_array($modInvisList)) {
    $key = array_search('Properties', $modInvisList);
    if ($key !== false) {
        unset($modInvisList[$key]);
    }
}

// Tab structure is now handled in custom/include/tabConfig.php

// Ensure it's available for tab selection
$modules_exempt_from_availability_check['Properties'] = 'Properties';