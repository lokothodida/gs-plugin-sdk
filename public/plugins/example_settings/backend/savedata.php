<?php

// Format the data
$settings = array(
  // Slugify Setting 1
  'setting1' => $utils->slug($_POST['setting1']),

  // Sanitize Setting 2
  'setting2' => $utils->clean($_POST['setting2']),

  // Just leave Setting 3 as-is
  'setting3' => $_POST['setting3'],
);

// Save the data
try {
  $utils->putfile($file, $settings);
  $msg = $plugin->i18n('SETTINGS_SAVE_SUCC');
  echo $ui->success($msg);
} catch (Exception $error) {
  $msg = $plugin->i18n('SETTINGS_SAVE_ERR');
  echo $ui->error($msg);
}
