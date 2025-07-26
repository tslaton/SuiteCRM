<?php
/**
 * Mock MLS Data Module Vardefs
 * Provides property data for CMA Generator functionality
 */

$dictionary['MockMLS'] = array(
    'table' => 'mock_mls_data',
    'audited' => false,
    'duplicate_merge' => 'disabled',
    'fields' => array(
        // Primary key
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => true,
            'comment' => 'Unique identifier',
        ),
        
        // Property identification
        'property_id' => array(
            'name' => 'property_id',
            'vname' => 'LBL_PROPERTY_ID',
            'type' => 'varchar',
            'len' => '50',
            'required' => true,
            'comment' => 'MLS Property ID',
        ),
        
        // Address fields
        'address' => array(
            'name' => 'address',
            'vname' => 'LBL_ADDRESS',
            'type' => 'varchar',
            'len' => '255',
            'required' => true,
            'comment' => 'Street address',
        ),
        
        'city' => array(
            'name' => 'city',
            'vname' => 'LBL_CITY',
            'type' => 'varchar',
            'len' => '100',
            'required' => true,
            'comment' => 'City',
        ),
        
        'state' => array(
            'name' => 'state',
            'vname' => 'LBL_STATE',
            'type' => 'varchar',
            'len' => '50',
            'required' => true,
            'comment' => 'State/Province',
        ),
        
        'zip' => array(
            'name' => 'zip',
            'vname' => 'LBL_ZIP',
            'type' => 'varchar',
            'len' => '20',
            'required' => true,
            'comment' => 'Postal code',
        ),
        
        // Property details
        'bedrooms' => array(
            'name' => 'bedrooms',
            'vname' => 'LBL_BEDROOMS',
            'type' => 'int',
            'len' => '11',
            'required' => true,
            'comment' => 'Number of bedrooms',
        ),
        
        'bathrooms' => array(
            'name' => 'bathrooms',
            'vname' => 'LBL_BATHROOMS',
            'type' => 'decimal',
            'len' => '3,1',
            'required' => true,
            'comment' => 'Number of bathrooms',
        ),
        
        'square_footage' => array(
            'name' => 'square_footage',
            'vname' => 'LBL_SQUARE_FOOTAGE',
            'type' => 'int',
            'len' => '11',
            'required' => true,
            'comment' => 'Living area in square feet',
        ),
        
        'lot_size' => array(
            'name' => 'lot_size',
            'vname' => 'LBL_LOT_SIZE',
            'type' => 'decimal',
            'len' => '10,2',
            'comment' => 'Lot size in acres',
        ),
        
        'year_built' => array(
            'name' => 'year_built',
            'vname' => 'LBL_YEAR_BUILT',
            'type' => 'int',
            'len' => '11',
            'required' => true,
            'comment' => 'Year property was built',
        ),
        
        // Pricing information
        'list_price' => array(
            'name' => 'list_price',
            'vname' => 'LBL_LIST_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'required' => true,
            'comment' => 'Original listing price',
        ),
        
        'sold_price' => array(
            'name' => 'sold_price',
            'vname' => 'LBL_SOLD_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'comment' => 'Final sold price (null if not sold)',
        ),
        
        // Market timing
        'days_on_market' => array(
            'name' => 'days_on_market',
            'vname' => 'LBL_DAYS_ON_MARKET',
            'type' => 'int',
            'len' => '11',
            'comment' => 'Days property was on market',
        ),
        
        'listing_date' => array(
            'name' => 'listing_date',
            'vname' => 'LBL_LISTING_DATE',
            'type' => 'date',
            'required' => true,
            'comment' => 'Date property was listed',
        ),
        
        'sold_date' => array(
            'name' => 'sold_date',
            'vname' => 'LBL_SOLD_DATE',
            'type' => 'date',
            'comment' => 'Date property was sold',
        ),
        
        // Property type
        'property_type' => array(
            'name' => 'property_type',
            'vname' => 'LBL_PROPERTY_TYPE',
            'type' => 'enum',
            'options' => 'property_type_list',
            'len' => '50',
            'required' => true,
            'comment' => 'Type of property',
        ),
        
        // Standard fields
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'comment' => 'Date record was created',
        ),
        
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'comment' => 'Date record was last modified',
        ),
        
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'default' => '0',
            'comment' => 'Record deletion indicator',
        ),
    ),
    
    'indices' => array(
        array('name' => 'mockmlspk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_property_id', 'type' => 'index', 'fields' => array('property_id')),
        array('name' => 'idx_zip', 'type' => 'index', 'fields' => array('zip')),
        array('name' => 'idx_bedrooms', 'type' => 'index', 'fields' => array('bedrooms')),
        array('name' => 'idx_bathrooms', 'type' => 'index', 'fields' => array('bathrooms')),
        array('name' => 'idx_list_price', 'type' => 'index', 'fields' => array('list_price')),
        array('name' => 'idx_property_type', 'type' => 'index', 'fields' => array('property_type')),
        array('name' => 'idx_listing_date', 'type' => 'index', 'fields' => array('listing_date')),
        array('name' => 'idx_deleted', 'type' => 'index', 'fields' => array('deleted')),
    ),
);