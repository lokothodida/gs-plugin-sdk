<?php

// Include library
include('lib/gsplugin.php');

// Static plugin information
$plugin = new GSPlugin(array(
  'id' => $id,
  'author' => 'Lawrence Okoth-Odida',
  'website' => '',
  'version' => '0.1',
  'lang' => 'en_US',
  'tab' => 'pages',
));

// Admin
$plugin->sidebar($plugin->i18n('PLUGIN_SIDEBAR'));
$plugin->admin('admin/index.php');

// Index (front page)
$plugin->index('index/index.php');

// Initialize
$plugin->init();