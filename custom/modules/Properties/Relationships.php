<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class PropertiesRelationships
{
    /**
     * Get contacts with roles for a property
     */
    public static function getContactsWithRoles($property_id)
    {
        global $db;
        
        $contacts = array();
        $query = "SELECT c.*, pcr.contact_role 
                  FROM contacts c
                  INNER JOIN properties_contacts_roles pcr ON c.id = pcr.contact_id
                  WHERE pcr.property_id = '$property_id' 
                  AND pcr.deleted = 0 
                  AND c.deleted = 0
                  ORDER BY c.last_name, c.first_name";
        
        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            $contacts[] = $row;
        }
        
        return $contacts;
    }
    
    /**
     * Save contact role
     */
    public static function saveContactRole($property_id, $contact_id, $role)
    {
        global $db;
        
        // Check if relationship exists
        $query = "SELECT id FROM properties_contacts_roles 
                  WHERE property_id = '$property_id' 
                  AND contact_id = '$contact_id' 
                  AND deleted = 0";
        
        $result = $db->query($query);
        if ($row = $db->fetchByAssoc($result)) {
            // Update existing
            $update = "UPDATE properties_contacts_roles 
                       SET contact_role = '$role', date_modified = NOW() 
                       WHERE id = '{$row['id']}'";
            $db->query($update);
        } else {
            // Create new
            $id = create_guid();
            $insert = "INSERT INTO properties_contacts_roles 
                       (id, property_id, contact_id, contact_role, date_modified, deleted) 
                       VALUES ('$id', '$property_id', '$contact_id', '$role', NOW(), 0)";
            $db->query($insert);
        }
        
        return true;
    }
}