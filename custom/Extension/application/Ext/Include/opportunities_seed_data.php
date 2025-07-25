<?php
/**
 * Seed data generator for Opportunities/Transactions module
 * Creates dummy real estate transactions for testing
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Generate dummy opportunities/transactions
 */
if (!function_exists('createDummyOpportunities')) {
    function createDummyOpportunities($count = 20) {
    global $db, $current_user, $app_list_strings;
    
    require_once('modules/Opportunities/Opportunity.php');
    require_once('modules/Accounts/Account.php');
    
    // Sample property addresses
    $properties = array(
        array('name' => '123 Main St, Anytown', 'price' => 350000),
        array('name' => '456 Oak Ave, Downtown', 'price' => 425000),
        array('name' => '789 Elm Dr, Suburbs', 'price' => 275000),
        array('name' => '321 Park Blvd, Uptown', 'price' => 550000),
        array('name' => '654 River Rd, Waterfront', 'price' => 750000),
        array('name' => '987 Hill St, Heights', 'price' => 325000),
        array('name' => '147 Lake View, Estates', 'price' => 875000),
        array('name' => '258 Forest Ln, Woods', 'price' => 295000),
        array('name' => '369 Beach Dr, Coastal', 'price' => 995000),
        array('name' => '741 Mountain Rd, Hills', 'price' => 425000),
    );
    
    // Sample client names
    $clients = array(
        'Johnson Family Trust',
        'Smith Investment Group',
        'Williams Properties LLC',
        'Brown Real Estate Holdings',
        'Davis Development Corp',
        'Miller & Associates',
        'Wilson Family Estate',
        'Moore Investment Partners',
        'Taylor Property Group',
        'Anderson Realty Trust',
    );
    
    // Get sales stages
    $stages = array_keys($app_list_strings['sales_stage_dom']);
    $stages = array_filter($stages); // Remove empty values
    
    // Create or get sample accounts
    $accountIds = array();
    foreach ($clients as $clientName) {
        // Check if account exists
        $query = "SELECT id FROM accounts WHERE name = '{$clientName}' AND deleted = 0 LIMIT 1";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        
        if ($row) {
            $accountIds[] = $row['id'];
        } else {
            // Create new account
            $account = new Account();
            $account->name = $clientName;
            $account->account_type = 'Customer';
            $account->industry = 'Real Estate';
            $account->assigned_user_id = $current_user->id;
            $account->save();
            $accountIds[] = $account->id;
        }
    }
    
    // Create opportunities
    $created = 0;
    for ($i = 0; $i < $count; $i++) {
        $opportunity = new Opportunity();
        
        // Random property
        $property = $properties[array_rand($properties)];
        
        // Random account
        $accountId = $accountIds[array_rand($accountIds)];
        
        // Random stage
        $stage = $stages[array_rand($stages)];
        
        // Set opportunity fields
        $opportunity->name = $property['name'];
        $opportunity->account_id = $accountId;
        $opportunity->sales_stage = $stage;
        $opportunity->amount = $property['price'];
        $opportunity->opportunity_type = array_rand(array_flip(array(
            'Buyer Representation',
            'Seller Representation',
            'Dual Agency',
        )));
        
        // Set probability based on stage
        if (isset($app_list_strings['sales_probability_dom'][$stage])) {
            $opportunity->probability = $app_list_strings['sales_probability_dom'][$stage];
        }
        
        // Random close date (within next 90 days)
        $daysAhead = rand(7, 90);
        $opportunity->date_closed = date('Y-m-d', strtotime("+{$daysAhead} days"));
        
        // Random commission (2-6% of sale price)
        $commissionRate = rand(20, 60) / 1000; // 2.0% to 6.0%
        $opportunity->commission_c = $property['price'] * $commissionRate;
        
        // Additional fields
        $opportunity->lead_source = array_rand(array_flip(array(
            'Website',
            'Referral',
            'Walk-in',
            'Advertisement',
            'Agent Network',
        )));
        
        $opportunity->next_step = array_rand(array_flip(array(
            'Schedule property viewing',
            'Prepare offer documents',
            'Negotiate terms',
            'Schedule inspection',
            'Review appraisal',
            'Finalize paperwork',
            'Coordinate closing',
        )));
        
        $opportunity->description = "Transaction for {$property['name']}. ";
        $opportunity->description .= "Stage: {$stage}. ";
        $opportunity->description .= "Expected commission: $" . number_format($opportunity->commission_c, 2);
        
        $opportunity->assigned_user_id = $current_user->id;
        
        $opportunity->save();
        $created++;
    }
    
    return $created;
    }
}

/**
 * Admin action to generate dummy data
 */
if (isset($_REQUEST['module']) && $_REQUEST['module'] == 'Administration' 
    && isset($_REQUEST['action']) && $_REQUEST['action'] == 'generateOpportunitiesData') {
    
    global $current_user;
    
    // Check admin access
    if (!is_admin($current_user)) {
        sugar_die('Unauthorized access to administration.');
    }
    
    $count = isset($_REQUEST['count']) ? intval($_REQUEST['count']) : 20;
    $created = createDummyOpportunities($count);
    
    echo "<h2>Dummy Data Generation Complete</h2>";
    echo "<p>Created {$created} dummy transactions.</p>";
    echo '<p><a href="index.php?module=Opportunities&action=kanban">View in Pipeline</a></p>';
    echo '<p><a href="index.php?module=Administration&action=index">Back to Admin</a></p>';
    sugar_die();
}

// Add menu item to Admin panel
if (!defined('OPPORTUNITIES_SEED_DATA_MENU_ADDED')) {
    define('OPPORTUNITIES_SEED_DATA_MENU_ADDED', true);
    
    if (!isset($GLOBALS['admin_group_header'])) {
        $GLOBALS['admin_group_header'] = array();
    }
    
    $GLOBALS['admin_group_header'][] = array(
        'Developer Tools',
        '',
        false,
        array(
            'Generate Transaction Data' => array(
                'link_url' => 'index.php?module=Administration&action=generateOpportunitiesData&count=20',
                'label' => 'Generate Transaction Data',
                'icon' => 'icon_Opportunities_32',
                'description' => 'Generate dummy real estate transactions for testing',
            ),
        ),
        'other'
    );
}