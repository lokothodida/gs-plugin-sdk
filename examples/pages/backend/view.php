<?php

// Process any requests
if (!empty($_POST)) {
  include 'savedata.php';
}

// Get the pages
$pages = $utils->getfiles('pages/*.json');

// Display them in a table
$title = $ui->title($plugin->i18n('VIEW_PAGES'));

// 2 columns
$thead = array($plugin->i18n('PAGE'), '');

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

echo $title;
echo $table
