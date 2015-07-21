<?php

$plugin = str_replace('edit/', '', $_GET['p']);

if (!empty($_POST)) {
  try {
    static::updatePluginInfo($_POST);
    echo GSPluginUI::success(static::i18n('PLUGIN_UPDATE_SUCCESS'));
    
    // Ensure URL is renamed if the plugin was
    $plugin = $_POST['id'];
    echo GSPluginUI::renameUrl(static::adminUrl('edit/' . $plugin));
  } catch (Exception $error) {
    echo GSPluginUI::error($error->getMessage());
  }
}

$id = $plugin;
include(GSPLUGINPATH . '/'. $plugin . '/info.php');

$info['id'] = $plugin;
$info['className'] = static::getExternalPluginClassName($plugin);
$url = '';
include('form.php');