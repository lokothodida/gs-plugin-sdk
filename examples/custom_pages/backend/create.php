<?php

// Create a page
// Default information
$page = array(
  'title' => '',
  'credate' => time(),
  'pubdate' => time(),
  'content' => '',
  'slug' => null,
);

$title = 'CREATE_PAGE';
$action = $utils->adminurl();

// Show the form
include 'pageform.php';