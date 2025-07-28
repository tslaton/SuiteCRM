<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$searchFields['Properties'] = array(
    'name' => array('query_type' => 'default'),
    'mls_id' => array('query_type' => 'default'),
    'street_address' => array('query_type' => 'default'),
    'city' => array('query_type' => 'default'),
    'state' => array('query_type' => 'default'),
    'zip_code' => array('query_type' => 'default'),
    'status' => array('query_type' => 'default'),
    'price' => array('query_type' => 'default'),
    'bedrooms' => array('query_type' => 'default'),
    'bathrooms' => array('query_type' => 'default'),
    'square_footage' => array('query_type' => 'default'),
    'date_entered' => array('query_type' => 'default'),
    'date_modified' => array('query_type' => 'default'),
    'assigned_user_id' => array('query_type' => 'default', 'db_field' => array('assigned_user_id')),
);