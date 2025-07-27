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
        
        // Fetch transaction pipeline data
        $pipelineData = $this->getTransactionPipeline($current_user->id);
        
        // Fetch upcoming showings data
        $upcomingShowings = $this->getUpcomingShowings($current_user->id);
        
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
                    'properties' => $activeProperties['properties'],
                    'total_value' => $activeProperties['total_value'],
                    'formatted_total_value' => $activeProperties['formatted_total_value']
                ),
                'transaction_pipeline' => array(
                    'title' => 'Transaction Pipeline',
                    'icon' => 'icon-bar-chart',
                    'placeholder' => 'Transaction pipeline visualization will be displayed here',
                    'col' => 2,
                    'row' => 1,
                    'size' => 'medium',
                    'pipeline' => $pipelineData
                ),
                'upcoming_showings' => array(
                    'title' => 'Upcoming Showings/Tasks',
                    'icon' => 'icon-calendar',
                    'placeholder' => 'Upcoming showings and tasks will be displayed here',
                    'col' => 3,
                    'row' => 1,
                    'size' => 'medium',
                    'showings' => $upcomingShowings
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
                $row['main_photo'] = $prop->main_photo;
                
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
        
        // Calculate total value of all properties
        $total_value = 0;
        foreach ($properties as $property) {
            $total_value += floatval($property['price']);
        }
        
        return array(
            'properties' => $properties,
            'total_value' => $total_value,
            'formatted_total_value' => '$' . number_format($total_value, 0)
        );
    }
    
    /**
     * Get transaction pipeline data for a user
     * @param string $userId
     * @return array
     */
    private function getTransactionPipeline($userId)
    {
        global $db, $app_list_strings;
        
        $pipeline = array();
        
        // Get sales stages from the app list strings
        $sales_stages = isset($app_list_strings['sales_stage_dom']) ? $app_list_strings['sales_stage_dom'] : array();
        
        // Query to get opportunities grouped by sales stage - only stages that have data
        // Include all opportunities that are not closed (sales_stage not starting with 'Closed')
        $sql = "SELECT 
                    o.sales_stage,
                    COUNT(*) as count,
                    SUM(o.amount) as total_amount
                FROM opportunities o
                WHERE o.deleted = 0
                AND o.sales_stage NOT LIKE 'Closed%'
                GROUP BY o.sales_stage
                ORDER BY 
                    CASE 
                        WHEN o.sales_stage = 'Prospecting' THEN 1
                        WHEN o.sales_stage = 'Qualification' THEN 2
                        WHEN o.sales_stage = 'Needs Analysis' THEN 3
                        WHEN o.sales_stage = 'Value Proposition' THEN 4
                        WHEN o.sales_stage = 'Id. Decision Makers' THEN 5
                        WHEN o.sales_stage = 'Perception Analysis' THEN 6
                        WHEN o.sales_stage = 'Proposal/Price Quote' THEN 7
                        WHEN o.sales_stage = 'Negotiation/Review' THEN 8
                        ELSE 9
                    END";
        
        $result = $db->query($sql);
        
        // Only create pipeline entries for stages that actually have data
        while ($row = $db->fetchByAssoc($result)) {
            $stage = $row['sales_stage'];
            // Use the label from app_list_strings if available, otherwise use the stage name
            $label = isset($sales_stages[$stage]) ? $sales_stages[$stage] : $stage;
            
            $pipeline[$stage] = array(
                'stage' => $stage,
                'label' => $label,
                'count' => (int)$row['count'],
                'amount' => (float)$row['total_amount'],
                'formatted_amount' => '$' . number_format($row['total_amount'], 0)
            );
        }
        
        // Calculate total pipeline value
        $total_count = 0;
        $total_amount = 0;
        foreach ($pipeline as $stage) {
            $total_count += $stage['count'];
            $total_amount += $stage['amount'];
        }
        
        // Define logical order for real estate transaction stages
        $stage_order = array(
            'Inquiry' => 1,
            'Showing' => 2,
            'Offer Made' => 3,
            'Under Contract' => 4,
            'Inspection/Appraisal' => 5,
            'Clear to Close' => 6,
            'Closed' => 7
        );
        
        // Sort pipeline stages by logical order
        $sorted_pipeline = array_values($pipeline);
        usort($sorted_pipeline, function($a, $b) use ($stage_order) {
            $order_a = isset($stage_order[$a['stage']]) ? $stage_order[$a['stage']] : 999;
            $order_b = isset($stage_order[$b['stage']]) ? $stage_order[$b['stage']] : 999;
            return $order_a - $order_b;
        });
        
        return array(
            'stages' => $sorted_pipeline,
            'total_count' => $total_count,
            'total_amount' => $total_amount,
            'formatted_total_amount' => '$' . number_format($total_amount, 0)
        );
    }
    
    /**
     * Get upcoming showings and tasks for a user
     * @param string $userId
     * @return array
     */
    private function getUpcomingShowings($userId)
    {
        global $db, $timedate;
        
        $showings = array();
        
        // Query for upcoming meetings in the next 7 days
        $sql = "SELECT 
                    m.id,
                    m.name,
                    m.date_start,
                    m.date_end,
                    m.status,
                    m.location,
                    m.description,
                    m.parent_type,
                    m.parent_id,
                    o.name as opportunity_name,
                    o.sales_stage,
                    p.street_address,
                    p.city,
                    p.state
                FROM meetings m
                LEFT JOIN opportunities o ON (m.parent_type = 'Opportunities' AND m.parent_id = o.id AND o.deleted = 0)
                LEFT JOIN properties_opportunities po ON (o.id = po.opportunity_id AND po.deleted = 0)
                LEFT JOIN properties p ON (po.property_id = p.id AND p.deleted = 0)
                WHERE m.assigned_user_id = " . $db->quote($userId) . "
                AND m.deleted = 0
                AND m.date_start >= NOW()
                AND m.date_start <= DATE_ADD(NOW(), INTERVAL 7 DAY)
                ORDER BY m.date_start ASC
                LIMIT 20";
        
        $result = $db->query($sql);
        
        while ($row = $db->fetchByAssoc($result)) {
            // Format date and time
            $datetime = strtotime($row['date_start']);
            $row['formatted_date'] = date('M j, Y', $datetime);
            $row['formatted_time'] = date('g:i A', $datetime);
            $row['day_of_week'] = date('l', $datetime);
            
            // Determine day group
            $today = strtotime('today');
            $tomorrow = strtotime('tomorrow');
            $days_from_now = floor(($datetime - $today) / 86400);
            
            if ($days_from_now == 0) {
                $row['day_group'] = 'Today';
            } elseif ($days_from_now == 1) {
                $row['day_group'] = 'Tomorrow';
            } elseif ($days_from_now <= 7) {
                $row['day_group'] = $row['day_of_week'];
            } else {
                $row['day_group'] = 'Next Week';
            }
            
            // Add property info if available
            if (!empty($row['street_address'])) {
                $row['property_address'] = $row['street_address'] . ', ' . $row['city'] . ', ' . $row['state'];
            }
            
            // Determine meeting type based on name or parent
            if (stripos($row['name'], 'showing') !== false) {
                $row['meeting_type'] = 'showing';
                $row['type_icon'] = 'glyphicon-home';
                $row['type_label'] = 'Property Showing';
            } elseif (stripos($row['name'], 'inspection') !== false) {
                $row['meeting_type'] = 'inspection';
                $row['type_icon'] = 'glyphicon-search';
                $row['type_label'] = 'Inspection';
            } elseif (stripos($row['name'], 'appraisal') !== false) {
                $row['meeting_type'] = 'appraisal';
                $row['type_icon'] = 'glyphicon-usd';
                $row['type_label'] = 'Appraisal';
            } elseif (stripos($row['name'], 'negotiation') !== false) {
                $row['meeting_type'] = 'negotiation';
                $row['type_icon'] = 'glyphicon-briefcase';
                $row['type_label'] = 'Negotiation';
            } else {
                $row['meeting_type'] = 'meeting';
                $row['type_icon'] = 'glyphicon-calendar';
                $row['type_label'] = 'Meeting';
            }
            
            $showings[] = $row;
        }
        
        // Group showings by day
        $grouped_showings = array();
        $overdue_count = 0;
        
        // Also check for overdue meetings
        $overdueSql = "SELECT COUNT(*) as count 
                       FROM meetings m
                       WHERE m.assigned_user_id = " . $db->quote($userId) . "
                       AND m.deleted = 0
                       AND m.status = 'Planned'
                       AND m.date_start < NOW()";
        
        $overdueResult = $db->query($overdueSql);
        $overdueRow = $db->fetchByAssoc($overdueResult);
        $overdue_count = (int)$overdueRow['count'];
        
        // Group the showings
        foreach ($showings as $showing) {
            $day_group = $showing['day_group'];
            if (!isset($grouped_showings[$day_group])) {
                $grouped_showings[$day_group] = array();
            }
            $grouped_showings[$day_group][] = $showing;
        }
        
        return array(
            'showings' => $showings,
            'grouped_showings' => $grouped_showings,
            'total_count' => count($showings),
            'overdue_count' => $overdue_count
        );
    }
}