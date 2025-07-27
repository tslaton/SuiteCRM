<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('data/Link2.php');

class PropertiesContactsRolesRelationship extends Link2
{
    public function getQuery($params = array())
    {
        global $db;
        
        $property_id = $db->quote($this->focus->id);
        
        $return_array = array();
        $return_array['select'] = "SELECT contacts.*, properties_contacts_roles.contact_role ";
        $return_array['from'] = "FROM contacts ";
        $return_array['join'] = "INNER JOIN properties_contacts_roles ON contacts.id = properties_contacts_roles.contact_id ";
        $return_array['where'] = "WHERE properties_contacts_roles.property_id = '$property_id' 
                                  AND properties_contacts_roles.deleted = 0 
                                  AND contacts.deleted = 0 ";
        
        return $return_array;
    }
    
    public function getBeans($params = array())
    {
        global $db;
        
        $query_array = $this->getQuery($params);
        $query = $query_array['select'] . $query_array['from'] . $query_array['join'] . $query_array['where'];
        
        if (!empty($params['orderby'])) {
            $query .= " ORDER BY " . $params['orderby'];
        } else {
            $query .= " ORDER BY contacts.last_name, contacts.first_name";
        }
        
        $result = $db->query($query);
        $beans = array();
        
        while ($row = $db->fetchByAssoc($result)) {
            $bean = BeanFactory::newBean('Contacts');
            foreach ($row as $field => $value) {
                if (property_exists($bean, $field)) {
                    $bean->$field = $value;
                }
            }
            // Add the contact role as a custom property
            $bean->contact_role = $row['contact_role'];
            $beans[$bean->id] = $bean;
        }
        
        return $beans;
    }
}