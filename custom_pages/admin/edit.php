<?php

// 'Edit Page' panel
// First process any POST requests
if (!empty($_POST)) {
  try {
    // Process the data
    include('save.php');

    // Success
    echo $ui->error($i18n('SUCC_PAGE_SAVE'));
  } catch (Exception $error) {
    // Error
    echo $ui->error($i18n('ERROR_PAGE_SAVE'));
  }
}

// Display the form
// UI for title
$title = $ui->title(
  // Title
  $i18n('CREATE_PAGE'),
  // Navigation
  $nav = array(
    //'label' => 
  )
);

// Get the form
$action = ''; // form submits to same page
include('form.php');

// Output UI
echo $title;
echo $form;
