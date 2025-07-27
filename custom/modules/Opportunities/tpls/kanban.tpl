{*
 * Kanban Board Template for Transactions Module
 * Uses Bootstrap 3 for responsive layout
 *}

<div id="kanban-container">
    {* Module Title *}
    <div class="moduleTitle">
        <h2>
            {$MOD.LBL_MODULE_NAME}
        </h2>
        <div class="clear"></div>
    </div>
    
    {* Tab Navigation *}
    <div id="EditView_tabs" style="margin-bottom: 20px;">
        <ul class="nav nav-tabs">
            <li role="presentation">
                <a href="index.php?module=Opportunities&action=index">
                    <span class="glyphicon glyphicon-list"></span> List View
                </a>
            </li>
            <li role="presentation" class="active">
                <a href="index.php?module=Opportunities&action=kanban">
                    <span class="glyphicon glyphicon-th"></span> Pipeline View
                </a>
            </li>
        </ul>
    </div>

    
    {* Kanban Board Container *}
    <div class="kanban-board" style="margin-top: 20px;">
        <div class="kanban-stages-grid">
            {foreach from=$salesStages key=stageKey item=stageLabel}
                <div class="kanban-column" data-stage="{$stageKey}">
                    {* Column Header with Stats *}
                    <div class="kanban-column-header">
                        <h4 class="stage-title">{$stageLabel}</h4>
                        <div class="stage-stats">
                            <span class="badge">{$stageStats.$stageKey.count|default:0}</span>
                            <span class="stage-total">{$stageStats.$stageKey.total_amount|default:"$0.00"}</span>
                        </div>
                        {if $stageStats.$stageKey.total_commission}
                        <div class="stage-commission">
                            <i class="glyphicon glyphicon-piggy-bank"></i>
                            <span>{$stageStats.$stageKey.total_commission}</span>
                        </div>
                        {/if}
                    </div>
                    
                    {* Cards Container *}
                    <div class="kanban-cards" id="stage-{$stageKey}">
                        {if isset($groupedOpportunities.$stageKey) && count($groupedOpportunities.$stageKey) > 0}
                            {foreach from=$groupedOpportunities.$stageKey item=opportunity}
                                <div class="kanban-card{if $opportunity.name|strstr:'Purchase'} purchase-card{elseif $opportunity.name|strstr:'Sale' || $opportunity.name|strstr:'Sell'} sale-card{/if}" data-id="{$opportunity.id}" data-stage="{$stageKey}">
                                    <div class="card-header">
                                        <a href="index.php?module=Opportunities&action=DetailView&record={$opportunity.id}" 
                                           class="card-title" target="_blank">
                                            {$opportunity.name|truncate:50:"...":true}
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        {if $opportunity.account_name}
                                            <div class="card-field">
                                                <i class="glyphicon glyphicon-briefcase"></i>
                                                <span class="account-name">{$opportunity.account_name|truncate:30:"...":true}</span>
                                            </div>
                                        {/if}
                                        <div class="card-field">
                                            <i class="glyphicon glyphicon-usd"></i>
                                            <span class="amount">{$opportunity.amount_formatted}</span>
                                        </div>
                                        <div class="card-field">
                                            <i class="glyphicon glyphicon-piggy-bank"></i>
                                            <span class="commission">{$opportunity.commission_formatted}</span>
                                        </div>
                                        <div class="card-field">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                            <span class="close-date">{$opportunity.date_closed}</span>
                                        </div>
                                        <div class="card-field">
                                            <i class="glyphicon glyphicon-time"></i>
                                            <span class="days-in-stage">{$opportunity.days_in_stage} days old</span>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <small class="text-muted">
                                            {if $opportunity.assigned_user_name}
                                                {$opportunity.assigned_user_name}
                                            {else}
                                                Unassigned
                                            {/if}
                                        </small>
                                    </div>
                                </div>
                            {/foreach}
                        {else}
                            <div class="kanban-empty">No transactions in this stage</div>
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
</div>

{* CSS Styles *}
<style>
{literal}
/* Tab Navigation Styles to match SuiteCRM */
#EditView_tabs {
    margin-bottom: 0;
}

#EditView_tabs .nav-tabs {
    border-bottom: 1px solid #ddd;
    margin-bottom: 0;
}

#EditView_tabs .nav-tabs > li {
    margin-bottom: -1px;
}

#EditView_tabs .nav-tabs > li > a {
    color: #fff;
    background-color: #777;
    border: 1px solid #666;
    border-radius: 4px 4px 0 0;
    margin-right: 2px;
    padding: 8px 20px;
    font-weight: bold;
}

#EditView_tabs .nav-tabs > li > a:hover {
    background-color: #666;
    color: #fff;
    text-decoration: none;
}

#EditView_tabs .nav-tabs > li.active > a,
#EditView_tabs .nav-tabs > li.active > a:hover,
#EditView_tabs .nav-tabs > li.active > a:focus {
    color: #fff;
    background-color: #534d64;
    border: 1px solid #534d64;
    border-bottom-color: transparent;
    cursor: default;
}

.kanban-board {
    overflow-x: auto;
    padding: 15px;
    background-color: #f5f5f5;
    margin-top: -1px;
    border: 1px solid #ddd;
    border-top: none;
}

.kanban-stages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    align-items: start;
}

.kanban-column {
    min-height: 500px;
}

.kanban-column-header {
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom: 3px solid #534d64;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 4px 4px 0 0;
}

.stage-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #534d64;
}

.stage-stats {
    margin-top: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stage-total {
    font-weight: normal;
    color: #333;
}

.stage-commission {
    margin-top: 8px;
    font-size: 13px;
    color: #666;
}

.stage-commission i {
    color: #5cb85c;
    margin-right: 5px;
}

.kanban-cards {
    min-height: 400px;
    background-color: #e9ecef;
    border: 1px dashed #ccc;
    border-radius: 4px;
    padding: 10px;
}

.kanban-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px;
    margin-bottom: 10px;
    cursor: move;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.kanban-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.kanban-card.dragging {
    opacity: 0.5;
}

/* Purchase card styling */
.kanban-card.purchase-card {
    background-color: #f8f6fc;
    border-color: #d7cfeb;
}

.kanban-card.purchase-card:hover {
    border-color: #aa9dcc;
    background-color: #f5f2fa;
}

.kanban-card.purchase-card .card-header {
    background-color: #f0ecf7;
    border-bottom-color: #d7cfeb;
}

.kanban-card.purchase-card .card-title {
    color: #7a6b9a;
}

.kanban-card.purchase-card .card-title:hover {
    color: #534d64;
}

/* Sale card styling */
.kanban-card.sale-card {
    background-color: #f0f7ff;
    border-color: #d0e3f7;
}

.kanban-card.sale-card:hover {
    border-color: #3498db;
    background-color: #e8f2ff;
}

.kanban-card.sale-card .card-header {
    background-color: #e8f2ff;
    border-bottom-color: #d0e3f7;
}

.kanban-card.sale-card .card-title {
    color: #2c7bb6;
}

.kanban-card.sale-card .card-title:hover {
    color: #1a5490;
}

.card-header {
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
    margin-bottom: 8px;
}

.card-title {
    font-weight: 600;
    color: #534d64;
    text-decoration: none;
    display: block;
    margin-bottom: 5px;
}

.card-title:hover {
    color: #3a3654;
    text-decoration: underline;
}

.card-field {
    margin: 5px 0;
    font-size: 13px;
    color: #666;
}

.card-field i {
    width: 16px;
    color: #999;
    margin-right: 5px;
}

.card-footer {
    border-top: 1px solid #eee;
    padding-top: 8px;
    margin-top: 8px;
}

.account-name {
    color: #3498db;
}

.amount {
    font-weight: normal;
    color: #333;
}

.commission {
    font-weight: 600;
    color: #5cb85c;
}

.close-date {
    color: #333;
}

.days-in-stage {
    color: #666;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .kanban-column {
        min-width: 300px;
    }
    
    .kanban-board .row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
    }
}

/* Drag and drop visual feedback */
.kanban-cards.drag-over {
    background-color: #d1ecf1;
    border-color: #3498db;
}

/* Loading state */
.kanban-loading {
    text-align: center;
    padding: 50px;
    color: #999;
}

/* Empty state */
.kanban-empty {
    text-align: center;
    padding: 20px;
    color: #999;
    font-style: italic;
}
{/literal}
</style>

{* JavaScript for drag and drop functionality *}
<script type="text/javascript" src="custom/modules/Opportunities/js/kanban.js"></script>

{* Additional styles for notifications *}
<style>
{literal}
.kanban-alert {
    margin: 10px 0;
    position: fixed;
    top: 60px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
{/literal}
</style>