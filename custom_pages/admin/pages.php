<?php
// 'View Pages' panel
// Process any POST requests first
if (!empty($_POST)) {
  try {
    // Save the data
    include('save.php');
  } catch(Exception $error) {
    // Error creating page
    echo $ui->error($i18n('ERROR_CREATE_PAGE'));
  }
}

// Display the page
// UI for title
$title = $ui->title($i18n('VIEW_PAGES'));

// Get the pages
$pages = $utils->getFiles($plugin->id());

// We will format the page information into a table
// Table header
$header = array($i18n('PAGE'), $i18n('DELETE'));

// Format the data
$rows = array();

foreach ($pages as $filename => $page) {
  $slug = basename($filename, '.xml');
  $rows[] = array(
    // Edit link
    $ui->link(null, 'edit=' . $slug),
    // Delete link
    $ui->link('delete', 'delete=' . $slug),
  );
}

// Build table to display the table
$table = $ui->table($header, $rows);

// Display the results
echo $title;
echo $table;