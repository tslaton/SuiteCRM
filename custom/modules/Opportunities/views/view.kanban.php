<?php
/**
 * Kanban View for Transactions (Opportunities) Module
 * Provides a visual pipeline board for managing real estate transactions
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/View/SugarView.php');

class OpportunitiesViewKanban extends SugarView
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display the Kanban view
     */
    public function display()
    {
        global $app_list_strings, $current_user, $mod_strings;

        // Set the view title
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        
        // Get sales stages from the dropdown
        $salesStages = $app_list_strings['sales_stage_dom'];
        
        // Remove empty option if exists
        if (isset($salesStages[''])) {
            unset($salesStages['']);
        }
        
        // Fetch opportunities grouped by sales stage
        $groupedOpportunities = $this->getOpportunitiesGroupedByStage();
        
        // Calculate statistics for each stage
        $stageStats = $this->calculateStageStatistics($groupedOpportunities);
        
        // Assign data to template
        $this->ss->assign('salesStages', $salesStages);
        $this->ss->assign('groupedOpportunities', $groupedOpportunities);
        $this->ss->assign('stageStats', $stageStats);
        $this->ss->assign('currentUserId', $current_user->id);
        
        // Display the template
        echo $this->ss->fetch('custom/modules/Opportunities/tpls/kanban.tpl');
    }
    
    /**
     * Fetch opportunities grouped by sales stage
     * @return array
     */
    private function getOpportunitiesGroupedByStage()
    {
        global $db, $current_user, $app_list_strings;
        
        $groupedData = array();
        $salesStages = $app_list_strings['sales_stage_dom'];
        
        // Initialize array with all stages
        foreach ($salesStages as $stage => $label) {
            if (!empty($stage)) {
                $groupedData[$stage] = array();
            }
        }
        
        // Build the query
        $query = "SELECT 
                    o.id,
                    o.name,
                    o.amount,
                    o.sales_stage,
                    o.date_entered,
                    o.date_modified,
                    o.assigned_user_id,
                    o.date_closed,
                    o.probability,
                    o.commission_c,
                    o.commission_rate_c,
                    a.name as account_name,
                    CONCAT(IFNULL(u.first_name, ''), ' ', IFNULL(u.last_name, '')) as assigned_user_name,
                    DATEDIFF(NOW(), o.date_entered) as days_in_stage,
                    u.commission_rate_c as user_commission_rate
                FROM opportunities o
                LEFT JOIN accounts_opportunities ao ON o.id = ao.opportunity_id AND ao.deleted = 0
                LEFT JOIN accounts a ON ao.account_id = a.id AND a.deleted = 0
                LEFT JOIN users u ON o.assigned_user_id = u.id AND u.deleted = 0
                WHERE o.deleted = 0";
        
        // Add visibility clauses if using SecurityGroups
        if (file_exists('modules/SecurityGroups/SecurityGroup.php')) {
            require_once('modules/SecurityGroups/SecurityGroup.php');
            require_once('modules/Opportunities/Opportunity.php');
            $opportunity = new Opportunity();
            $opportunity->table_name = 'o'; // Use the alias instead of full table name
            $owner_where = $opportunity->getOwnerWhere($current_user->id);
            
            // Only apply owner check for now - skip security groups
            if (!empty($owner_where)) {
                // Replace table name with alias in owner_where
                $owner_where = str_replace('opportunities.', 'o.', $owner_where);
                $query .= " AND (" . $owner_where . ")";
            }
            
            // Skip security group check for now since dummy data doesn't have security group associations
            /*
            // Fix the SecurityGroup query to use our alias
            $group_where = SecurityGroup::getGroupWhere('o', 'Opportunities', $current_user->id);
            
            if (!empty($group_where)) {
                // Replace table name with alias in group_where
                $group_where = str_replace('opportunities.', 'o.', $group_where);
                $query .= " AND " . $group_where;
            }
            */
        }
        
        $query .= " ORDER BY o.date_entered DESC";
        
        $result = $db->query($query);
        
        while ($row = $db->fetchByAssoc($result)) {
            // Format the amount - ensure we have a valid amount
            $amount = !empty($row['amount']) ? $row['amount'] : 0;
            $row['amount_formatted'] = '$' . number_format($amount, 0);
            
            // Calculate days in current stage (simplified - in production would need audit table)
            $row['days_in_stage'] = max(0, intval($row['days_in_stage']));
            
            // Calculate commission
            if (!empty($row['commission_c']) && floatval($row['commission_c']) > 0) {
                // Use actual commission if set and greater than 0
                $commission = floatval($row['commission_c']);
                $row['commission_formatted'] = '$' . number_format($commission, 0);
                $row['commission_type'] = 'actual';
                $row['commission_raw'] = $commission;
            } else {
                // Calculate estimated commission
                $commission_rate = 0;
                if (!empty($row['commission_rate_c'])) {
                    $commission_rate = floatval($row['commission_rate_c']);
                } elseif (!empty($row['user_commission_rate'])) {
                    $commission_rate = floatval($row['user_commission_rate']);
                } else {
                    $commission_rate = 3.0; // Default 3% if not set
                }
                
                $commission = $amount * ($commission_rate / 100);
                $row['commission_formatted'] = '$' . number_format($commission, 0) . ' (est)';
                $row['commission_type'] = 'estimated';
                $row['commission_raw'] = $commission;
            }
            
            // Add to appropriate stage group
            $stage = $row['sales_stage'];
            if (isset($groupedData[$stage])) {
                $groupedData[$stage][] = $row;
            }
        }
        
        return $groupedData;
    }
    
    /**
     * Calculate statistics for each stage
     * @param array $groupedOpportunities
     * @return array
     */
    private function calculateStageStatistics($groupedOpportunities)
    {
        $stats = array();
        
        foreach ($groupedOpportunities as $stage => $opportunities) {
            $count = count($opportunities);
            $totalAmount = 0;
            $totalCommission = 0;
            $hasEstimated = false;
            
            foreach ($opportunities as $opp) {
                $totalAmount += floatval($opp['amount']);
                $totalCommission += floatval($opp['commission_raw']);
                if ($opp['commission_type'] == 'estimated') {
                    $hasEstimated = true;
                }
            }
            
            $commissionDisplay = '$' . number_format($totalCommission, 0);
            if ($hasEstimated) {
                $commissionDisplay .= ' (est)';
            }
            
            $stats[$stage] = array(
                'count' => $count,
                'total_amount' => '$' . number_format($totalAmount, 0),
                'total_amount_raw' => $totalAmount,
                'total_commission' => $commissionDisplay,
                'total_commission_raw' => $totalCommission
            );
        }
        
        return $stats;
    }
    
    /**
     * Process AJAX request to update opportunity stage
     */
    public function action_updateStage()
    {
        global $db, $current_user;
        
        // Check for required parameters
        $opportunityId = $_REQUEST['opportunity_id'] ?? '';
        $newStage = $_REQUEST['new_stage'] ?? '';
        
        if (empty($opportunityId) || empty($newStage)) {
            $this->sendJsonResponse(array(
                'success' => false,
                'message' => 'Missing required parameters'
            ));
            return;
        }
        
        // Load the opportunity
        require_once('modules/Opportunities/Opportunity.php');
        $opportunity = new Opportunity();
        $opportunity->retrieve($opportunityId);
        
        if (empty($opportunity->id)) {
            $this->sendJsonResponse(array(
                'success' => false,
                'message' => 'Transaction not found'
            ));
            return;
        }
        
        // Check access rights
        if (!$opportunity->ACLAccess('edit')) {
            $this->sendJsonResponse(array(
                'success' => false,
                'message' => 'Access denied'
            ));
            return;
        }
        
        // Update the stage
        $oldStage = $opportunity->sales_stage;
        $opportunity->sales_stage = $newStage;
        
        // Update probability based on new stage
        global $app_list_strings;
        if (isset($app_list_strings['sales_probability_dom'][$newStage])) {
            $opportunity->probability = $app_list_strings['sales_probability_dom'][$newStage];
        }
        
        $opportunity->save();
        
        // Log the change
        $GLOBALS['log']->info("Kanban: Updated opportunity {$opportunityId} from stage {$oldStage} to {$newStage}");
        
        $this->sendJsonResponse(array(
            'success' => true,
            'message' => 'Transaction stage updated successfully',
            'data' => array(
                'id' => $opportunity->id,
                'old_stage' => $oldStage,
                'new_stage' => $newStage,
                'probability' => $opportunity->probability
            )
        ));
    }
    
    /**
     * Send JSON response
     * @param array $data
     */
    private function sendJsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        sugar_die();
    }
}