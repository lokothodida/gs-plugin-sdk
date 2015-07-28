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
try {
  $settings = $utils->getfile('mysettings.json');
  include 'viewsettings.php';
} catch (Exception $error) {
  $msg = $plugin->i18n('SETTINGS_VIEW_ERROR');
  echo $ui->error($msg);
}
