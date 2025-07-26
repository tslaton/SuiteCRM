<?php
/**
 * Mock MLS Bean Class
 * Provides ORM access to mock MLS property data for CMA generation
 */

class MockMLS extends Basic
{
    public $new_schema = true;
    public $module_dir = 'MockMLS';
    public $object_name = 'MockMLS';
    public $table_name = 'mock_mls_data';
    public $importable = false;
    public $disable_row_level_security = true;
    
    // Disable custom fields for this module
    public $disable_custom_fields = true;
    
    // Core fields
    public $id;
    public $property_id;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $bedrooms;
    public $bathrooms;
    public $square_footage;
    public $lot_size;
    public $year_built;
    public $list_price;
    public $sold_price;
    public $days_on_market;
    public $listing_date;
    public $sold_date;
    public $property_type;
    public $date_entered;
    public $date_modified;
    public $deleted;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Find comparable properties based on criteria
     * 
     * @param array $criteria Search criteria including location, size, price range
     * @param int $limit Maximum number of results
     * @return array Array of comparable properties
     */
    public function findComparables($criteria, $limit = 10)
    {
        $query = new SugarQuery();
        $query->select(array('*'));
        $query->from($this);
        
        // Location filter (zip code)
        if (!empty($criteria['zip'])) {
            $query->where()->equals('zip', $criteria['zip']);
        }
        
        // Property type filter
        if (!empty($criteria['property_type'])) {
            $query->where()->equals('property_type', $criteria['property_type']);
        }
        
        // Bedroom range (+/- 1)
        if (!empty($criteria['bedrooms'])) {
            $query->where()->gte('bedrooms', $criteria['bedrooms'] - 1);
            $query->where()->lte('bedrooms', $criteria['bedrooms'] + 1);
        }
        
        // Bathroom range (+/- 0.5)
        if (!empty($criteria['bathrooms'])) {
            $query->where()->gte('bathrooms', $criteria['bathrooms'] - 0.5);
            $query->where()->lte('bathrooms', $criteria['bathrooms'] + 0.5);
        }
        
        // Square footage range (+/- 20%)
        if (!empty($criteria['square_footage'])) {
            $min_sqft = $criteria['square_footage'] * 0.8;
            $max_sqft = $criteria['square_footage'] * 1.2;
            $query->where()->gte('square_footage', $min_sqft);
            $query->where()->lte('square_footage', $max_sqft);
        }
        
        // Price range (+/- 15%)
        if (!empty($criteria['price'])) {
            $min_price = $criteria['price'] * 0.85;
            $max_price = $criteria['price'] * 1.15;
            $query->where()->gte('list_price', $min_price);
            $query->where()->lte('list_price', $max_price);
        }
        
        // Only include sold properties if specified
        if (!empty($criteria['sold_only'])) {
            $query->where()->notNull('sold_price');
            $query->where()->notNull('sold_date');
        }
        
        // Date range filter (e.g., sold within last 6 months)
        if (!empty($criteria['date_range_months'])) {
            $date_limit = date('Y-m-d', strtotime("-{$criteria['date_range_months']} months"));
            if (!empty($criteria['sold_only'])) {
                $query->where()->gte('sold_date', $date_limit);
            } else {
                $query->where()->gte('listing_date', $date_limit);
            }
        }
        
        // Exclude deleted records
        $query->where()->equals('deleted', 0);
        
        // Order by most recent first
        $query->orderBy('listing_date', 'DESC');
        
        // Limit results
        $query->limit($limit);
        
        return $query->execute();
    }
    
    /**
     * Calculate price per square foot
     * 
     * @return float Price per square foot
     */
    public function getPricePerSquareFoot()
    {
        if (empty($this->square_footage) || $this->square_footage == 0) {
            return 0;
        }
        
        $price = !empty($this->sold_price) ? $this->sold_price : $this->list_price;
        return round($price / $this->square_footage, 2);
    }
    
    /**
     * Get property age in years
     * 
     * @return int Age in years
     */
    public function getPropertyAge()
    {
        if (empty($this->year_built)) {
            return 0;
        }
        
        return date('Y') - $this->year_built;
    }
}