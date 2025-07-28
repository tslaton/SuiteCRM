<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class PropertiesDemoData
{
    private $property_data = array(
        array(
            'name' => 'Luxury Downtown Penthouse',
            'mls_id' => 'MLS001',
            'street_address' => '123 Main Street, Penthouse A',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip_code' => '94105',
            'price' => 2500000,
            'status' => 'active',
            'bedrooms' => 3,
            'bathrooms' => 2.5,
            'square_footage' => 2800,
            'description' => 'Stunning penthouse with panoramic city views, modern kitchen, and rooftop terrace.'
        ),
        array(
            'name' => 'Suburban Family Home',
            'mls_id' => 'MLS002',
            'street_address' => '456 Oak Avenue',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip_code' => '90210',
            'price' => 1200000,
            'status' => 'active',
            'bedrooms' => 4,
            'bathrooms' => 3,
            'square_footage' => 3200,
            'description' => 'Beautiful family home with large backyard, pool, and excellent school district.'
        ),
        array(
            'name' => 'Beachfront Condo',
            'mls_id' => 'MLS003',
            'street_address' => '789 Ocean Boulevard, Unit 5B',
            'city' => 'Miami',
            'state' => 'FL',
            'zip_code' => '33139',
            'price' => 850000,
            'status' => 'pending',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'square_footage' => 1500,
            'description' => 'Modern beachfront condo with ocean views, updated kitchen, and beach access.'
        ),
        array(
            'name' => 'Historic Townhouse',
            'mls_id' => 'MLS004',
            'street_address' => '321 Heritage Lane',
            'city' => 'Boston',
            'state' => 'MA',
            'zip_code' => '02108',
            'price' => 1800000,
            'status' => 'active',
            'bedrooms' => 3,
            'bathrooms' => 2.5,
            'square_footage' => 2200,
            'description' => 'Beautifully restored historic townhouse in prime location with modern amenities.'
        ),
        array(
            'name' => 'Mountain Retreat',
            'mls_id' => 'MLS005',
            'street_address' => '555 Pine Ridge Road',
            'city' => 'Denver',
            'state' => 'CO',
            'zip_code' => '80202',
            'price' => 950000,
            'status' => 'active',
            'bedrooms' => 5,
            'bathrooms' => 4,
            'square_footage' => 4000,
            'description' => 'Spacious mountain home with stunning views, hot tub, and ski-in/ski-out access.'
        ),
        array(
            'name' => 'Urban Loft',
            'mls_id' => 'MLS006',
            'street_address' => '888 Industrial Way, Loft 3',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10013',
            'price' => 3200000,
            'status' => 'sold',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'square_footage' => 1800,
            'description' => 'Converted industrial loft with exposed brick, high ceilings, and open floor plan.'
        ),
        array(
            'name' => 'Golf Course Estate',
            'mls_id' => 'MLS007',
            'street_address' => '100 Fairway Drive',
            'city' => 'Phoenix',
            'state' => 'AZ',
            'zip_code' => '85001',
            'price' => 1500000,
            'status' => 'active',
            'bedrooms' => 4,
            'bathrooms' => 3.5,
            'square_footage' => 3500,
            'description' => 'Luxury estate on golf course with pool, outdoor kitchen, and mountain views.'
        ),
        array(
            'name' => 'Waterfront Cottage',
            'mls_id' => 'MLS008',
            'street_address' => '222 Lake Shore Drive',
            'city' => 'Seattle',
            'state' => 'WA',
            'zip_code' => '98101',
            'price' => 750000,
            'status' => 'pending',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'square_footage' => 1600,
            'description' => 'Charming waterfront cottage with private dock, updated interior, and lake views.'
        )
    );
    
    public function create_demo_data()
    {
        global $current_user;
        
        require_once('modules/Properties/Properties.php');
        
        // Get default currency
        $currency_id = '-99'; // Default system currency
        
        foreach ($this->property_data as $data) {
            $property = new Properties();
            
            foreach ($data as $field => $value) {
                $property->$field = $value;
            }
            
            // Set currency
            $property->currency_id = $currency_id;
            
            // Set assigned user to current user or admin
            $property->assigned_user_id = !empty($current_user->id) ? $current_user->id : '1';
            
            // Save the property
            $property->save();
        }
        
        return count($this->property_data);
    }
    
    /**
     * Remove demo data
     */
    public function remove_demo_data()
    {
        $db = DBManagerFactory::getInstance();
        
        // Build MLS ID list for deletion
        $mls_ids = array();
        foreach ($this->property_data as $data) {
            $mls_ids[] = $db->quote($data['mls_id']);
        }
        
        if (!empty($mls_ids)) {
            $mls_list = implode(',', $mls_ids);
            $query = "UPDATE properties SET deleted = 1 WHERE mls_id IN ($mls_list)";
            $db->query($query);
        }
    }
}