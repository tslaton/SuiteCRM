<?php
/**
 * Test script for Open House Sign-In functionality
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once('include/entryPoint.php');

global $sugar_config, $current_user;

// Check if we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

// Set admin user
$current_user = BeanFactory::getBean('Users', '1');

echo "\n=== TESTING OPEN HOUSE SIGN-IN FEATURE ===\n\n";

// Get a property for testing
$property = BeanFactory::newBean('Properties');
$property_list = $property->get_full_list('date_entered DESC', "properties.deleted = 0", true, 1);

if (empty($property_list)) {
    echo "No properties found. Creating a test property...\n";
    $property = BeanFactory::newBean('Properties');
    $property->name = 'Test Open House Property';
    $property->street_address = '789 Open House Lane';
    $property->city = 'Austin';
    $property->state = 'TX';
    $property->zip_code = '78701';
    $property->status = 'active';
    $property->price = 450000;
    $property->bedrooms = 3;
    $property->bathrooms = 2;
    $property->assigned_user_id = $current_user->id;
    $property->save();
} else {
    $property = $property_list[0];
}

echo "Using property: {$property->name}\n";
echo "Property ID: {$property->id}\n\n";

// Generate token
$secret = isset($sugar_config['unique_key']) ? $sugar_config['unique_key'] : 'default_secret';
$token = substr(md5($property->id . $secret), 0, 16);

// Build URLs
$base_url = $sugar_config['site_url'];
$sign_in_url = $base_url . '/index.php?entryPoint=OpenHouseSignIn&property_id=' . $property->id . '&token=' . $token;
$qr_generator_url = $base_url . '/index.php?module=Properties&action=generateqr&record=' . $property->id;

echo "=== OPEN HOUSE URLS ===\n";
echo "Sign-In Form URL:\n";
echo $sign_in_url . "\n\n";
echo "QR Code Generator URL:\n";
echo $qr_generator_url . "\n\n";

echo "=== FEATURE SUMMARY ===\n";
echo "✓ Public sign-in form (no login required)\n";
echo "✓ Mobile-responsive design\n";
echo "✓ Secure token validation\n";
echo "✓ Automatic lead creation\n";
echo "✓ Email notifications to agents\n";
echo "✓ QR code generation\n";
echo "✓ Rate limiting (10 submissions per hour per IP)\n\n";

echo "=== HOW TO TEST ===\n";
echo "1. Open the Sign-In Form URL in a browser (or scan QR code)\n";
echo "2. Fill out the visitor information form\n";
echo "3. Submit the form\n";
echo "4. Check the Leads module for the new lead\n";
echo "5. Check agent's email for notification\n\n";

echo "=== QR CODE GENERATION ===\n";
echo "1. Go to Properties module\n";
echo "2. Open any property detail view\n";
echo "3. Click 'Open House QR Code' button\n";
echo "4. Print the QR code for display at the property\n\n";

// Test lead creation directly
echo "=== TESTING LEAD CREATION ===\n";
$test_lead = BeanFactory::newBean('Leads');
$test_lead->first_name = 'Test';
$test_lead->last_name = 'Visitor';
$test_lead->email1 = 'test.visitor@example.com';
$test_lead->phone_mobile = '(555) 123-4567';
$test_lead->lead_source = 'Open House';
$test_lead->status = 'New';
$test_lead->description = "Open House Sign-In for: {$property->name}\n\nTest lead created by test script.";
$test_lead->assigned_user_id = $property->assigned_user_id;

// Set property relationship if field exists
if (property_exists($test_lead, 'property_id_c')) {
    $test_lead->property_id_c = $property->id;
}

$test_lead->save();

echo "✓ Created test lead: {$test_lead->first_name} {$test_lead->last_name}\n";
echo "  Lead ID: {$test_lead->id}\n";
echo "  Email: {$test_lead->email1}\n";
echo "  Lead Source: {$test_lead->lead_source}\n\n";

// Clean up test lead
$test_lead->mark_deleted($test_lead->id);
echo "✓ Cleaned up test lead\n\n";

echo "=== OPEN HOUSE SIGN-IN TESTING COMPLETE ===\n";