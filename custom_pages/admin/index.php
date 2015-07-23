<?php

// Administration Panel
// Initialize
try {
  // Create the data directory
  $mkdir = $utils->mkdir($plugin->id());
  
  // Route the pages
  $route->add('', 'admin/pages.php');
  $route->add('create', 'admin/create.php');
  $route->add('/edit=[a-z0-9]*/', 'admin/edit.php');
  $route->add('/delete=[a-z0-9]*/', 'admin/delete.php');
  $route->add(false, 'admin/error.php');
} catch(Exception $error) {
  // Catastrophic error
  echo $ui->error($i18n('ERROR_INITIALIZATION'));
}
