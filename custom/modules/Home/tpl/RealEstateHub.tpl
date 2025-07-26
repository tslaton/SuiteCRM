{*
 * Real Estate Hub Dashboard Template
 *}

{literal}
<style>
    .real-estate-hub {
        padding: 20px 0;
    }
    
    .real-estate-hub .widget-container {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .real-estate-hub .widget-header {
        background: #f8f8f8;
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 20px;
        font-size: 16px;
        font-weight: 600;
        color: #534d64;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .real-estate-hub .widget-header i {
        margin-right: 10px;
        color: #aa9dcc;
    }
    
    .real-estate-hub .widget-body {
        padding: 20px;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-style: italic;
    }
    
    .real-estate-hub .widget-body.large {
        min-height: 300px;
    }
    
    .real-estate-hub .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 0;
        min-height: auto;
        font-style: normal;
    }
    
    .real-estate-hub .quick-action-btn {
        flex: 1;
        min-width: 150px;
        background: #aa9dcc;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 15px 20px;
        text-align: center;
        cursor: pointer;
        transition: background 0.3s;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    
    .real-estate-hub .quick-action-btn:hover {
        background: #9589b3;
        text-decoration: none;
        color: #fff;
    }
    
    .real-estate-hub .quick-action-btn i {
        font-size: 24px;
    }
    
    .real-estate-hub .dashboard-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .real-estate-hub .dashboard-title {
        font-size: 28px;
        font-weight: 300;
        color: #534d64;
        margin: 0;
    }
    
    .real-estate-hub .dashboard-actions {
        display: flex;
        gap: 10px;
    }
    
    .real-estate-hub .btn-dashboard-toggle {
        background: #f8f8f8;
        border: 1px solid #e0e0e0;
        color: #534d64;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .real-estate-hub .btn-dashboard-toggle:hover {
        background: #e0e0e0;
    }
    
    @media (max-width: 768px) {
        .real-estate-hub .quick-action-btn {
            min-width: 100%;
        }
        
        .real-estate-hub .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>
{/literal}

<div class="real-estate-hub">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Real Estate Hub</h1>
        <div class="dashboard-actions">
            <button class="btn-dashboard-toggle" onclick="window.location.href='index.php?module=Home&action=index&switch_dashboard=suitecrm'">
                <i class="glyphicon glyphicon-th"></i> Switch to SuiteCRM Dashboard
            </button>
        </div>
    </div>
    
    <div class="row">
        {* Active Listings Widget - Large widget taking up left column *}
        <div class="col-md-6">
            <div class="widget-container">
                <div class="widget-header">
                    <span><i class="glyphicon glyphicon-home"></i>{$dashboardData.widgets.active_listings.title}</span>
                    <i class="glyphicon glyphicon-refresh" style="cursor: pointer;" title="Refresh"></i>
                </div>
                <div class="widget-body large">
                    {$dashboardData.widgets.active_listings.placeholder}
                </div>
            </div>
        </div>
        
        {* Right column with stacked widgets *}
        <div class="col-md-6">
            {* Transaction Pipeline Widget *}
            <div class="widget-container">
                <div class="widget-header">
                    <span><i class="glyphicon glyphicon-stats"></i>{$dashboardData.widgets.transaction_pipeline.title}</span>
                    <i class="glyphicon glyphicon-refresh" style="cursor: pointer;" title="Refresh"></i>
                </div>
                <div class="widget-body">
                    {$dashboardData.widgets.transaction_pipeline.placeholder}
                </div>
            </div>
            
            {* Upcoming Showings/Tasks Widget *}
            <div class="widget-container">
                <div class="widget-header">
                    <span><i class="glyphicon glyphicon-calendar"></i>{$dashboardData.widgets.upcoming_showings.title}</span>
                    <i class="glyphicon glyphicon-refresh" style="cursor: pointer;" title="Refresh"></i>
                </div>
                <div class="widget-body">
                    {$dashboardData.widgets.upcoming_showings.placeholder}
                </div>
            </div>
        </div>
    </div>
    
    {* Quick Actions Widget - Full width at bottom *}
    <div class="row">
        <div class="col-md-12">
            <div class="widget-container">
                <div class="widget-header">
                    <span><i class="glyphicon glyphicon-flash"></i>{$dashboardData.widgets.quick_actions.title}</span>
                </div>
                <div class="widget-body quick-actions">
                    {foreach from=$dashboardData.quickActions item=action}
                        <a href="index.php?module={$action.module}&action={$action.action}" class="quick-action-btn">
                            <i class="glyphicon {$action.icon}"></i>
                            <span>{$action.label}</span>
                        </a>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>

{$chartResources}

<script type="text/javascript">
{literal}
// Placeholder for future widget initialization
$(document).ready(function() {
    // Initialize tooltips
    $('[title]').tooltip();
    
    // Handle refresh buttons
    $('.widget-header .glyphicon-refresh').click(function() {
        var $widget = $(this).closest('.widget-container');
        var $body = $widget.find('.widget-body');
        
        // Show loading state
        $body.html('<div style="text-align: center;"><i class="glyphicon glyphicon-refresh glyphicon-spin"></i> Loading...</div>');
        
        // Simulate refresh (replace with actual AJAX call when widgets are implemented)
        setTimeout(function() {
            $body.html('<div style="text-align: center; color: #999; font-style: italic;">Widget content refreshed</div>');
        }, 1000);
    });
});
{/literal}
</script>