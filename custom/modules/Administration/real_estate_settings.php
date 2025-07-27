<?php
/**
 * Real Estate Settings Administration Panel
 * Manages system-wide real estate configuration including commission rates
 */

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/Administration/Administration.php');
require_once('modules/Configurator/Configurator.php');

global $mod_strings, $app_strings, $sugar_config, $current_user;

if (!is_admin($current_user)) {
    sugar_die($app_strings['ERR_NOT_ADMIN']);
}

$configurator = new Configurator();
$configurator->loadConfig();

// Handle form submission
if (!empty($_POST['save'])) {
    // Save commission rate setting
    if (isset($_POST['default_commission_rate'])) {
        $rate = floatval($_POST['default_commission_rate']);
        // Validate rate is reasonable (0-100%)
        if ($rate >= 0 && $rate <= 100) {
            $configurator->config['default_commission_rate'] = $rate;
        }
    }
    
    // Save other real estate settings here as needed
    
    $configurator->saveConfig();
    
    // Clear opcache if available
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    
    // Redirect to avoid form resubmission
    SugarApplication::redirect('index.php?module=Administration&action=real_estate_settings&saved=true');
}

// Display saved message
if (!empty($_REQUEST['saved'])) {
    echo '<div class="alert alert-success">' . $mod_strings['LBL_REAL_ESTATE_SETTINGS_SAVED'] . '</div>';
}

// Get current values
$defaultCommissionRate = isset($sugar_config['default_commission_rate']) ? $sugar_config['default_commission_rate'] : 2.5;

?>

<form method="POST" action="index.php?module=Administration&action=real_estate_settings">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="edit view">
        <tr>
            <th align="left" colspan="4">
                <h4><?php echo $mod_strings['LBL_REAL_ESTATE_SETTINGS_TITLE']; ?></h4>
            </th>
        </tr>
        
        <tr>
            <td scope="row" width="20%">
                <?php echo $mod_strings['LBL_DEFAULT_COMMISSION_RATE']; ?>:
                <span class="required">*</span>
            </td>
            <td width="30%">
                <input type="number" 
                       name="default_commission_rate" 
                       id="default_commission_rate" 
                       value="<?php echo $defaultCommissionRate; ?>" 
                       min="0" 
                       max="100" 
                       step="0.01"
                       style="width: 100px;"> %
                <br>
                <span class="help-block">
                    <?php echo $mod_strings['LBL_DEFAULT_COMMISSION_RATE_HELP']; ?>
                </span>
            </td>
            <td scope="row" width="20%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
        
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        
        <tr>
            <td colspan="4">
                <input type="submit" name="save" value="<?php echo $app_strings['LBL_SAVE_BUTTON_LABEL']; ?>" class="button primary">
                <input type="button" name="cancel" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']; ?>" class="button" onclick="location.href='index.php?module=Administration&action=index'">
            </td>
        </tr>
    </table>
</form>