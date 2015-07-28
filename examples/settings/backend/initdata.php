<?php

// Initialize the data structure
try {
  $utils->putfile('mysettings.json', array(
    'setting1' => 'My value',
    'setting2' => 'Another value',
    'setting3' => 'Yet another value',
  ));
} catch (Exception $error) {
  $msg = $plugin->i18n('SETTINGS_INIT_ERROR');
  $ui->error($msg);
}
