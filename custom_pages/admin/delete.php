<?php

// Process deletion
try {
  // Get slug from url parameter asked for in route
  list ($slug) = $params;

  // Delete the file
  $utils->rmFile($slug);

  // Success
  echo $ui->success($i18n('SUCC_DELETE_PAGE'));
} catch (Exception $error) {
  // Error
  echo $ui->error($i18n('ERROR_DELETE_PAGE'));
}

// Show main page
include('pages.php');