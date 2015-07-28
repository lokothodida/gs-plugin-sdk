<?php

// Initialization
include 'init.php';

// Process any requests
if (!empty($_POST)) {
  include 'savedata.php';
}

// Get the pages
$pages = $utils->getfiles('pages/*.json');


// Page header
$header = $ui->header($plugin->i18n('VIEW_PAGES'), array(
  // Quicknav link to 'create'
  array('label' => $plugin->i18n('CREATE'), 'href' => $utils->adminurl('create'))
));

// Display pages in a table
// 2 columns in the header
$thead = array($plugin->i18n('PAGE'), '');

// Build the rows
$rows = array();

foreach ($pages as $filename => $page) {
  $slug = basename($filename, '.json');
  $rows[] = array(
    // Edit button
    $ui->anchor(null, array('href' => $utils->adminurl('edit=' . $slug), 'label' => $page['title'])),
    // Delete button
    $ui->anchor('cancel', array('href' => $utils->adminurl('delete=' . $slug), 'label' => 'x')),
  );
}

$table = $ui->table(array('class' => 'mypages'), $thead, $rows);

// Output the header and table
echo $header;
echo $table;
?>
<script>
  // A little Javascript/jQuery to give us a prompt for deletion
  $('.mypages .cancel').click(function() {
    return confirm('<?php echo $plugin->i18n('PAGE_DEL_SURE'); ?>');
  });
</script>
