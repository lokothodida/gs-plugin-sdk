<?php

// Admin panel
// Initialize utilities and ui
$utils  = new GSUtils();
$ui     = new GSUI();
$plugin = $exports['plugin'];

// Initialize the data structure
if (!$utils->exists('mysettings.json')) {
  include 'initdata.php';
}

// Process changes
if (!empty($_POST)) {
  include 'savedata.php';
}

// Display the panel
include 'viewsettings.php';
