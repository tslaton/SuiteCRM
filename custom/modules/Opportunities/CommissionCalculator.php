<?php
/**
 * Commission Calculator Logic Hook for Opportunities (Transactions)
 * Automatically calculates agent commission when a deal is marked as closed
 */

class CommissionCalculator
{
    /**
     * Before save logic hook to calculate commission
     * 
     * @param SugarBean $bean The Opportunity bean being saved
     * @param string $event The event type
     * @param array $arguments Additional arguments
     */
    public function calculateCommission($bean, $event, $arguments)
    {
        // Only calculate if sales stage is changing to 'Closed'
        if ($this->shouldCalculateCommission($bean)) {
            // Get the commission rate
            $commissionRate = $this->getCommissionRate($bean);
            
            // Calculate total commission based on amount and rate
            if (!empty($bean->amount) && $commissionRate > 0) {
                $totalCommission = floatval($bean->amount) * ($commissionRate / 100);
                
                // Check if there's a split commission scenario
                if (!empty($bean->secondary_agent_id_c)) {
                    // Calculate split commissions
                    $this->calculateSplitCommission($bean, $totalCommission);
                } else {
                    // Single agent gets full commission
                    $bean->commission_c = round($totalCommission, 2);
                    $bean->secondary_commission_c = 0;
                    
                    // Log the calculation
                    $GLOBALS['log']->info("Commission calculated for Opportunity {$bean->id}: Amount={$bean->amount}, Rate={$commissionRate}%, Commission={$bean->commission_c}");
                }
            } else {
                // Set to 0 if no amount or rate
                $bean->commission_c = 0;
                $bean->secondary_commission_c = 0;
                $GLOBALS['log']->info("Commission set to 0 for Opportunity {$bean->id}: Amount={$bean->amount}, Rate={$commissionRate}%");
            }
        }
    }
    
    /**
     * Calculate split commission between primary and secondary agents
     * 
     * @param SugarBean $bean The Opportunity bean
     * @param float $totalCommission Total commission amount
     */
    private function calculateSplitCommission($bean, $totalCommission)
    {
        // Get split percentage (default to 50/50 if not set)
        $primarySplit = !empty($bean->commission_split_c) ? floatval($bean->commission_split_c) : 50;
        
        // Ensure split is valid (0-100)
        if ($primarySplit < 0) $primarySplit = 0;
        if ($primarySplit > 100) $primarySplit = 100;
        
        $secondarySplit = 100 - $primarySplit;
        
        // Calculate individual commissions
        $bean->commission_c = round($totalCommission * ($primarySplit / 100), 2);
        $bean->secondary_commission_c = round($totalCommission * ($secondarySplit / 100), 2);
        
        // Log the split calculation
        $GLOBALS['log']->info("Split commission calculated for Opportunity {$bean->id}: " .
            "Total={$totalCommission}, Primary={$bean->commission_c} ({$primarySplit}%), " .
            "Secondary={$bean->secondary_commission_c} ({$secondarySplit}%)");
    }
    
    /**
     * Check if commission should be calculated
     * 
     * @param SugarBean $bean The Opportunity bean
     * @return bool
     */
    private function shouldCalculateCommission($bean)
    {
        // Check if sales_stage is changing
        if (!isset($bean->fetched_row['sales_stage'])) {
            // New record
            return $bean->sales_stage === 'Closed';
        }
        
        // Check if transitioning to Closed stage
        $oldStage = $bean->fetched_row['sales_stage'];
        $newStage = $bean->sales_stage;
        
        // Only calculate when moving TO Closed, not when already Closed
        return ($oldStage !== 'Closed' && $newStage === 'Closed');
    }
    
    /**
     * Get the commission rate for the opportunity
     * 
     * @param SugarBean $bean The Opportunity bean
     * @return float Commission rate as percentage
     */
    private function getCommissionRate($bean)
    {
        global $sugar_config;
        
        // First check if there's a custom commission rate field on the opportunity
        if (!empty($bean->commission_rate_c)) {
            return floatval($bean->commission_rate_c);
        }
        
        // Check if assigned user has a custom commission rate
        if (!empty($bean->assigned_user_id)) {
            $user = BeanFactory::getBean('Users', $bean->assigned_user_id);
            if ($user && !empty($user->commission_rate_c)) {
                return floatval($user->commission_rate_c);
            }
        }
        
        // Fall back to global config setting
        if (isset($sugar_config['default_commission_rate'])) {
            return floatval($sugar_config['default_commission_rate']);
        }
        
        // Default rate if nothing else is configured
        return 2.5; // 2.5% default commission rate
    }
}