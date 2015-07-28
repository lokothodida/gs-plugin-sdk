<?php

// Edit a page
if (!empty($_POST)) {
  $type = 'edit';
  include 'savedata.php';
}

try {
  $slug = $exports['matches'][0];
  $file = 'pages/' . $slug . '.json';
  $page = $utils->getfile($file);
  $page['slug'] = $slug;

  $title = 'EDIT_PAGE';
  $action = '';

  // Show the form
  include 'pageform.php';
} catch (Exception $error) {
  echo $ui->error('OHSHIT');
}