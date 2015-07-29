<?php

try {
  unset($_POST['submitted']);

  foreach ($_POST as $name => $path) {
    $basedir = GSPLUGINPATH . $name . '/';
    $sdkconfig = $basedir . 'sdkconfig.json';

    if (empty($path) && $utils->exists($sdkconfig)) {
      $config = $utils->getfile($sdkconfig);

      if (isset($config['buildpath'])) {
        $path = $config['buildpath'];
      }
    }

    if (!empty($path)) {
      $dest = GSROOTPATH . '/build/' . $path . '/';
      $file = $name . '.php';

      $utils->copy(GSPLUGINPATH . $file, $dest . $file);
      $utils->copy($basedir, $dest . $name);

      // Copy the SDK
      $sdk = array('gsutils', 'gsplugin', 'gsui');
      foreach ($sdk as $item) {
        $file = '/lib/' . $item . '.php';
        $utils->copy($plugin->path() . $file, $dest . $name . $file);
      }

      echo $ui->success($plugin->i18n('BUILD_SUCC'));
    }
  }
} catch (Exception $error) {
  echo $ui->error($error->getMessage());
}
