<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['RE_Properties'] = array(
    'table' => 're_properties',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        // Standard fields inherited from Basic template
        
        // Property Address Fields
        'property_address' => array(
            'name' => 'property_address',
            'vname' => 'LBL_PROPERTY_ADDRESS',
            'type' => 'varchar',
            'len' => '255',
            'required' => true,
            'importable' => 'true',
        ),
        'property_city' => array(
            'name' => 'property_city',
            'vname' => 'LBL_PROPERTY_CITY',
            'type' => 'varchar',
            'len' => '100',
            'required' => true,
        ),
        'property_state' => array(
            'name' => 'property_state',
            'vname' => 'LBL_PROPERTY_STATE',
            'type' => 'varchar',
            'len' => '100',
            'required' => true,
        ),
        'property_postalcode' => array(
            'name' => 'property_postalcode',
            'vname' => 'LBL_PROPERTY_POSTALCODE',
            'type' => 'varchar',
            'len' => '20',
        ),
        'property_country' => array(
            'name' => 'property_country',
            'vname' => 'LBL_PROPERTY_COUNTRY',
            'type' => 'varchar',
            'len' => '100',
            'default' => 'USA',
        ),
        
        // Property Details
        'square_footage' => array(
            'name' => 'square_footage',
            'vname' => 'LBL_SQUARE_FOOTAGE',
            'type' => 'int',
            'len' => '11',
            'comment' => 'Total square footage of the property',
        ),
        'bedrooms' => array(
            'name' => 'bedrooms',
            'vname' => 'LBL_BEDROOMS',
            'type' => 'int',
            'len' => '11',
            'comment' => 'Number of bedrooms',
        ),
        'bathrooms' => array(
            'name' => 'bathrooms',
            'vname' => 'LBL_BATHROOMS',
            'type' => 'decimal',
            'len' => '10,1',
            'comment' => 'Number of bathrooms (can be decimal for half baths)',
        ),
        'year_built' => array(
            'name' => 'year_built',
            'vname' => 'LBL_YEAR_BUILT',
            'type' => 'int',
            'len' => '4',
            'comment' => 'Year the property was built',
        ),
        
        // Listing Information
        'listing_price' => array(
            'name' => 'listing_price',
            'vname' => 'LBL_LISTING_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'comment' => 'Current listing price',
            'required' => true,
        ),
        'currency_id' => array(
            'name' => 'currency_id',
            'type' => 'id',
            'vname' => 'LBL_CURRENCY',
            'function' => array('name' => 'getCurrencyDropDown', 'returns' => 'html'),
            'reportable' => false,
            'comment' => 'Currency used for display purposes'
        ),
        'currency_name' => array(
            'name' => 'currency_name',
            'rname' => 'name',
            'id_name' => 'currency_id',
            'vname' => 'LBL_CURRENCY_NAME',
            'type' => 'relate',
            'isnull' => 'true',
            'table' => 'currencies',
            'module' => 'Currencies',
            'source' => 'non-db',
            'function' => array('name' => 'getCurrencyNameDropDown', 'returns' => 'html'),
            'studio' => 'false',
            'duplicate_merge' => 'disabled',
        ),
        'currency_symbol' => array(
            'name' => 'currency_symbol',
            'rname' => 'symbol',
            'id_name' => 'currency_id',
            'vname' => 'LBL_CURRENCY_SYMBOL',
            'type' => 'relate',
            'isnull' => 'true',
            'table' => 'currencies',
            'module' => 'Currencies',
            'source' => 'non-db',
            'function' => array('name' => 'getCurrencySymbolDropDown', 'returns' => 'html'),
            'studio' => 'false',
            'duplicate_merge' => 'disabled',
        ),
        'property_type' => array(
            'name' => 'property_type',
            'vname' => 'LBL_PROPERTY_TYPE',
            'type' => 'enum',
            'options' => 'property_type_list',
            'len' => 100,
            'comment' => 'Type of property',
            'required' => true,
        ),
        'mls_id' => array(
            'name' => 'mls_id',
            'vname' => 'LBL_MLS_ID',
            'type' => 'varchar',
            'len' => '50',
            'comment' => 'Multiple Listing Service ID',
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'options' => 'property_status_list',
            'len' => 100,
            'default' => 'active',
            'comment' => 'Current status of the property listing',
            'required' => true,
        ),
        
        // Media Fields
        'cover_image' => array(
            'name' => 'cover_image',
            'vname' => 'LBL_COVER_IMAGE',
            'type' => 'image',
            'dbType' => 'varchar',
            'len' => '255',
            'comment' => 'Main property image',
            'studio' => array(
                'listview' => true,
            ),
        ),
        'virtual_tour_link' => array(
            'name' => 'virtual_tour_link',
            'vname' => 'LBL_VIRTUAL_TOUR_LINK',
            'type' => 'url',
            'dbType' => 'varchar',
            'len' => '255',
            'comment' => 'Link to virtual property tour',
        ),
        
        // Additional Features
        'lot_size' => array(
            'name' => 'lot_size',
            'vname' => 'LBL_LOT_SIZE',
            'type' => 'decimal',
            'len' => '10,2',
            'comment' => 'Lot size in acres',
        ),
        'garage_spaces' => array(
            'name' => 'garage_spaces',
            'vname' => 'LBL_GARAGE_SPACES',
            'type' => 'int',
            'len' => '11',
            'comment' => 'Number of garage spaces',
        ),
        'amenities' => array(
            'name' => 'amenities',
            'vname' => 'LBL_AMENITIES',
            'type' => 'text',
            'comment' => 'Property amenities and features',
        ),
        
        // Dates
        'listing_date' => array(
            'name' => 'listing_date',
            'vname' => 'LBL_LISTING_DATE',
            'type' => 'date',
            'comment' => 'Date property was listed',
        ),
        'sold_date' => array(
            'name' => 'sold_date',
            'vname' => 'LBL_SOLD_DATE',
            'type' => 'date',
            'comment' => 'Date property was sold',
        ),
        'sold_price' => array(
            'name' => 'sold_price',
            'vname' => 'LBL_SOLD_PRICE',
            'type' => 'currency',
            'len' => '26,6',
            'comment' => 'Final sold price',
        ),
        
        // Relationships
        'accounts' => array(
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 're_properties_accounts',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNTS',
        ),
        'contacts' => array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 're_properties_contacts',
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS',
        ),
        'opportunities' => array(
            'name' => 'opportunities',
            'type' => 'link',
            'relationship' => 're_properties_opportunities',
            'module' => 'Opportunities',
            'bean_name' => 'Opportunity',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITIES',
        ),
    ),
    'relationships' => array(
        're_properties_accounts' => array(
            'lhs_module' => 'RE_Properties',
            'lhs_table' => 're_properties',
            'lhs_key' => 'id',
            'rhs_module' => 'Accounts',
            'rhs_table' => 'accounts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 're_properties_accounts',
            'join_key_lhs' => 're_property_id',
            'join_key_rhs' => 'account_id',
        ),
        're_properties_contacts' => array(
            'lhs_module' => 'RE_Properties',
            'lhs_table' => 're_properties',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 're_properties_contacts',
            'join_key_lhs' => 're_property_id',
            'join_key_rhs' => 'contact_id',
        ),
        're_properties_opportunities' => array(
            'lhs_module' => 'RE_Properties',
            'lhs_table' => 're_properties',
            'lhs_key' => 'id',
            'rhs_module' => 'Opportunities',
            'rhs_table' => 'opportunities',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 're_properties_opportunities',
            'join_key_lhs' => 're_property_id',
            'join_key_rhs' => 'opportunity_id',
        ),
        're_properties_currencies' => array(
            'lhs_module' => 'RE_Properties',
            'lhs_table' => 're_properties',
            'lhs_key' => 'currency_id',
            'rhs_module' => 'Currencies',
            'rhs_table' => 'currencies',
            'rhs_key' => 'id',
            'relationship_type' => 'one-to-many'
        ),
    ),
    'indices' => array(
        array('name' => 'idx_property_status', 'type' => 'index', 'fields' => array('status')),
        array('name' => 'idx_property_type', 'type' => 'index', 'fields' => array('property_type')),
        array('name' => 'idx_mls_id', 'type' => 'index', 'fields' => array('mls_id')),
        array('name' => 'idx_listing_date', 'type' => 'index', 'fields' => array('listing_date')),
    ),
);

// Add standard SugarBean template relationships
VardefManager::createVardef('RE_Properties', 'RE_Properties', array('basic', 'assignable', 'security_groups'));