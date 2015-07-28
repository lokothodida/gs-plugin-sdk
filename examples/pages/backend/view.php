<?php

// Process any requests
if (!empty($_POST)) {
  include 'savedata.php';
}

// Get the pages
$pages = $utils->getfiles('pages/*.json');

// Page header
$title = $ui->header(
  // Title
  $plugin->i18n('VIEW_PAGES'),

  // Quicknav link to 'create'
  array('label' => $plugin->i18n('CREATE'), 'href' => '&create')
);

// Display pages in a table
// 2 columns in the header
$thead = array($plugin->i18n('PAGE'), '');

// Build the rows
$rows = array();

foreach ($pages as $filename => $page) {
  $slug = basename($filename, '.json');
  $rows[] = array(
    // Edit button
    $ui->a(array('href' => '&edit=' . $slug), $page['title']),
    // Delete button
    $ui->a(array('href' => '&delete=' . $slug), 'x'),
  );
}

$table = $ui->table(array('header' => $thead, 'rows' => $rows));

// Output the header and table
echo $header;
echo $table;
