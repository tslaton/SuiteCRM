<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ForPropertiesContactsRoles extends SubPanel
{
    function ForPropertiesContactsRoles($module, $record_id, $subpanel_id, $subpanel_defs)
    {
        parent::SubPanel($module, $record_id, $subpanel_id, $subpanel_defs);
    }

    function get_list_query($request_data, $where = '', $limit = -1, $offset = -1)
    {
        global $db;
        
        $property_id = $db->quote($this->parent_bean->id);
        
        $query = "SELECT 
            contacts.*,
            contacts_cstm.*,
            properties_contacts_roles.contact_role as contact_role,
            properties_contacts_roles.id as relationship_id
        FROM contacts
        LEFT JOIN contacts_cstm ON contacts.id = contacts_cstm.id_c
        INNER JOIN properties_contacts_roles ON contacts.id = properties_contacts_roles.contact_id
        WHERE properties_contacts_roles.property_id = '$property_id'
        AND properties_contacts_roles.deleted = 0
        AND contacts.deleted = 0";
        
        if (!empty($where)) {
            $query .= " AND $where";
        }
        
        $query .= " ORDER BY contacts.last_name, contacts.first_name";
        
        if ($limit > 0) {
            $query .= " LIMIT $offset, $limit";
        }
        
        return $query;
    }
}