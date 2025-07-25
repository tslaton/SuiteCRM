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
        
        echo <<<EOD
<style type="text/css">
/* Tab Navigation Styles to match SuiteCRM */
#EditView_tabs {
    margin-bottom: 10px;
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
</style>
<script type="text/javascript">
$(document).ready(function() {
    // Create tab navigation HTML
    var tabNavigation = '<div id="EditView_tabs" style="margin-top: 10px;">' +
        '<ul class="nav nav-tabs">' +
        '<li role="presentation" class="active">' +
        '<a href="index.php?module=Opportunities&action=index">' +
        '<span class="glyphicon glyphicon-list"></span> List View' +
        '</a>' +
        '</li>' +
        '<li role="presentation">' +
        '<a href="index.php?module=Opportunities&action=kanban">' +
        '<span class="glyphicon glyphicon-th"></span> Pipeline View' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '<div class="clearfix"></div>' +
        '</div>';
    
    // Insert after the module title
    if ($('.moduleTitle').length > 0) {
        $('.moduleTitle').after(tabNavigation);
    } else {
        // Fallback: prepend to the main content area
        $('#content').prepend(tabNavigation);
    }
});
</script>
EOD;
        
        parent::display();
    }
}