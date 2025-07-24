<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$mod_strings = array(
    // Module
    'LBL_MODULE_NAME' => 'Properties',
    'LBL_MODULE_TITLE' => 'Properties: Home',
    'LBL_MODULE_ID' => 'Properties',
    'LBL_SEARCH_FORM_TITLE' => 'Property Search',
    'LBL_LIST_FORM_TITLE' => 'Property List',
    'LBL_NEW_FORM_TITLE' => 'New Property',
    
    // Menu
    'LNK_NEW_PROPERTY' => 'Create Property',
    'LNK_PROPERTY_LIST' => 'View Properties',
    'LNK_IMPORT_PROPERTIES' => 'Import Properties',
    
    // Fields
    'LBL_NAME' => 'Property Name',
    'LBL_PROPERTY_ADDRESS' => 'Street Address',
    'LBL_PROPERTY_CITY' => 'City',
    'LBL_PROPERTY_STATE' => 'State/Province',
    'LBL_PROPERTY_POSTALCODE' => 'Postal Code',
    'LBL_PROPERTY_COUNTRY' => 'Country',
    'LBL_SQUARE_FOOTAGE' => 'Square Footage',
    'LBL_BEDROOMS' => 'Bedrooms',
    'LBL_BATHROOMS' => 'Bathrooms',
    'LBL_YEAR_BUILT' => 'Year Built',
    'LBL_LISTING_PRICE' => 'Listing Price',
    'LBL_CURRENCY' => 'Currency',
    'LBL_CURRENCY_NAME' => 'Currency Name',
    'LBL_CURRENCY_SYMBOL' => 'Currency Symbol',
    'LBL_PROPERTY_TYPE' => 'Property Type',
    'LBL_MLS_ID' => 'MLS ID',
    'LBL_STATUS' => 'Status',
    'LBL_COVER_IMAGE' => 'Cover Image',
    'LBL_VIRTUAL_TOUR_LINK' => 'Virtual Tour Link',
    'LBL_LOT_SIZE' => 'Lot Size (acres)',
    'LBL_GARAGE_SPACES' => 'Garage Spaces',
    'LBL_AMENITIES' => 'Amenities',
    'LBL_LISTING_DATE' => 'Listing Date',
    'LBL_SOLD_DATE' => 'Sold Date',
    'LBL_SOLD_PRICE' => 'Sold Price',
    
    // Panels
    'LBL_PROPERTY_INFORMATION' => 'Property Information',
    'LBL_ADDRESS_INFORMATION' => 'Address Information',
    'LBL_PROPERTY_DETAILS' => 'Property Details',
    'LBL_LISTING_INFORMATION' => 'Listing Information',
    'LBL_MEDIA_INFORMATION' => 'Media & Tours',
    'LBL_ADDITIONAL_FEATURES' => 'Additional Features',
    'LBL_SALES_INFORMATION' => 'Sales Information',
    
    // Relationships
    'LBL_ACCOUNTS' => 'Accounts',
    'LBL_CONTACTS' => 'Contacts',
    'LBL_OPPORTUNITIES' => 'Opportunities',
    
    // List View
    'LBL_LIST_NAME' => 'Property',
    'LBL_LIST_ADDRESS' => 'Address',
    'LBL_LIST_CITY' => 'City',
    'LBL_LIST_STATE' => 'State',
    'LBL_LIST_TYPE' => 'Type',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_PRICE' => 'Price',
    'LBL_LIST_BEDROOMS' => 'Beds',
    'LBL_LIST_BATHROOMS' => 'Baths',
    'LBL_LIST_SQFT' => 'Sq Ft',
    
    // Subpanels
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Interested Contacts',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Related Accounts',
    'LBL_OPPORTUNITIES_SUBPANEL_TITLE' => 'Related Opportunities',
    
    // Quick Create
    'LBL_QUICK_CREATE' => 'Create Property',
    
    // Additional Labels
    'LBL_FULL_ADDRESS' => 'Full Address',
    'LBL_PROPERTY_SUMMARY' => 'Property Summary',
    'LBL_DAYS_ON_MARKET' => 'Days on Market',
    'LBL_SEARCH_BUTTON' => 'Search',
    'LBL_CLEAR_BUTTON' => 'Clear',
);

// Dropdown lists
$app_list_strings['property_type_list'] = array(
    '' => '',
    'single_family' => 'Single Family Home',
    'condo' => 'Condominium',
    'townhouse' => 'Townhouse',
    'multi_family' => 'Multi-Family',
    'apartment' => 'Apartment',
    'commercial' => 'Commercial',
    'land' => 'Land/Lot',
    'mobile_home' => 'Mobile Home',
    'other' => 'Other',
);

$app_list_strings['property_status_list'] = array(
    '' => '',
    'active' => 'Active',
    'pending' => 'Pending',
    'sold' => 'Sold',
    'off_market' => 'Off Market',
    'coming_soon' => 'Coming Soon',
    'contingent' => 'Contingent',
    'withdrawn' => 'Withdrawn',
    'expired' => 'Expired',
);