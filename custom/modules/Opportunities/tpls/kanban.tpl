{*
 * Kanban Board Template for Transactions Module
 * Uses Bootstrap 3 for responsive layout
 *}

<div id="kanban-container">
    {* Header with title and view toggle *}
    <div class="moduleTitle">
        <h2 style="display: inline-block;">
            <a href="index.php?module=Opportunities&action=index">{$MOD.LBL_MODULE_NAME}</a>
            <span class="pointer">»</span>
            Pipeline View
        </h2>
        <div class="btn-group pull-right" style="margin-top: 10px;">
            <a href="index.php?module=Opportunities&action=index" class="btn btn-default">
                <span class="glyphicon glyphicon-list"></span> List View
            </a>
            <button class="btn btn-primary active" disabled>
                <span class="glyphicon glyphicon-th"></span> Pipeline View
            </button>
        </div>
        <div class="clear"></div>
    </div>

    
    {* Kanban Board Container *}
    <div class="kanban-board" style="margin-top: 20px;">
        <div class="row">
            {foreach from=$salesStages key=stageKey item=stageLabel}
                <div class="col-sm-6 col-md-4 col-lg-2 kanban-column" data-stage="{$stageKey}">
                    {* Column Header with Stats *}
                    <div class="kanban-column-header">
                        <h4 class="stage-title">{$stageLabel}</h4>
                        <div class="stage-stats">
                            <span class="badge">{$stageStats.$stageKey.count|default:0}</span>
                            <span class="stage-total">{$stageStats.$stageKey.total_amount|default:"$0.00"}</span>
                        </div>
                    </div>
                    
                    {* Cards Container *}
                    <div class="kanban-cards" id="stage-{$stageKey}">
                        {if isset($groupedOpportunities.$stageKey) && count($groupedOpportunities.$stageKey) > 0}
                            {foreach from=$groupedOpportunities.$stageKey item=opportunity}
                                <div class="kanban-card" data-id="{$opportunity.id}" data-stage="{$stageKey}">
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
                                            <i class="glyphicon glyphicon-calendar"></i>
                                            <span class="close-date">{$opportunity.date_closed}</span>
                                        </div>
                                        <div class="card-field">
                                            <i class="glyphicon glyphicon-time"></i>
                                            <span class="days-in-stage">{$opportunity.days_in_stage} days</span>
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
.kanban-board {
    overflow-x: auto;
    padding: 15px;
    background-color: #f5f5f5;
}

.kanban-column {
    margin-bottom: 20px;
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
    font-weight: bold;
    color: #27ae60;
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
    font-weight: bold;
    color: #27ae60;
}

.close-date {
    color: #e74c3c;
}

.days-in-stage {
    color: #f39c12;
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