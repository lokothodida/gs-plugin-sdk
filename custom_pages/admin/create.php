<?php

// 'Create Page' panel
// UI for title
$title = $ui->title($plugin->i18n('CREATE_PAGE'));

// Set the default data
$page = array('title' => null, 'content' => null);

// Get the form
$action = $plugin->adminURL(); // form submits to main page
include('form.php');

// Output UI
echo $title;
echo $form;