<?php

try {
  $slug = $exports['matches'][0];
  $file = 'pages/' . $slug . '.json';
  $page = $utils->rmfile($file);

  $msg = $plugin->i18n('PAGE_DEL_SUCC');
  echo $ui->success($msg);
  
  // Show the pages
  include 'view.php';
} catch (Exception $error) {
  $msg = $plugin->i18n('PAGE_DEL_ERR');
  echo $ui->error($msg);
  include 'view.php';
}