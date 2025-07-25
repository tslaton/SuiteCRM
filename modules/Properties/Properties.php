<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class Properties extends Basic
{
    public $new_schema = true;
    public $module_dir = 'Properties';
    public $object_name = 'Properties';
    public $table_name = 'properties';
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
    public $mls_id;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $price;
    public $status;
    public $bedrooms;
    public $bathrooms;
    public $square_footage;
    public $main_photo;

    public function __construct()
    {
        parent::__construct();
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }
}