<?php
/**
 * Regenerate Demo Data Script
 * Truncates existing data and creates fresh Properties and Transactions
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once('include/entryPoint.php');
require_once('modules/MockMLS/MockMLS.php');

global $current_user, $db;

// Check if we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

// Set admin user
$current_user = BeanFactory::getBean('Users', '1');

echo "\n=== REGENERATING DEMO DATA ===\n";
echo "Truncating existing data...\n";

// Truncate tables
$tables_to_truncate = [
    'properties',
    'opportunities', 
    'contacts',
    'opportunities_contacts',
    'properties_contacts' // If this relationship table exists
];

foreach ($tables_to_truncate as $table) {
    try {
        $db->query("TRUNCATE TABLE $table");
        echo "- Truncated $table\n";
    } catch (Exception $e) {
        echo "- Could not truncate $table (may not exist)\n";
    }
}

echo "\nGenerating fresh demo data...\n";

// Get Mock MLS properties to base our data on
$query = "SELECT * FROM mock_mls_data WHERE deleted = 0 ORDER BY RAND() LIMIT 35";
$result = $db->query($query);

$properties_created = 0;
$transactions_created = 0;
$contacts_created = 0;

// Arrays for random data generation
$agent_first_names = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Mary'];
$agent_last_names = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];

$buyer_first_names = ['Christopher', 'Jennifer', 'Matthew', 'Amanda', 'Daniel', 'Jessica', 'Andrew', 'Ashley', 'Joshua', 'Brittany'];
$seller_first_names = ['William', 'Patricia', 'Richard', 'Linda', 'Joseph', 'Barbara', 'Thomas', 'Elizabeth', 'Charles', 'Maria'];
$client_last_names = ['Anderson', 'Taylor', 'Thomas', 'Hernandez', 'Moore', 'Martin', 'Jackson', 'Thompson', 'White', 'Lopez'];

$agencies = ['Premier Real Estate', 'Century Properties', 'Realty One Group', 'Coldwell Banker', 'RE/MAX Excellence'];

// Fixed stage distribution with exact counts
$stage_assignments = [
    // First 12 transactions get specific stages
    'Inquiry', 'Inquiry', 'Inquiry',
    'Showing', 'Showing', 'Showing',
    'Offer Made', 'Offer Made',
    'Under Contract',
    'Inspection/Appraisal', 'Inspection/Appraisal',
    'Clear to Close',
    // Rest will be Closed
];
$stage_index = 0;

// Transaction type counter for 50-50 split
$transaction_type_counter = 0;

while ($mls_property = $db->fetchByAssoc($result)) {
    // Create a Property record based on MLS data
    $property = BeanFactory::newBean('Properties');
    
    // Map MLS data to Properties fields
    $property->name = $mls_property['address'];
    $property->mls_id = $mls_property['property_id'];
    $property->street_address = $mls_property['address'];
    $property->city = $mls_property['city'];
    $property->state = $mls_property['state'];
    $property->zip_code = $mls_property['zip'];
    
    // Map property type
    $type_mapping = [
        'single_family' => 'residential',
        'condo' => 'condo',
        'townhouse' => 'townhouse',
        'multi_family' => 'multi_unit',
        'commercial' => 'commercial',
        'land' => 'land'
    ];
    $property->property_type = isset($type_mapping[$mls_property['property_type']]) ? 
        $type_mapping[$mls_property['property_type']] : 'residential';
    
    $property->bedrooms = $mls_property['bedrooms'];
    $property->bathrooms = $mls_property['bathrooms'];
    $property->square_footage = $mls_property['square_footage'];
    $property->lot_size = $mls_property['lot_size'];
    $property->year_built = $mls_property['year_built'];
    
    // Determine if this will have a transaction and what stage
    $will_have_transaction = $stage_index < count($stage_assignments) || !empty($mls_property['sold_price']) || rand(0, 100) < 50;
    
    if ($will_have_transaction && $stage_index < count($stage_assignments)) {
        $assigned_stage = $stage_assignments[$stage_index];
        $stage_index++;
        
        // Set property status based on transaction stage
        if (in_array($assigned_stage, ['Under Contract', 'Inspection/Appraisal', 'Clear to Close'])) {
            $property->status = 'pending';
        } elseif (in_array($assigned_stage, ['Inquiry', 'Showing', 'Offer Made'])) {
            $property->status = 'active';
        } else {
            $property->status = 'sold';
        }
    } else {
        // Set status based on MLS data
        if (!empty($mls_property['sold_price'])) {
            $property->status = 'sold';
            $assigned_stage = 'Closed';
        } else {
            $property->status = rand(0, 100) < 70 ? 'active' : 'pending';
            $assigned_stage = null;
        }
    }
    
    // Set price
    $property->price = !empty($mls_property['sold_price']) ? $mls_property['sold_price'] : $mls_property['list_price'];
    $property->listing_date = $mls_property['listing_date'];
    $property->assigned_user_id = $current_user->id;
    $property->description = generatePropertyDescription($property);
    
    $property->save();
    $properties_created++;
    
    // Determine transaction type (50-50 split between Purchase and Sell)
    $is_purchase = ($transaction_type_counter % 2 == 0);
    $transaction_type_counter++;
    
    // Create listing agent
    $agent = BeanFactory::newBean('Contacts');
    $agent->first_name = $agent_first_names[array_rand($agent_first_names)];
    $agent->last_name = $agent_last_names[array_rand($agent_last_names)];
    $agent->contact_type_c = 'agent';
    $agent->account_name = $agencies[array_rand($agencies)];
    $agent->email1 = strtolower($agent->first_name . '.' . $agent->last_name . '@' . str_replace(' ', '', $agent->account_name) . '.com');
    $agent->phone_mobile = sprintf('(%03d) %03d-%04d', rand(200, 999), rand(200, 999), rand(1000, 9999));
    $agent->assigned_user_id = $current_user->id;
    $agent->save();
    $contacts_created++;
    
    // Link agent to property
    if ($property->load_relationship('contacts')) {
        $property->contacts->add($agent->id);
    }
    
    // Create transaction if property has an assigned stage or is sold
    if ($assigned_stage) {
        // Create client contact (buyer or seller)
        $client = BeanFactory::newBean('Contacts');
        
        if ($is_purchase) {
            $client->first_name = $buyer_first_names[array_rand($buyer_first_names)];
            $client->contact_type_c = 'buyer';
            $transaction_name = 'Purchase - ' . $property->street_address;
        } else {
            $client->first_name = $seller_first_names[array_rand($seller_first_names)];
            $client->contact_type_c = 'seller';
            $transaction_name = 'Sell - ' . $property->street_address;
        }
        
        $client->last_name = $client_last_names[array_rand($client_last_names)];
        $client->email1 = strtolower($client->first_name . '.' . $client->last_name . '@email.com');
        $client->phone_mobile = sprintf('(%03d) %03d-%04d', rand(200, 999), rand(200, 999), rand(1000, 9999));
        $client->assigned_user_id = $current_user->id;
        $client->save();
        $contacts_created++;
        
        // Link client to property
        if ($property->load_relationship('contacts')) {
            $property->contacts->add($client->id);
        }
        
        // Create transaction (Opportunity)
        $transaction = BeanFactory::newBean('Opportunities');
        $transaction->name = $transaction_name;
        $transaction->amount = $property->price;
        $transaction->sales_stage = $assigned_stage;
        
        // Set probability based on stage
        $stage_probabilities = [
            'Inquiry' => 10,
            'Showing' => 20,
            'Offer Made' => 40,
            'Under Contract' => 60,
            'Inspection/Appraisal' => 75,
            'Clear to Close' => 90,
            'Closed' => 100,
        ];
        $transaction->probability = $stage_probabilities[$assigned_stage] ?? 50;
        
        // Set dates that make sense for the pipeline stage
        switch ($assigned_stage) {
            case 'Inquiry':
                $transaction->date_entered = date('Y-m-d H:i:s', strtotime('-' . rand(1, 7) . ' days'));
                $transaction->date_closed = date('Y-m-d', strtotime('+' . rand(60, 90) . ' days'));
                break;
            case 'Showing':
                $transaction->date_entered = date('Y-m-d H:i:s', strtotime('-' . rand(5, 14) . ' days'));
                $transaction->date_closed = date('Y-m-d', strtotime('+' . rand(45, 75) . ' days'));
                break;
            case 'Offer Made':
                $transaction->date_entered = date('Y-m-d H:i:s', strtotime('-' . rand(10, 21) . ' days'));
                $transaction->date_closed = date('Y-m-d', strtotime('+' . rand(30, 60) . ' days'));
                break;
            case 'Under Contract':
                $transaction->date_entered = date('Y-m-d H:i:s', strtotime('-' . rand(15, 30) . ' days'));
                $transaction->date_closed = date('Y-m-d', strtotime('+' . rand(20, 45) . ' days'));
                break;
            case 'Inspection/Appraisal':
                $transaction->date_entered = date('Y-m-d H:i:s', strtotime('-' . rand(20, 35) . ' days'));
                $transaction->date_closed = date('Y-m-d', strtotime('+' . rand(10, 30) . ' days'));
                break;
            case 'Clear to Close':
                $transaction->date_entered = date('Y-m-d H:i:s', strtotime('-' . rand(25, 40) . ' days'));
                $transaction->date_closed = date('Y-m-d', strtotime('+' . rand(3, 10) . ' days'));
                break;
            case 'Closed':
                $close_days_ago = rand(1, 60);
                $transaction->date_entered = date('Y-m-d H:i:s', strtotime('-' . ($close_days_ago + rand(30, 60)) . ' days'));
                $transaction->date_closed = date('Y-m-d', strtotime('-' . $close_days_ago . ' days'));
                break;
        }
        
        $transaction->lead_source = getRandomLeadSource();
        $transaction->opportunity_type = $is_purchase ? 'Buyer Representation' : 'Seller Representation';
        $transaction->assigned_user_id = $current_user->id;
        
        // Calculate commission (2.5%) for closed deals
        if ($transaction->sales_stage == 'Closed') {
            $transaction->commission_c = $transaction->amount * 0.025;
        }
        
        $transaction->save();
        $transactions_created++;
        
        // Link transaction to property
        if ($property->load_relationship('opportunities')) {
            $property->opportunities->add($transaction->id);
        }
        
        // Link transaction to contacts
        $transaction->load_relationship('contacts');
        $transaction->contacts->add($client->id);
        $transaction->contacts->add($agent->id);
    }
    
    if ($properties_created % 10 == 0) {
        echo "Created $properties_created properties, $transactions_created transactions, $contacts_created contacts...\n";
    }
}

echo "\n=== DEMO DATA GENERATION COMPLETE! ===\n";
echo "Created:\n";
echo "- $properties_created Properties\n";
echo "- $transactions_created Transactions\n";
echo "- $contacts_created Contacts\n";
echo "\nTransaction stage distribution:\n";

// Show stage distribution
$stage_query = "SELECT sales_stage, COUNT(*) as count FROM opportunities WHERE deleted = 0 GROUP BY sales_stage ORDER BY 
    CASE sales_stage 
        WHEN 'Inquiry' THEN 1
        WHEN 'Showing' THEN 2
        WHEN 'Offer Made' THEN 3
        WHEN 'Under Contract' THEN 4
        WHEN 'Inspection/Appraisal' THEN 5
        WHEN 'Clear to Close' THEN 6
        WHEN 'Closed' THEN 7
        ELSE 8
    END";
$stage_result = $db->query($stage_query);
while ($row = $db->fetchByAssoc($stage_result)) {
    echo "- {$row['sales_stage']}: {$row['count']}\n";
}

// Show transaction type distribution
echo "\nTransaction type distribution:\n";
$type_query = "SELECT opportunity_type, COUNT(*) as count FROM opportunities WHERE deleted = 0 GROUP BY opportunity_type";
$type_result = $db->query($type_query);
while ($row = $db->fetchByAssoc($type_result)) {
    echo "- {$row['opportunity_type']}: {$row['count']}\n";
}

/**
 * Get random lead source
 */
function getRandomLeadSource() {
    $sources = ['Website', 'Referral', 'Walk-in', 'Phone Inquiry', 'Email Inquiry', 'Social Media', 'Open House', 'Agent Referral'];
    return $sources[array_rand($sources)];
}

/**
 * Generate property description based on property data
 */
function generatePropertyDescription($property) {
    $descriptions = [
        'residential' => [
            'Beautiful %d bedroom, %s bathroom home in %s',
            'Stunning %d bed/%s bath residence located in the heart of %s',
            'Spacious %d bedroom home with %s bathrooms in desirable %s',
            'Charming %d bed, %s bath property in prime %s location'
        ],
        'condo' => [
            'Modern %d bedroom, %s bathroom condo in %s',
            'Luxury %d bed/%s bath condominium in downtown %s',
            'Contemporary %d bedroom condo with %s baths in %s',
            'Elegant %d bed, %s bath unit in prestigious %s'
        ],
        'townhouse' => [
            'Stylish %d bedroom, %s bathroom townhouse in %s',
            'Modern %d bed/%s bath townhome in %s',
            'Beautiful %d bedroom townhouse with %s bathrooms in %s',
            'Spacious %d bed, %s bath townhome in desirable %s'
        ],
        'commercial' => [
            'Prime commercial property with %d rooms and %s bathrooms in %s',
            'Excellent commercial space featuring %d areas and %s baths in %s',
            'Strategic commercial location with %d spaces and %s bathrooms in %s',
            'Premium commercial property offering %d rooms and %s baths in %s'
        ]
    ];
    
    $type = $property->property_type ?: 'residential';
    if (!isset($descriptions[$type])) {
        $type = 'residential';
    }
    
    $template = $descriptions[$type][array_rand($descriptions[$type])];
    $description = sprintf(
        $template,
        $property->bedrooms,
        $property->bathrooms,
        $property->city
    );
    
    // Add features
    $features = [];
    if ($property->square_footage > 3000) $features[] = 'spacious ' . number_format($property->square_footage) . ' sq ft';
    if ($property->year_built > 2015) $features[] = 'recently built';
    if ($property->lot_size > 0.5) $features[] = 'large ' . $property->lot_size . ' acre lot';
    
    if (!empty($features)) {
        $description .= '. Features ' . implode(', ', $features) . '.';
    }
    
    return $description;
}