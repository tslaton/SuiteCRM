<?php
/**
 * Standalone script to generate dummy real estate transactions for testing
 * 
 * Usage:
 * 1. Run from command line: php custom/scripts/generate_dummy_transactions.php
 * 2. Or access via browser: http://localhost:8080/custom/scripts/generate_dummy_transactions.php?count=20
 * 
 * This script creates dummy transactions with realistic real estate data
 */

// Allow both CLI and web execution
if (php_sapi_name() !== 'cli') {
    // Web access - bootstrap SuiteCRM
    if (!defined('sugarEntry')) define('sugarEntry', true);
    chdir('../..');
    require_once('include/entryPoint.php');
} else {
    // CLI access
    if (!defined('sugarEntry')) define('sugarEntry', true);
    require_once(dirname(__FILE__) . '/../../include/entryPoint.php');
}

global $current_user, $app_list_strings, $db;

// Set admin user for permissions
$current_user = new User();
$current_user->getSystemUser();

require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Contacts/Contact.php');

// Configuration
$count = isset($_REQUEST['count']) ? intval($_REQUEST['count']) : (isset($argv[1]) ? intval($argv[1]) : 20);

// Sample data arrays
$properties = array(
    array('address' => '123 Main Street', 'city' => 'Springfield', 'price' => 350000, 'type' => 'Single Family'),
    array('address' => '456 Oak Avenue', 'city' => 'Downtown', 'price' => 425000, 'type' => 'Condo'),
    array('address' => '789 Elm Drive', 'city' => 'Riverside', 'price' => 275000, 'type' => 'Townhouse'),
    array('address' => '321 Park Boulevard', 'city' => 'Uptown', 'price' => 550000, 'type' => 'Single Family'),
    array('address' => '654 River Road', 'city' => 'Waterfront', 'price' => 750000, 'type' => 'Luxury Home'),
    array('address' => '987 Hill Street', 'city' => 'Heights', 'price' => 325000, 'type' => 'Single Family'),
    array('address' => '147 Lake View Drive', 'city' => 'Estates', 'price' => 875000, 'type' => 'Luxury Home'),
    array('address' => '258 Forest Lane', 'city' => 'Woodlands', 'price' => 295000, 'type' => 'Single Family'),
    array('address' => '369 Beach Drive', 'city' => 'Coastal', 'price' => 995000, 'type' => 'Beach House'),
    array('address' => '741 Mountain Road', 'city' => 'Highland', 'price' => 425000, 'type' => 'Single Family'),
    array('address' => '852 Valley View', 'city' => 'Midtown', 'price' => 385000, 'type' => 'Condo'),
    array('address' => '963 Sunset Boulevard', 'city' => 'Westside', 'price' => 525000, 'type' => 'Single Family'),
);

$firstNames = array('John', 'Jane', 'Michael', 'Sarah', 'David', 'Emma', 'Robert', 'Lisa', 'James', 'Mary', 'William', 'Patricia');
$lastNames = array('Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Anderson', 'Taylor');

$companies = array(
    'Premier Properties LLC',
    'Golden Gate Realty',
    'Summit Real Estate Group',
    'Coastal Living Properties',
    'Urban Nest Realty',
    'Mountain View Properties',
    'Riverside Real Estate',
    'Lakeside Properties',
    'Downtown Realty Group',
    'Heritage Home Sales'
);

// Get sales stages
$salesStages = array_keys($app_list_strings['sales_stage_dom']);
$salesStages = array_filter($salesStages); // Remove empty values

// Transaction types
$transactionTypes = array('Buyer Representation', 'Seller Representation', 'Dual Agency');

// Lead sources
$leadSources = array('Website', 'Referral', 'Walk-in', 'Advertisement', 'Agent Network', 'Open House', 'Social Media');

// Next steps by stage
$nextStepsByStage = array(
    'Inquiry' => array('Schedule initial consultation', 'Send property information', 'Qualify buyer/seller'),
    'Showing' => array('Schedule property viewing', 'Arrange virtual tour', 'Prepare showing checklist'),
    'Offer Made' => array('Review offer terms', 'Negotiate price', 'Submit counter-offer'),
    'Under Contract' => array('Schedule inspection', 'Review contract contingencies', 'Coordinate with lender'),
    'Inspection/Appraisal' => array('Review inspection report', 'Negotiate repairs', 'Schedule appraisal'),
    'Clear to Close' => array('Final walkthrough', 'Review closing documents', 'Confirm closing date'),
    'Closed' => array('File paperwork', 'Send thank you note', 'Request testimonial'),
);

echo "Starting generation of $count dummy transactions...\n\n";

// Create accounts (buyers/sellers)
$accountIds = array();
for ($i = 0; $i < min(10, $count); $i++) {
    $account = new Account();
    
    if (rand(0, 1) == 0) {
        // Individual account
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $account->name = "$firstName $lastName";
        $account->account_type = 'Customer';
    } else {
        // Company account
        $account->name = $companies[array_rand($companies)];
        $account->account_type = 'Customer';
    }
    
    $account->industry = 'Real Estate';
    $account->assigned_user_id = $current_user->id;
    $account->save();
    $accountIds[] = $account->id;
    
    echo "Created account: {$account->name}\n";
}

echo "\n";

// Create opportunities/transactions
$createdCount = 0;
for ($i = 0; $i < $count; $i++) {
    $opportunity = new Opportunity();
    
    // Select random property
    $property = $properties[array_rand($properties)];
    
    // Create transaction name
    $opportunity->name = $property['address'] . ', ' . $property['city'] . ' - ' . $property['type'];
    
    // Assign to random account
    $opportunity->account_id = $accountIds[array_rand($accountIds)];
    
    // Set random stage
    $stage = $salesStages[array_rand($salesStages)];
    $opportunity->sales_stage = $stage;
    
    // Set amount
    $opportunity->amount = $property['price'];
    $opportunity->currency_id = '-99'; // Default currency
    
    // Set transaction type
    $opportunity->opportunity_type = $transactionTypes[array_rand($transactionTypes)];
    
    // Set probability based on stage
    if (isset($app_list_strings['sales_probability_dom'][$stage])) {
        $opportunity->probability = $app_list_strings['sales_probability_dom'][$stage];
    }
    
    // Set close date based on stage
    $daysOffset = 0;
    switch($stage) {
        case 'Inquiry':
            $daysOffset = rand(60, 90);
            break;
        case 'Showing':
            $daysOffset = rand(45, 75);
            break;
        case 'Offer Made':
            $daysOffset = rand(30, 60);
            break;
        case 'Under Contract':
            $daysOffset = rand(20, 45);
            break;
        case 'Inspection/Appraisal':
            $daysOffset = rand(10, 30);
            break;
        case 'Clear to Close':
            $daysOffset = rand(3, 10);
            break;
        case 'Closed':
            $daysOffset = rand(-30, -1); // Closed in the past
            break;
    }
    $opportunity->date_closed = date('Y-m-d', strtotime("+{$daysOffset} days"));
    
    // Calculate commission (2.5% to 3.5% typically)
    $commissionRate = rand(25, 35) / 1000;
    $opportunity->commission_c = $property['price'] * $commissionRate;
    
    // Set lead source
    $opportunity->lead_source = $leadSources[array_rand($leadSources)];
    
    // Set next step based on stage
    if (isset($nextStepsByStage[$stage])) {
        $opportunity->next_step = $nextStepsByStage[$stage][array_rand($nextStepsByStage[$stage])];
    }
    
    // Add description
    $opportunity->description = "Property Type: {$property['type']}\n";
    $opportunity->description .= "Location: {$property['address']}, {$property['city']}\n";
    $opportunity->description .= "List Price: $" . number_format($property['price'], 0) . "\n";
    $opportunity->description .= "Expected Commission: $" . number_format($opportunity->commission_c, 2) . " (" . ($commissionRate * 100) . "%)\n";
    $opportunity->description .= "Transaction Type: {$opportunity->opportunity_type}\n";
    
    $opportunity->assigned_user_id = $current_user->id;
    
    // Save the opportunity
    $opportunity->save();
    $createdCount++;
    
    echo "Created transaction #{$createdCount}: {$opportunity->name} (Stage: {$stage})\n";
}

echo "\n";
echo "========================================\n";
echo "Successfully created $createdCount dummy transactions!\n";
echo "========================================\n\n";

if (php_sapi_name() !== 'cli') {
    // Web output
    echo '<br><br>';
    echo '<h3>Next Steps:</h3>';
    echo '<ul>';
    echo '<li><a href="index.php?module=Opportunities&action=index">View Transactions List</a></li>';
    echo '<li><a href="index.php?module=Opportunities&action=kanban">View Pipeline (Kanban)</a></li>';
    echo '<li><a href="index.php?module=Administration&action=index">Back to Admin</a></li>';
    echo '</ul>';
} else {
    // CLI output
    echo "View the transactions at:\n";
    echo "- List View: index.php?module=Opportunities&action=index\n";
    echo "- Pipeline View: index.php?module=Opportunities&action=kanban\n";
}