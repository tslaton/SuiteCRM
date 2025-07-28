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

<style>
.real-estate-settings-container {
    background: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 4px;
    padding: 30px;
    margin: 20px 0;
}

.real-estate-settings-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eeeeee;
}

.real-estate-settings-header h4 {
    margin: 0;
    color: #333333;
    font-size: 18px;
}

.real-estate-form-group {
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
}

.real-estate-form-label {
    width: 200px;
    padding-right: 20px;
    font-weight: 600;
    color: #333333;
}

.real-estate-form-control {
    flex: 1;
}

.real-estate-form-control input[type="number"] {
    padding: 5px 10px;
    border: 1px solid #cccccc;
    border-radius: 3px;
    width: 100px;
}

.real-estate-help-text {
    display: block;
    margin-top: 5px;
    color: #666666;
    font-size: 12px;
}

.real-estate-form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eeeeee;
}

.real-estate-form-actions .button {
    margin-right: 10px;
}
</style>

<div class="real-estate-settings-container">
    <form method="POST" action="index.php?module=Administration&action=real_estate_settings">
        <div class="real-estate-settings-header">
            <h4><?php echo $mod_strings['LBL_REAL_ESTATE_SETTINGS_TITLE']; ?></h4>
        </div>
        
        <div class="real-estate-form-group">
            <label class="real-estate-form-label">
                <?php echo $mod_strings['LBL_DEFAULT_COMMISSION_RATE']; ?>:
                <span class="required">*</span>
            </label>
            <div class="real-estate-form-control">
                <input type="number" 
                       name="default_commission_rate" 
                       id="default_commission_rate" 
                       value="<?php echo $defaultCommissionRate; ?>" 
                       min="0" 
                       max="100" 
                       step="0.01"> %
                <span class="real-estate-help-text">
                    <?php echo $mod_strings['LBL_DEFAULT_COMMISSION_RATE_HELP']; ?>
                </span>
            </div>
        </div>
        
        <div class="real-estate-form-actions">
            <input type="submit" name="save" value="<?php echo $app_strings['LBL_SAVE_BUTTON_LABEL']; ?>" class="button primary">
            <input type="button" name="cancel" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']; ?>" class="button" onclick="location.href='index.php?module=Administration&action=index'">
        </div>
    </form>
</div>