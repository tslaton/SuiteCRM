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
}