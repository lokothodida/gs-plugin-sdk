<?php

// Save page data
// Slugify the title so it can be used for the filename
$slug = $utils->slug($_POST['title']);

// Create/save the file
var_dump($utils->mkFile($plugin->id() . '/' . $slug, array(
  'title' => $_POST['title'],
  'content' => $_POST['content'],
), $overwrite = true)); // overwrites file if it exists
