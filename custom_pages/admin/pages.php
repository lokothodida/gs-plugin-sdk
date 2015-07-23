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
// UI for header
$header = $ui->header(
  // Title
  $plugin->i18n('VIEW_PAGES'),
  // Navigation
  array(
    array(
      'label' => $plugin->i18n('CREATE'),
      'url' => $plugin->adminURL('create'),
    )
  )
);

// Get the pages
$pages = $utils->getFiles($plugin->id());

// We will format the page information into a table
// Table header
$thead = array($plugin->i18n('PAGE'), $plugin->i18n('DELETE'));

// Format the data
$rows = array();

foreach ($pages as $filename => $page) {
  $slug = basename($filename, '.xml');
  $rows[] = array(
    // Edit link
    $ui->link(null, $slug, $plugin->adminURL('edit=' . $slug)),
    // Delete link
    $ui->link('delete', 'x', $plugin->adminURL('delete=' . $slug)),
  );
}

// Build table to display the table
$table = $ui->table($thead, $rows);

// Display the results
echo $header;
echo $table;
