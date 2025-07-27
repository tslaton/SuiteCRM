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
        global $db;
        
        $where_clauses = array();
        $where_clauses[] = "deleted = 0";
        
        // Location filter (zip code)
        if (!empty($criteria['zip'])) {
            $zip = $db->quote($criteria['zip']);
            $where_clauses[] = "zip = '$zip'";
        }
        
        // Property type filter
        if (!empty($criteria['property_type'])) {
            $type = $db->quote($criteria['property_type']);
            $where_clauses[] = "property_type = '$type'";
        }
        
        // Bedroom range (+/- 1)
        if (!empty($criteria['bedrooms'])) {
            $min_beds = $criteria['bedrooms'] - 1;
            $max_beds = $criteria['bedrooms'] + 1;
            $where_clauses[] = "bedrooms >= $min_beds AND bedrooms <= $max_beds";
        }
        
        // Bathroom range (+/- 0.5)
        if (!empty($criteria['bathrooms'])) {
            $min_baths = $criteria['bathrooms'] - 0.5;
            $max_baths = $criteria['bathrooms'] + 0.5;
            $where_clauses[] = "bathrooms >= $min_baths AND bathrooms <= $max_baths";
        }
        
        // Square footage range (+/- 20%)
        if (!empty($criteria['square_footage'])) {
            $min_sqft = $criteria['square_footage'] * 0.8;
            $max_sqft = $criteria['square_footage'] * 1.2;
            $where_clauses[] = "square_footage >= $min_sqft AND square_footage <= $max_sqft";
        }
        
        // Price range (+/- 15%)
        if (!empty($criteria['price'])) {
            $min_price = $criteria['price'] * 0.85;
            $max_price = $criteria['price'] * 1.15;
            $where_clauses[] = "list_price >= $min_price AND list_price <= $max_price";
        }
        
        // Only include sold properties if specified
        if (!empty($criteria['sold_only'])) {
            $where_clauses[] = "sold_price IS NOT NULL";
            $where_clauses[] = "sold_date IS NOT NULL";
        }
        
        // Date range filter (e.g., sold within last 6 months)
        if (!empty($criteria['date_range_months'])) {
            $date_limit = date('Y-m-d', strtotime("-{$criteria['date_range_months']} months"));
            if (!empty($criteria['sold_only'])) {
                $where_clauses[] = "sold_date >= '$date_limit'";
            } else {
                $where_clauses[] = "listing_date >= '$date_limit'";
            }
        }
        
        // Build the query
        $where_sql = implode(' AND ', $where_clauses);
        $query = "SELECT * FROM mock_mls_data WHERE $where_sql ORDER BY listing_date DESC LIMIT $limit";
        
        $result = $db->query($query);
        $comparables = array();
        
        while ($row = $db->fetchByAssoc($result)) {
            $comp = new MockMLS();
            foreach ($row as $field => $value) {
                if (property_exists($comp, $field)) {
                    $comp->$field = $value;
                }
            }
            $comparables[] = $comp;
        }
        
        return $comparables;
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