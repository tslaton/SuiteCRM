<?php
/**
 * Script to populate Mock MLS table with sample data
 * Generates 100+ realistic property records for CMA testing
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once('include/entryPoint.php');
require_once('modules/MockMLS/MockMLS.php');

// Check if we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

echo "Populating Mock MLS data...\n";

// Sample data arrays for realistic property generation
$streets = [
    'Main', 'Oak', 'Maple', 'Cedar', 'Pine', 'Elm', 'Washington', 'Park', 
    'Lake', 'Hill', 'Forest', 'River', 'Spring', 'Sunset', 'Valley', 'Meadow',
    'Birch', 'Willow', 'Cherry', 'Walnut', 'Chestnut', 'Hickory', 'Sycamore'
];

$street_types = ['St', 'Ave', 'Rd', 'Blvd', 'Ln', 'Dr', 'Way', 'Ct', 'Pl', 'Cir'];

$cities = [
    ['name' => 'Austin', 'state' => 'TX', 'zips' => ['78701', '78702', '78703', '78704', '78705']],
    ['name' => 'Dallas', 'state' => 'TX', 'zips' => ['75201', '75202', '75203', '75204', '75205']],
    ['name' => 'Houston', 'state' => 'TX', 'zips' => ['77001', '77002', '77003', '77004', '77005']],
    ['name' => 'San Antonio', 'state' => 'TX', 'zips' => ['78201', '78202', '78203', '78204', '78205']],
    ['name' => 'Denver', 'state' => 'CO', 'zips' => ['80201', '80202', '80203', '80204', '80205']],
];

$property_types = [
    'single_family' => ['min_bed' => 2, 'max_bed' => 5, 'min_bath' => 1, 'max_bath' => 4, 'min_sqft' => 1200, 'max_sqft' => 4000],
    'condo' => ['min_bed' => 1, 'max_bed' => 3, 'min_bath' => 1, 'max_bath' => 2.5, 'min_sqft' => 600, 'max_sqft' => 2000],
    'townhouse' => ['min_bed' => 2, 'max_bed' => 4, 'min_bath' => 1.5, 'max_bath' => 3.5, 'min_sqft' => 1000, 'max_sqft' => 2500],
    'multi_family' => ['min_bed' => 3, 'max_bed' => 6, 'min_bath' => 2, 'max_bath' => 4, 'min_sqft' => 2000, 'max_sqft' => 5000],
];

// Clear existing data (optional)
global $db;
$db->query("DELETE FROM mock_mls_data WHERE 1=1");
echo "Cleared existing data.\n";

$total_properties = 150; // Generate 150 properties
$created_count = 0;

for ($i = 0; $i < $total_properties; $i++) {
    $mockMLS = new MockMLS();
    
    // Generate property ID
    $mockMLS->property_id = 'MLS' . str_pad($i + 1000, 6, '0', STR_PAD_LEFT);
    
    // Generate address
    $street_num = rand(100, 9999);
    $street_name = $streets[array_rand($streets)];
    $street_type = $street_types[array_rand($street_types)];
    $mockMLS->address = $street_num . ' ' . $street_name . ' ' . $street_type;
    
    // Select random city
    $city_data = $cities[array_rand($cities)];
    $mockMLS->city = $city_data['name'];
    $mockMLS->state = $city_data['state'];
    $mockMLS->zip = $city_data['zips'][array_rand($city_data['zips'])];
    
    // Select property type and generate appropriate specs
    $prop_type = array_rand($property_types);
    $mockMLS->property_type = $prop_type;
    $specs = $property_types[$prop_type];
    
    // Generate property details based on type
    $mockMLS->bedrooms = rand($specs['min_bed'], $specs['max_bed']);
    $mockMLS->bathrooms = rand($specs['min_bath'] * 2, $specs['max_bath'] * 2) / 2; // Allow .5 increments
    $mockMLS->square_footage = rand($specs['min_sqft'], $specs['max_sqft']);
    
    // Lot size (condos typically have 0)
    if ($prop_type === 'condo') {
        $mockMLS->lot_size = 0;
    } else {
        $mockMLS->lot_size = round(rand(2000, 43560) / 43560, 2); // Convert sq ft to acres
    }
    
    // Year built - weighted towards recent years
    $year_weight = rand(1, 100);
    if ($year_weight <= 30) {
        $mockMLS->year_built = rand(1950, 1980);
    } elseif ($year_weight <= 60) {
        $mockMLS->year_built = rand(1981, 2000);
    } else {
        $mockMLS->year_built = rand(2001, 2023);
    }
    
    // Calculate base price based on location, size, and age
    $base_price_per_sqft = rand(150, 400); // Base price per sq ft
    
    // Adjust for age
    $age = date('Y') - $mockMLS->year_built;
    if ($age > 50) {
        $base_price_per_sqft *= 0.8;
    } elseif ($age < 10) {
        $base_price_per_sqft *= 1.2;
    }
    
    // Calculate list price
    $mockMLS->list_price = round($mockMLS->square_footage * $base_price_per_sqft, -3); // Round to nearest thousand
    
    // Determine if property is sold (70% sold)
    $is_sold = rand(1, 100) <= 70;
    
    // Generate listing date (within last 2 years)
    $days_ago = rand(1, 730);
    $mockMLS->listing_date = date('Y-m-d', strtotime("-$days_ago days"));
    
    if ($is_sold) {
        // Generate days on market (realistic distribution)
        $dom_weight = rand(1, 100);
        if ($dom_weight <= 40) {
            $mockMLS->days_on_market = rand(1, 30); // 40% sell quickly
        } elseif ($dom_weight <= 70) {
            $mockMLS->days_on_market = rand(31, 90); // 30% moderate time
        } else {
            $mockMLS->days_on_market = rand(91, 180); // 30% longer time
        }
        
        // Sold date
        $mockMLS->sold_date = date('Y-m-d', strtotime($mockMLS->listing_date . ' + ' . $mockMLS->days_on_market . ' days'));
        
        // Sold price (usually slightly below list)
        $price_ratio = rand(92, 105) / 100; // Between 92% and 105% of list
        $mockMLS->sold_price = round($mockMLS->list_price * $price_ratio, -3);
    } else {
        // Active listing
        $mockMLS->days_on_market = (strtotime('now') - strtotime($mockMLS->listing_date)) / 86400;
        $mockMLS->sold_price = null;
        $mockMLS->sold_date = null;
    }
    
    // Set timestamps
    $mockMLS->date_entered = date('Y-m-d H:i:s');
    $mockMLS->date_modified = date('Y-m-d H:i:s');
    $mockMLS->deleted = 0;
    
    // Save the record
    $mockMLS->save();
    $created_count++;
    
    if ($created_count % 10 == 0) {
        echo "Created $created_count properties...\n";
    }
}

echo "\nSuccessfully created $created_count mock MLS properties!\n";

// Display some statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN sold_price IS NOT NULL THEN 1 END) as sold,
    COUNT(CASE WHEN sold_price IS NULL THEN 1 END) as active,
    AVG(list_price) as avg_list_price,
    AVG(sold_price) as avg_sold_price,
    AVG(days_on_market) as avg_dom
    FROM mock_mls_data WHERE deleted = 0";

$result = $db->query($stats_query);
$stats = $db->fetchByAssoc($result);

echo "\nStatistics:\n";
echo "Total Properties: " . $stats['total'] . "\n";
echo "Sold Properties: " . $stats['sold'] . "\n";
echo "Active Listings: " . $stats['active'] . "\n";
echo "Average List Price: $" . number_format($stats['avg_list_price'], 2) . "\n";
echo "Average Sold Price: $" . number_format($stats['avg_sold_price'], 2) . "\n";
echo "Average Days on Market: " . round($stats['avg_dom']) . "\n";

echo "\nDone!\n";