<?php
/**
 * Run All SuiteCRM Repairs Script
 * Executes all four repair operations to ensure customizations are properly loaded
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

// Set execution time limit to prevent timeout
set_time_limit(0);

require_once('include/entryPoint.php');
require_once('modules/Administration/QuickRepairAndRebuild.php');
require_once('modules/Administration/Administration.php');

global $current_user, $mod_strings, $dictionary;

// Check if we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

// Set admin user
$current_user = BeanFactory::getBean('Users', '1');

echo "\n=== RUNNING ALL SUITECRM REPAIRS ===\n";
echo "This will execute:\n";
echo "1. Quick Repair and Rebuild\n";
echo "2. Rebuild Extensions\n";
echo "3. Rebuild Relationships\n";
echo "4. Clear Additional Cache\n\n";

// 1. Quick Repair and Rebuild
echo "Step 1/4: Running Quick Repair and Rebuild...\n";
$randc = new RepairAndClear();
$randc->repairAndClearAll(['clearAll'], ['Accounts', 'Contacts', 'Opportunities', 'Properties', 'MockMLS'], true, false);
echo "✓ Quick Repair and Rebuild completed\n\n";

// 2. Rebuild Extensions
echo "Step 2/4: Rebuilding Extensions...\n";
require_once('ModuleInstall/ModuleInstaller.php');
$mi = new ModuleInstaller();
$mi->rebuild_all(true);
echo "✓ Extensions rebuilt\n\n";

// 3. Rebuild Relationships
echo "Step 3/4: Rebuilding Relationships...\n";
require_once('modules/Administration/RebuildRelationship.php');
// The rebuild relationship functionality is in the global scope of the file
// We need to include it in a way that executes the code
$_REQUEST['silent'] = true;
include('modules/Administration/RebuildRelationship.php');
echo "✓ Relationships rebuilt\n\n";

// 4. Clear Additional Cache
echo "Step 4/4: Clearing Additional Cache...\n";

// Clear various cache directories
$cacheDirs = [
    'cache/api',
    'cache/blowfish',
    'cache/csv',
    'cache/dashlets',
    'cache/diagnostic',
    'cache/dynamic_fields',
    'cache/feeds',
    'cache/import',
    'cache/include',
    'cache/javascript',
    'cache/jsLanguage',
    'cache/modules',
    'cache/pdf',
    'cache/smarty',
    'cache/themes',
    'cache/xml',
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        deleteDirectory($dir);
        echo "  - Cleared $dir\n";
    }
}

// Clear specific cache files
$cacheFiles = [
    'cache/api/metadata.php',
    'cache/file_map.php',
    'cache/class_map.php',
    'cache/smarty_compiled',
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        if (is_dir($file)) {
            deleteDirectory($file);
        } else {
            unlink($file);
        }
        echo "  - Removed $file\n";
    }
}

// Clear theme cache
if (is_dir('themes')) {
    $themes = scandir('themes');
    foreach ($themes as $theme) {
        if ($theme != '.' && $theme != '..' && is_dir("themes/$theme")) {
            $themeCache = "themes/$theme/css";
            if (is_dir($themeCache)) {
                $cssFiles = glob("$themeCache/*.css");
                foreach ($cssFiles as $cssFile) {
                    if (strpos($cssFile, 'style.css') === false && strpos($cssFile, 'color-palette.css') === false) {
                        unlink($cssFile);
                    }
                }
                echo "  - Cleared theme cache for $theme\n";
            }
        }
    }
}

echo "✓ Additional cache cleared\n\n";

// Final steps
echo "Running final cleanup...\n";

// Rebuild the autoloader
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "  - OPcache reset\n";
}

// Clear Sugar cache
$GLOBALS['sugar_config']['cache_expire_timeout'] = 0;
require_once('include/SugarCache/SugarCache.php');
SugarCache::cleanOpcodes();
echo "  - Sugar cache cleaned\n";

echo "\n=== ALL REPAIRS COMPLETED SUCCESSFULLY! ===\n\n";

echo "Please check the following:\n";
echo "1. Navigate to the main page - you should see the Real Estate menu items\n";
echo "2. Properties module should be accessible from the navigation\n";
echo "3. Open a Property detail view - the 'Generate CMA' button should appear\n";
echo "4. The menu should show Properties and Transactions in the Real Estate section\n";

echo "\nIf issues persist, try:\n";
echo "- Logging out and back in\n";
echo "- Clearing your browser cache\n";
echo "- Testing in an incognito/private browser window\n";

/**
 * Recursively delete a directory
 */
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = "$dir/$file";
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}