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
        overflow-x: auto;
        overflow-y: hidden;
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
    
    /* Property listing cards */
    .property-listings {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 0;
        min-height: auto;
    }
    
    .property-card {
        flex: 0 0 280px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
    }
    
    .property-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        transform: translateY(-2px);
        text-decoration: none;
        color: inherit;
    }
    
    .property-card-header {
        background: #f8f8f8;
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .property-card-price {
        font-size: 20px;
        font-weight: 600;
        color: #534d64;
        margin: 0;
    }
    
    .property-card-mls {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }
    
    .property-card-body {
        padding: 15px;
    }
    
    .property-card-address {
        font-weight: 500;
        color: #534d64;
        margin-bottom: 10px;
    }
    
    .property-card-details {
        display: flex;
        gap: 15px;
        color: #666;
        font-size: 14px;
        margin-bottom: 10px;
    }
    
    .property-card-detail {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .property-card-date {
        font-size: 12px;
        color: #999;
        margin-top: 10px;
    }
    
    .property-no-listings {
        text-align: center;
        color: #999;
        font-style: italic;
        padding: 40px;
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
                    {if $dashboardData.widgets.active_listings.properties|@count > 0}
                        <div class="property-listings">
                            {foreach from=$dashboardData.widgets.active_listings.properties item=property}
                                <a href="index.php?module=Properties&action=DetailView&record={$property.id}" class="property-card">
                                    <div class="property-card-header">
                                        <p class="property-card-price">{$property.formatted_price}</p>
                                        <p class="property-card-mls">MLS# {$property.mls_id}</p>
                                    </div>
                                    <div class="property-card-body">
                                        <div class="property-card-address">
                                            {$property.street_address}<br>
                                            {$property.city}, {$property.state} {$property.zip_code}
                                        </div>
                                        <div class="property-card-details">
                                            {if $property.bedrooms}
                                                <div class="property-card-detail">
                                                    <i class="glyphicon glyphicon-bed"></i>
                                                    <span>{$property.bedrooms} Beds</span>
                                                </div>
                                            {/if}
                                            {if $property.bathrooms}
                                                <div class="property-card-detail">
                                                    <i class="glyphicon glyphicon-tint"></i>
                                                    <span>{$property.bathrooms} Baths</span>
                                                </div>
                                            {/if}
                                            {if $property.square_footage}
                                                <div class="property-card-detail">
                                                    <i class="glyphicon glyphicon-home"></i>
                                                    <span>{$property.square_footage|number_format} sqft</span>
                                                </div>
                                            {/if}
                                        </div>
                                        {if $property.formatted_listing_date}
                                            <div class="property-card-date">
                                                Listed: {$property.formatted_listing_date}
                                            </div>
                                        {/if}
                                    </div>
                                </a>
                            {/foreach}
                        </div>
                    {else}
                        <div class="property-no-listings">
                            <i class="glyphicon glyphicon-home" style="font-size: 48px; color: #ddd; margin-bottom: 20px;"></i>
                            <p>No active listings found</p>
                            <a href="index.php?module=Properties&action=EditView" class="btn btn-primary">Add Your First Property</a>
                        </div>
                    {/if}
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
        var widgetTitle = $widget.find('.widget-header span').text();
        
        // Check if this is the active listings widget
        if (widgetTitle.indexOf('My Active Listings') !== -1) {
            // Show loading state
            $body.html('<div style="text-align: center; padding: 40px;"><i class="glyphicon glyphicon-refresh glyphicon-spin"></i> Loading active listings...</div>');
            
            // Reload the page to refresh data (simple approach)
            window.location.reload();
        } else {
            // Show loading state for other widgets
            $body.html('<div style="text-align: center;"><i class="glyphicon glyphicon-refresh glyphicon-spin"></i> Loading...</div>');
            
            // Simulate refresh (replace with actual AJAX call when widgets are implemented)
            setTimeout(function() {
                $body.html('<div style="text-align: center; color: #999; font-style: italic;">Widget content refreshed</div>');
            }, 1000);
        }
    });
});
{/literal}
</script>