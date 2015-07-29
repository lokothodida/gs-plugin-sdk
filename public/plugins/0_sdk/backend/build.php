<?php

try {
  unset($_POST['submitted']);

  foreach ($_POST as $name => $path) {
    if (!empty($path)) {
      $basedir = GSPLUGINPATH . $name . '/';
      $dest = GSROOTPATH . '/build/' . $path . '/';
      $file = $name . '.php';

      $utils->copy(GSPLUGINPATH . $file, $dest . $file);
      $utils->copy($basedir, $dest . $name);
      // Copy the SDK
      //$utils->dump($basedir, $dest);
      echo $ui->success($plugin->i18n('BUILD_SUCC'));
    }
  }
} catch (Exception $error) {
  echo $ui->error($error->getMessage());
}
