<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Function to get the subpanel data with roles
function get_contacts_with_roles_query($params)
{
    $property_id = $params['property_id'];
    $return_array['select'] = " SELECT contacts.*, properties_contacts_roles.contact_role ";
    $return_array['from'] = " FROM contacts ";
    $return_array['where'] = " WHERE contacts.deleted = 0 ";
    $return_array['join'] = " INNER JOIN properties_contacts_roles ON contacts.id = properties_contacts_roles.contact_id AND properties_contacts_roles.property_id = '" . $property_id . "' AND properties_contacts_roles.deleted = 0 ";
    $return_array['join_tables'] = array();
    
    return $return_array;
}

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Contacts'),
    ),
    'where' => '',
    'fill_in_additional_fields' => true,
    'list_fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '20%',
        ),
        'contact_role' => array(
            'name' => 'contact_role',
            'vname' => 'LBL_CONTACT_ROLE',
            'width' => '15%',
            'type' => 'enum',
            'options' => 'property_contact_role_list',
            'default' => true,
        ),
        'email1' => array(
            'name' => 'email1',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'widget_class' => 'SubPanelEmailLink',
            'width' => '20%',
        ),
        'phone_work' => array(
            'name' => 'phone_work',
            'vname' => 'LBL_OFFICE_PHONE',
            'width' => '15%',
        ),
        'phone_mobile' => array(
            'name' => 'phone_mobile',
            'vname' => 'LBL_MOBILE_PHONE',
            'width' => '15%',
        ),
        'assigned_user_name' => array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'assigned_user_id',
            'target_module' => 'Employees',
            'width' => '10%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditRoleButton',
            'module' => 'Contacts',
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'Contacts',
            'width' => '4%',
        ),
    ),
    'get_subpanel_data' => 'function:get_contacts_with_roles_query',
);