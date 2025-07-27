<?php
/**
 * Custom Controller for Properties Module
 */

require_once('include/MVC/Controller/SugarController.php');

class PropertiesController extends SugarController
{
    /**
     * Generate QR Code action
     */
    public function action_generateqr()
    {
        $this->view = 'generateqr';
    }
    
    /**
     * Generate CMA action (if needed)
     */
    public function action_generatecma()
    {
        $this->view = 'generatecma';
    }
}