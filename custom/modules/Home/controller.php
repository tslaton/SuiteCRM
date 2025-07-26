<?php
/**
 * Custom Home Controller for Real Estate Hub
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('modules/Home/controller.php');

class CustomHomeController extends HomeController
{
    /**
     * Action to display the Real Estate Hub dashboard
     */
    public function action_RealEstateHub()
    {
        $this->view = 'realestatehub';
    }

    /**
     * Override pre_process to handle dashboard type preference
     */
    public function pre_process()
    {
        parent::pre_process();
        
        // Check if user has a dashboard preference
        global $current_user;
        $dashboardType = $current_user->getPreference('dashboard_type', 'Home');
        
        // If no preference is set, default to Real Estate Hub
        if (empty($dashboardType)) {
            $dashboardType = 'real_estate_hub';
            $current_user->setPreference('dashboard_type', $dashboardType, 0, 'Home');
        }
    }
}