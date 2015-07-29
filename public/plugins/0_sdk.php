<?php

call_user_func(create_function('$id', '
  // Load SDK library
  $libs = array("plugin", "utils", "ui");

  foreach ($libs as $lib) {
    $file = GSPLUGINPATH . $id . "/lib/gs" . $lib . ".php";

    if (file_exists($file)) {
      include $file;
    }
  }

  // Run plugin
  include $id . "/index.php";
'), basename(__FILE__, ".php"));
