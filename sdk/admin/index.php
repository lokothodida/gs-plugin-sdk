<?php

// Process any forms
if (!empty($_POST)) {
  try {
    static::createPlugin($_POST);
    echo GSPluginUI::success(static::i18n('CREATE_PLUGIN_SUCC'));
  } catch (Exception $error) {
    echo GSPluginUI::error($error->getMessage());
    //echo GSPluginUI::error(static::i18n('CREATE_PLUGIN_ERROR'));
  }
}
    


$dir = dirname(dirname(__DIR__));
$plugins = glob($dir . '/*/');
$sdkPlugins = array();
$rows = array();

foreach ($plugins as $plugin) {
  if (file_exists($plugin . 'dev.php')) {
    $sdkPlugins[] = basename($plugin);

    $id = basename($plugin);

    $rows[] = array(
      '<a href="' . static::adminUrl('edit/' . $id) . '">' . $id . '</a>',
      '',
    );
  }
}

$header = array(static::i18n('PLUGIN'), '');

echo GSPluginUI::table($header, $rows);