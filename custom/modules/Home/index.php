<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

// Handle dashboard type switching
if (isset($_REQUEST['switch_dashboard'])) {
    $newType = $_REQUEST['switch_dashboard'] === 'suitecrm' ? 'suitecrm' : 'real_estate_hub';
    $current_user->setPreference('dashboard_type', $newType, 0, 'Home');
    
    // Redirect to avoid issues with request parameters
    SugarApplication::redirect('index.php?module=Home&action=index');
    exit;
}

// Check dashboard preference
$dashboardType = $current_user->getPreference('dashboard_type', 'Home');

// Set default only if never set before
if ($dashboardType === null) {
    $dashboardType = 'real_estate_hub';
    $current_user->setPreference('dashboard_type', $dashboardType, 0, 'Home');
}

// Display dashboard type tabs
echo '<div class="dashboard-type-tabs">
    <ul>
        <li>
            <a href="index.php?module=Home&action=index&switch_dashboard=real_estate_hub" 
               class="' . ($dashboardType !== 'suitecrm' ? 'active' : '') . '">
                Real Estate Hub
            </a>
        </li>
        <li>
            <a href="index.php?module=Home&action=index&switch_dashboard=suitecrm" 
               class="' . ($dashboardType === 'suitecrm' ? 'active' : '') . '">
                SuiteCRM Dashboard
            </a>
        </li>
    </ul>
</div>';

// Add the CSS for tabs
echo '<style>
    .dashboard-type-tabs {
        margin-bottom: 20px;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .dashboard-type-tabs ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
    }
    
    .dashboard-type-tabs li {
        margin-right: 20px;
    }
    
    .dashboard-type-tabs a {
        display: inline-block;
        padding: 10px 20px;
        color: #534d64;
        text-decoration: none;
        font-size: 18px;
        font-weight: 300;
        letter-spacing: 1px;
        text-transform: uppercase;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    
    .dashboard-type-tabs a:hover {
        color: #aa9dcc;
        text-decoration: none;
    }
    
    .dashboard-type-tabs a.active {
        color: #aa9dcc;
        border-bottom-color: #aa9dcc;
    }
</style>';

// Load appropriate dashboard
if ($dashboardType === 'real_estate_hub') {
    require_once('custom/modules/Home/views/view.realestatehub.php');
    $view = new HomeViewRealEstateHub();
    $view->display();
} else {
    // Include the original home module
    require_once('modules/Home/index.php');
}