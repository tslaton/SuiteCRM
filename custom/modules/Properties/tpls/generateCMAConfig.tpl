<div class="moduleTitle">
    <h2>Generate Comparative Market Analysis</h2>
    <div class="clear"></div>
</div>

<form id="cmaConfigForm" method="post" action="index.php">
    <input type="hidden" name="module" value="Properties">
    <input type="hidden" name="action" value="generatecma">
    <input type="hidden" name="record" value="{$property->id}">
    <input type="hidden" name="generate" value="1">
    
    <div class="edit view edit508">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
            <tr>
                <th colspan="4" align="left">
                    <h4 class="header-4">CMA Configuration Parameters</h4>
                </th>
            </tr>
            
            <tr>
                <td scope="row" width="15%">
                    <label for="price_range">Price Range (+/- %):</label>
                </td>
                <td width="35%">
                    <input type="number" name="price_range" id="price_range" value="{$price_range}" min="5" max="50" step="5" size="10">
                    <span class="help-block">Percentage variance for comparable property prices</span>
                </td>
                <td scope="row" width="15%">
                    <label for="sqft_range">Square Footage Range (+/- %):</label>
                </td>
                <td width="35%">
                    <input type="number" name="sqft_range" id="sqft_range" value="{$sqft_range}" min="10" max="50" step="5" size="10">
                    <span class="help-block">Percentage variance for square footage</span>
                </td>
            </tr>
            
            <tr>
                <td scope="row">
                    <label for="bedroom_range">Bedroom Range (+/-):</label>
                </td>
                <td>
                    <input type="number" name="bedroom_range" id="bedroom_range" value="{$bedroom_range}" min="0" max="3" step="1" size="10">
                    <span class="help-block">Number of bedrooms variance</span>
                </td>
                <td scope="row">
                    <label for="bathroom_range">Bathroom Range (+/-):</label>
                </td>
                <td>
                    <input type="number" name="bathroom_range" id="bathroom_range" value="{$bathroom_range}" min="0" max="2" step="0.5" size="10">
                    <span class="help-block">Number of bathrooms variance</span>
                </td>
            </tr>
            
            <tr>
                <td scope="row">
                    <label for="max_comparables">Maximum Comparables:</label>
                </td>
                <td>
                    <select name="max_comparables" id="max_comparables">
                        <option value="3" {if $max_comparables == 3}selected{/if}>3</option>
                        <option value="4" {if $max_comparables == 4}selected{/if}>4</option>
                        <option value="5" {if $max_comparables == 5}selected{/if}>5</option>
                        <option value="6" {if $max_comparables == 6}selected{/if}>6</option>
                    </select>
                    <span class="help-block">Number of comparable properties to include</span>
                </td>
                <td scope="row">
                    <label for="date_range_months">Date Range (months):</label>
                </td>
                <td>
                    <select name="date_range_months" id="date_range_months">
                        <option value="3" {if $date_range_months == 3}selected{/if}>Last 3 months</option>
                        <option value="6" {if $date_range_months == 6}selected{/if}>Last 6 months</option>
                        <option value="9" {if $date_range_months == 9}selected{/if}>Last 9 months</option>
                        <option value="12" {if $date_range_months == 12}selected{/if}>Last 12 months</option>
                    </select>
                    <span class="help-block">Time period for comparable sales</span>
                </td>
            </tr>
            
            <tr>
                <td scope="row">
                    <label for="sold_only">Sold Properties Only:</label>
                </td>
                <td colspan="3">
                    <input type="checkbox" name="sold_only" id="sold_only" value="1" {if $sold_only}checked{/if}>
                    <span class="help-block">Include only sold properties (recommended for accurate analysis)</span>
                </td>
            </tr>
        </table>
        
        <div class="buttons" style="margin-top: 20px;">
            <input type="submit" value="Generate CMA" class="button primary">
            <input type="button" value="Cancel" class="button" onclick="window.location.href='index.php?module=Properties&action=DetailView&record={$property->id}';">
        </div>
    </div>
</form>

<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('#cmaConfigForm').on('submit', function(e) {
        // Show loading message
        ajaxStatus.showStatus('Generating CMA Report...');
    });
});
{/literal}
</script>