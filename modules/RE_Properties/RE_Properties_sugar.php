<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class RE_Properties_sugar extends Basic
{
    public $new_schema = true;
    public $module_dir = 'RE_Properties';
    public $object_name = 'RE_Properties';
    public $table_name = 're_properties';
    public $importable = true;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    
    // Property specific fields
    public $property_address;
    public $property_city;
    public $property_state;
    public $property_postalcode;
    public $property_country;
    public $square_footage;
    public $bedrooms;
    public $bathrooms;
    public $year_built;
    public $listing_price;
    public $currency_id;
    public $property_type;
    public $mls_id;
    public $status;
    public $cover_image;
    public $virtual_tour_link;
    public $lot_size;
    public $garage_spaces;
    public $amenities;
    public $listing_date;
    public $sold_date;
    public $sold_price;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }
}