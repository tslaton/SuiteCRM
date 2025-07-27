<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once('modules/Properties/PropertiesDemoData.php');

global $current_user;

// If running from CLI, set up admin user
if (php_sapi_name() == 'cli') {
    $current_user = BeanFactory::getBean('Users', '1');
}

// Check if user is admin
if (!empty($current_user) && !is_admin($current_user)) {
    sugar_die('Unauthorized access to administration.');
}

$demo_data = new PropertiesDemoData();
$count = $demo_data->create_demo_data();

echo json_encode(array(
    'success' => true,
    'message' => "Successfully created $count demo properties."
));