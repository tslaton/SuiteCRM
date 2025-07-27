<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $layout_defs;

// Contacts subpanel with roles (1st position)
$layout_defs['Opportunities']['subpanel_setup']['contacts_roles'] = array(
    'order' => 100,
    'module' => 'Contacts',
    'subpanel_name' => 'ForTransactionsWithRoles',
    'sort_order' => 'asc',
    'sort_by' => 'last_name',
    'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
    'get_subpanel_data' => 'contacts_roles',
    'top_buttons' => array(
        0 => array(
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
        1 => array(
            'widget_class' => 'SubPanelTopSelectButton',
            'mode' => 'MultiSelect',
        ),
    ),
);

// Remove the documents subpanel - not needed on transaction details page

// Remove all default subpanels except the ones we want
unset($layout_defs['Opportunities']['subpanel_setup']['property']);
unset($layout_defs['Opportunities']['subpanel_setup']['documents']);
unset($layout_defs['Opportunities']['subpanel_setup']['activities']);
unset($layout_defs['Opportunities']['subpanel_setup']['history']);
unset($layout_defs['Opportunities']['subpanel_setup']['leads']);
unset($layout_defs['Opportunities']['subpanel_setup']['contacts']);
unset($layout_defs['Opportunities']['subpanel_setup']['project']);
unset($layout_defs['Opportunities']['subpanel_setup']['opportunity_aos_quotes']);
unset($layout_defs['Opportunities']['subpanel_setup']['opportunities_aos_contracts']);
unset($layout_defs['Opportunities']['subpanel_setup']['securitygroups']);