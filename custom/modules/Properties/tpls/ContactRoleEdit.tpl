{literal}
<script type="text/javascript">
function saveContactRole(contactId, propertyId) {
    var roleSelect = document.getElementById('contact_role_' + contactId);
    var roleValue = roleSelect.value;
    
    // AJAX call to save the role
    $.ajax({
        url: 'index.php?module=Properties&action=SaveContactRole',
        method: 'POST',
        data: {
            contact_id: contactId,
            property_id: propertyId,
            contact_role: roleValue
        },
        success: function(response) {
            alert('Role updated successfully');
        },
        error: function() {
            alert('Error updating role');
        }
    });
}
</script>
{/literal}

<select id="contact_role_{$fields.id.value}" name="contact_role_{$fields.id.value}" onchange="saveContactRole('{$fields.id.value}', '{$bean->id}')">
    {html_options options=$app_list_strings.property_contact_role_list selected=$fields.contact_role.value}
</select>