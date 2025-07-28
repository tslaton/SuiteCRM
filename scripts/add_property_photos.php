<?php
/**
 * Add Property Photos Script
 * Assigns unique photo URLs to active listings
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once('include/entryPoint.php');

global $current_user, $db;

// Check if we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

// Set admin user
$current_user = BeanFactory::getBean('Users', '1');

echo "\n=== ADDING UNIQUE PROPERTY PHOTOS TO ACTIVE LISTINGS ===\n";

// Create a combined pool of unique images from multiple property types
$unique_images = array(
    // Residential (8 images)
    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop', // Modern house
    'https://images.unsplash.com/photo-1583608205776-bfd35f0d9f83?w=800&h=600&fit=crop', // Traditional house
    'https://images.unsplash.com/photo-1572120360610-d971b9d7767c?w=800&h=600&fit=crop', // White house
    'https://images.unsplash.com/photo-1605146769289-440113cc3d00?w=800&h=600&fit=crop', // Large home
    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800&h=600&fit=crop', // Two story house
    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop', // Mansion
    'https://images.unsplash.com/photo-1598228723793-52759bba239c?w=800&h=600&fit=crop', // Contemporary
    'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=600&fit=crop', // Modern design
    
    // Condo (4 images)
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&h=600&fit=crop', // Modern condo
    'https://images.unsplash.com/photo-1567496898669-ee935f5f647a?w=800&h=600&fit=crop', // Luxury condo
    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop', // Condo interior
    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=600&fit=crop', // Apartment living
    
    // Townhouse (3 images)
    'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&h=600&fit=crop', // Townhouse row
    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=600&fit=crop', // Modern townhouse
    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop', // Townhome exterior
    
    // Commercial (2 images)
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop', // Office building
    'https://images.unsplash.com/photo-1554435493-93422e8220c8?w=800&h=600&fit=crop', // Commercial space
    
    // Multi-unit (1 image)
    'https://images.unsplash.com/photo-1460317442991-0ec209397118?w=800&h=600&fit=crop', // Apartment building
);

// Ensure we have exactly 18 unique images
if (count($unique_images) != 18) {
    echo "Warning: We have " . count($unique_images) . " unique images, expected 18\n";
}

// Get all active properties
$query = "SELECT id, street_address, city, state, bedrooms, bathrooms, square_footage 
          FROM properties 
          WHERE deleted = 0 
          AND status = 'active'
          ORDER BY date_entered ASC";  // Order by date to ensure consistent assignment
$result = $db->query($query);

$properties = array();
while ($property = $db->fetchByAssoc($result)) {
    $properties[] = $property;
}

$total_properties = count($properties);
echo "Found $total_properties active properties\n\n";

if ($total_properties != 18) {
    echo "Note: Expected 18 active properties, found $total_properties\n\n";
}

// Assign unique images to each property
$updated_count = 0;
foreach ($properties as $index => $property) {
    if ($index < count($unique_images)) {
        $selected_photo = $unique_images[$index];
        
        // Update the property
        $update_query = "UPDATE properties 
                        SET main_photo = " . $db->quoted($selected_photo) . ", 
                            date_modified = NOW() 
                        WHERE id = " . $db->quoted($property['id']);
        
        if ($db->query($update_query)) {
            $updated_count++;
            $beds_baths = "{$property['bedrooms']}bd/{$property['bathrooms']}ba";
            echo sprintf("✓ [%2d] %-35s (%s)\n", 
                $updated_count,
                substr($property['street_address'], 0, 35),
                $beds_baths
            );
        } else {
            echo "✗ Failed to update {$property['street_address']}\n";
        }
    } else {
        echo "⚠ No more unique images available for {$property['street_address']}\n";
    }
}

// Also update any other properties (non-active) that don't have photos
echo "\n=== UPDATING OTHER PROPERTIES WITHOUT PHOTOS ===\n";

// Property photo URLs by type for non-active properties
$photo_urls = array(
    'residential' => array(
        'https://images.unsplash.com/photo-1513584684374-8bab748fbf90?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1416331108676-a22ccb276e35?w=800&h=600&fit=crop',
    ),
    'condo' => array(
        'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1565182999561-18d7dc61c393?w=800&h=600&fit=crop',
    ),
    'townhouse' => array(
        'https://images.unsplash.com/photo-1599423300746-b62533397364?w=800&h=600&fit=crop',
    ),
    'commercial' => array(
        'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&h=600&fit=crop',
    ),
    'multi_unit' => array(
        'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1565363887715-8884629e09ee?w=800&h=600&fit=crop',
    ),
    'land' => array(
        'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&h=600&fit=crop',
        'https://images.unsplash.com/photo-1440581572325-0bea30075d9d?w=800&h=600&fit=crop',
    ),
);

// Get non-active properties without photos
$other_query = "SELECT id, street_address, city, status, square_footage 
                FROM properties 
                WHERE deleted = 0 
                AND status != 'active'
                AND (main_photo IS NULL OR main_photo = '')";
$other_result = $db->query($other_query);

$other_updated = 0;
while ($property = $db->fetchByAssoc($other_result)) {
    // Determine type based on square footage
    if ($property['square_footage'] > 3000) {
        $photos = $photo_urls['residential']; // Large homes
    } elseif ($property['square_footage'] < 1200) {
        $photos = $photo_urls['condo']; // Small units
    } else {
        $photos = $photo_urls['townhouse']; // Medium size
    }
    
    $selected_photo = $photos[array_rand($photos)];
    
    $update_query = "UPDATE properties 
                    SET main_photo = " . $db->quoted($selected_photo) . ", 
                        date_modified = NOW() 
                    WHERE id = " . $db->quoted($property['id']);
    
    if ($db->query($update_query)) {
        $other_updated++;
        echo "✓ {$property['street_address']} ({$property['status']})\n";
    }
}

echo "\n=== PROPERTY PHOTOS UPDATE COMPLETE! ===\n";
echo "Results:\n";
echo "- Assigned unique photos to $updated_count active listings\n";
if ($other_updated > 0) {
    echo "- Updated $other_updated non-active properties with photos\n";
}

// Show final distribution
echo "\nFinal photo distribution:\n";
$dist_query = "SELECT status, COUNT(*) as total, 
               COUNT(CASE WHEN main_photo IS NOT NULL AND main_photo != '' THEN 1 END) as with_photo 
               FROM properties 
               WHERE deleted = 0 
               GROUP BY status 
               ORDER BY status";
$dist_result = $db->query($dist_query);

while ($row = $db->fetchByAssoc($dist_result)) {
    echo sprintf("- %-10s: %d total, %d with photos\n", 
        $row['status'], 
        $row['total'], 
        $row['with_photo']
    );
}

echo "\nDone!\n";