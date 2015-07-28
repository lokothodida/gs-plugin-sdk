<?php

try {
  $slug = $exports['matches'][0];
  $file = 'pages/' . $slug . '.json';
  $page = $utils->rmfile($file);

  $msg = $plugin->i18n('PAGE_DELETE_SUCC');
  echo $ui->success($msg);
  
  // Show the pages
  include 'view.php';
} catch (Exception $error) {
  echo $ui->error('OHSHIT');
}