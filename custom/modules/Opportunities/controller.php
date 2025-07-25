<?php
/**
 * Custom controller for Opportunities module
 * Adds Kanban view functionality
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/Controller/SugarController.php');

class CustomOpportunitiesController extends SugarController
{
    /**
     * Action to display Kanban view
     */
    public function action_kanban()
    {
        $this->view = 'kanban';
    }
    
    /**
     * Update opportunity stage via AJAX
     * Called when dragging cards in Kanban view
     */
    public function action_updateStage()
    {
        global $db, $current_user;
        
        // Prevent any output buffering issues
        ob_clean();
        
        // Set this view to not use the theme - important for AJAX
        $this->view = 'ajax';
        
        // Set JSON header
        header('Content-Type: application/json');
        
        // Check if user is authenticated
        if (empty($current_user->id)) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Not authenticated'
            ));
            sugar_cleanup(true);
        }
        
        // Get parameters
        $opportunityId = $_POST['opportunity_id'] ?? '';
        $newStage = $_POST['new_stage'] ?? '';
        
        // Validate parameters
        if (empty($opportunityId) || empty($newStage)) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Missing required parameters'
            ));
            sugar_cleanup(true);
        }
        
        // Load the opportunity
        $opportunity = BeanFactory::getBean('Opportunities', $opportunityId);
        
        if (empty($opportunity->id)) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Transaction not found'
            ));
            sugar_cleanup(true);
        }
        
        // Check if user has access to edit this opportunity
        if (!$opportunity->ACLAccess('edit')) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Access denied'
            ));
            sugar_cleanup(true);
        }
        
        // Store the old stage for logging
        $oldStage = $opportunity->sales_stage;
        
        // Update the stage
        $opportunity->sales_stage = $newStage;
        
        // Update probability based on stage
        $opportunity->probability = $this->getStageProbability($newStage);
        
        // Save the opportunity
        try {
            $opportunity->save();
            
            // Log the stage change
            $this->logStageChange($opportunity, $oldStage, $newStage);
            
            echo json_encode(array(
                'success' => true,
                'message' => 'Transaction stage updated successfully',
                'probability' => $opportunity->probability
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Failed to save changes: ' . $e->getMessage()
            ));
        }
        
        sugar_cleanup(true);
    }
    
    /**
     * Get probability based on sales stage
     */
    private function getStageProbability($stage)
    {
        // Define stage probabilities
        $stageProbabilities = array(
            'Prospecting' => 10,
            'Qualification' => 20,
            'Needs Analysis' => 25,
            'Value Proposition' => 30,
            'Id. Decision Makers' => 40,
            'Perception Analysis' => 50,
            'Proposal/Price Quote' => 65,
            'Negotiation/Review' => 80,
            'Closed Won' => 100,
            'Closed Lost' => 0
        );
        
        return isset($stageProbabilities[$stage]) ? $stageProbabilities[$stage] : 50;
    }
    
    /**
     * Log stage change in audit trail
     */
    private function logStageChange($opportunity, $oldStage, $newStage)
    {
        global $current_user;
        
        // Create a note for the stage change
        $note = BeanFactory::newBean('Notes');
        $note->name = "Stage changed from {$oldStage} to {$newStage}";
        $note->description = "Transaction stage was updated via Pipeline View drag and drop\n";
        $note->description .= "Previous Stage: {$oldStage}\n";
        $note->description .= "New Stage: {$newStage}\n";
        $note->description .= "Changed by: {$current_user->full_name}\n";
        $note->description .= "Date/Time: " . date('Y-m-d H:i:s');
        
        $note->parent_type = 'Opportunities';
        $note->parent_id = $opportunity->id;
        $note->assigned_user_id = $current_user->id;
        
        $note->save();
    }
}