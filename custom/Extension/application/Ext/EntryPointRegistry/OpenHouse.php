<?php
/**
 * Open House Sign-In EntryPoint Registration
 * Provides public access to the open house sign-in form
 */

$entry_point_registry['OpenHouseSignIn'] = array(
    'file' => 'custom/EntryPoint/OpenHouseSignIn.php',
    'auth' => false, // No authentication required - this is a public form
);