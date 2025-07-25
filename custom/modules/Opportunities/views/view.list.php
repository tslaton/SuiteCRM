<?php
/**
 * Custom List View for Opportunities Module
 * Adds Pipeline View toggle button
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('modules/Opportunities/views/view.list.php');

class CustomOpportunitiesViewList extends OpportunitiesViewList
{
    public function display()
    {
        // Add custom JavaScript to inject the Pipeline View button
        echo <<<EOD
<script type="text/javascript">
$(document).ready(function() {
    // Add Pipeline View button to the action menu
    var pipelineButton = '<div class="btn-group" style="margin-left: 10px; display: inline-block;">' +
        '<button class="btn btn-default" onclick="window.location.href=\'index.php?module=Opportunities&action=index\';" disabled>' +
        '<span class="glyphicon glyphicon-list"></span> List View' +
        '</button>' +
        '<a href="index.php?module=Opportunities&action=kanban" class="btn btn-primary">' +
        '<span class="glyphicon glyphicon-th"></span> Pipeline View' +
        '</a>' +
        '</div>';
    
    // Try multiple possible locations for the button
    if ($('.module-title-text').length > 0) {
        // SuiteCRM 7.x with module title
        $('.module-title-text').parent().append(pipelineButton);
    } else if ($('.moduleTitle h2').length > 0) {
        // Older style module title
        $('.moduleTitle h2').append(pipelineButton);
    } else if ($('div.action_buttons').length > 0) {
        // In the action buttons area
        $('div.action_buttons').prepend(pipelineButton);
    } else {
        // Fallback: Add after the search form
        setTimeout(function() {
            if ($('#searchform').length > 0) {
                $('#searchform').after('<div style="margin: 10px 0;">' + pipelineButton.replace('margin-left: 10px;', '') + '</div>');
            }
        }, 500);
    }
});
</script>
EOD;
        
        parent::display();
    }
}