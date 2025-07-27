<?php
/**
 * Clear All SuiteCRM Caches Script
 * Clears entire cache directory
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once('include/entryPoint.php');

global $current_user;

// Check if we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

// Set admin user
$current_user = BeanFactory::getBean('Users', '1');

echo "\n=== CLEARING ALL SUITECRM CACHES ===\n";

// Clear entire cache directory
if (is_dir('cache')) {
    echo "Deleting entire cache directory...\n";
    deleteDirectory('cache');
    echo "✓ Cache directory deleted\n";
    
    // Recreate the cache directory
    mkdir('cache', 0755);
    echo "✓ Cache directory recreated\n";
}

// Clear compiled extension files
echo "\nClearing compiled extension files...\n";
$extFiles = glob('custom/*/Ext/*/*.ext.php');
foreach ($extFiles as $extFile) {
    if (file_exists($extFile)) {
        unlink($extFile);
        echo "✓ Removed $extFile\n";
    }
}

// Also clear module-specific compiled extensions
$moduleExtFiles = glob('custom/modules/*/Ext/*/*.ext.php');
foreach ($moduleExtFiles as $extFile) {
    if (file_exists($extFile)) {
        unlink($extFile);
        echo "✓ Removed $extFile\n";
    }
}

// Clear OPcache if available
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✓ OPcache reset\n";
}

echo "\n=== ALL CACHES CLEARED SUCCESSFULLY! ===\n\n";

echo "Cache clearing complete. You may need to:\n";
echo "- Refresh your browser (Ctrl+F5 or Cmd+Shift+R)\n";
echo "- Log out and log back in to see all changes\n";

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