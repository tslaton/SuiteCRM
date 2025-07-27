<div class="subpanel-filter-container" style="margin: 10px 0;">
    <label for="contact_role_filter">{$MOD.LBL_FILTER_BY_ROLE}: </label>
    <select id="contact_role_filter" onchange="filterContactsByRole(this.value)">
        <option value="">{$APP.LBL_ALL}</option>
        {html_options options=$APP_LIST.property_contact_role_list}
    </select>
</div>

{literal}
<script type="text/javascript">
function filterContactsByRole(role) {
    // Get the subpanel ID
    var subpanelId = 'whole_subpanel_properties_contacts_roles';
    var subpanel = document.getElementById(subpanelId);
    
    if (!subpanel) return;
    
    // Find all rows in the subpanel
    var rows = subpanel.getElementsByClassName('oddListRowS1');
    var evenRows = subpanel.getElementsByClassName('evenListRowS1');
    
    // Combine both odd and even rows
    var allRows = [];
    for (var i = 0; i < rows.length; i++) {
        allRows.push(rows[i]);
    }
    for (var i = 0; i < evenRows.length; i++) {
        allRows.push(evenRows[i]);
    }
    
    // Show/hide rows based on role filter
    for (var i = 0; i < allRows.length; i++) {
        if (role === '') {
            allRows[i].style.display = '';
        } else {
            // This would need to check the actual role value
            // For now, just show all
            allRows[i].style.display = '';
        }
    }
}
</script>
{/literal}