<?php
/**
 * Real Estate Hub Dashboard View
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/View/SugarView.php');

class HomeViewRealEstateHub extends SugarView
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display the Real Estate Hub dashboard
     */
    public function display()
    {
        global $current_user, $app_strings, $mod_strings, $theme, $db;
        
        $this->ss = new Sugar_Smarty();
        
        // Fetch active properties for current user
        $activeProperties = $this->getActiveProperties($current_user->id);
        
        // Debug: Log the number of properties found
        $GLOBALS['log']->debug("Real Estate Hub Display - Properties count: " . count($activeProperties));
        
        // Initialize dashboard data structure
        $dashboardData = array(
            'widgets' => array(
                'active_listings' => array(
                    'title' => 'My Active Listings',
                    'icon' => 'icon-home',
                    'placeholder' => 'Active property listings will be displayed here',
                    'col' => 1,
                    'row' => 1,
                    'size' => 'large',
                    'properties' => $activeProperties
                ),
                'transaction_pipeline' => array(
                    'title' => 'Transaction Pipeline',
                    'icon' => 'icon-bar-chart',
                    'placeholder' => 'Transaction pipeline visualization will be displayed here',
                    'col' => 2,
                    'row' => 1,
                    'size' => 'medium'
                ),
                'upcoming_showings' => array(
                    'title' => 'Upcoming Showings/Tasks',
                    'icon' => 'icon-calendar',
                    'placeholder' => 'Upcoming showings and tasks will be displayed here',
                    'col' => 3,
                    'row' => 1,
                    'size' => 'medium'
                ),
                'quick_actions' => array(
                    'title' => 'Quick Actions',
                    'icon' => 'icon-plus',
                    'placeholder' => 'Quick action buttons will be displayed here',
                    'col' => 1,
                    'row' => 2,
                    'size' => 'small'
                )
            ),
            'quickActions' => array(
                array(
                    'label' => 'Add Property',
                    'icon' => 'icon-plus-sign',
                    'module' => 'Properties',
                    'action' => 'EditView'
                ),
                array(
                    'label' => 'Schedule Showing',
                    'icon' => 'icon-calendar',
                    'module' => 'Meetings',
                    'action' => 'EditView'
                ),
                array(
                    'label' => 'Add Contact',
                    'icon' => 'icon-user',
                    'module' => 'Contacts',
                    'action' => 'EditView'
                ),
                array(
                    'label' => 'Create Transaction',
                    'icon' => 'icon-briefcase',
                    'module' => 'Opportunities',
                    'action' => 'EditView'
                )
            )
        );
        
        // Assign variables to template
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('theme', $theme);
        $this->ss->assign('dashboardData', $dashboardData);
        $this->ss->assign('current_user_id', $current_user->id);
        
        // Add chart resources
        require_once('include/SugarCharts/SugarChartFactory.php');
        $sugarChart = SugarChartFactory::getInstance();
        if ($sugarChart) {
            $resources = $sugarChart->getChartResources();
            $this->ss->assign('chartResources', $resources);
        }
        
        // Display the template
        echo $this->ss->fetch('custom/modules/Home/tpl/RealEstateHub.tpl');
    }
    
    /**
     * Get active properties for a user
     * @param string $userId
     * @return array
     */
    private function getActiveProperties($userId)
    {
        $properties = array();
        
        // Use SugarBean to fetch properties
        require_once('modules/Properties/Properties.php');
        
        $property = new Properties();
        $where = "properties.status = 'active'";
        $order_by = "properties.price DESC";
        
        $property_list = $property->get_full_list($order_by, $where, true, 20);
        
        if (!empty($property_list)) {
            foreach ($property_list as $prop) {
                $row = array();
                $row['id'] = $prop->id;
                $row['name'] = $prop->name;
                $row['mls_id'] = $prop->mls_id;
                $row['street_address'] = $prop->street_address;
                $row['city'] = $prop->city;
                $row['state'] = $prop->state;
                $row['zip_code'] = $prop->zip_code;
                $row['price'] = $prop->price;
                $row['bedrooms'] = $prop->bedrooms;
                $row['bathrooms'] = $prop->bathrooms;
                $row['square_footage'] = $prop->square_footage;
                $row['property_type'] = $prop->property_type;
                $row['listing_date'] = $prop->listing_date;
                
                // Format price
                if (!empty($row['price'])) {
                    $row['formatted_price'] = '$' . number_format($row['price'], 2);
                } else {
                    $row['formatted_price'] = '$0.00';
                }
                
                // Format address
                $row['full_address'] = $row['street_address'] . ', ' . $row['city'] . ', ' . $row['state'] . ' ' . $row['zip_code'];
                
                // Format listing date
                if (!empty($row['listing_date'])) {
                    $row['formatted_listing_date'] = date('M j, Y', strtotime($row['listing_date']));
                } else if (!empty($prop->date_entered)) {
                    $row['formatted_listing_date'] = date('M j, Y', strtotime($prop->date_entered));
                }
                
                $properties[] = $row;
            }
        }
        
        $GLOBALS['log']->debug("Real Estate Hub - Total active properties found using SugarBean: " . count($properties));
        
        // Sort properties by price in descending order (highest first)
        usort($properties, function($a, $b) {
            $priceA = floatval($a['price']);
            $priceB = floatval($b['price']);
            return $priceB - $priceA; // Descending order
        });
        
        return $properties;
    }
}