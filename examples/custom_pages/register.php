<?php

// TODO: Documentation
include 'lib/gsplugin.php';
include 'lib/gsutils.php';
include 'lib/gsui.php';

// Registration
$plugin = new GSPlugin(array(
  'id'       => $id,
  'version'  => '1.0',
  'author'   => 'You',
  'website'  => 'http://yourwebsite.com',
  'tab'      => 'pages',
  'lang'     => 'en_US',
));

// Utilities
$ui = new GSUI();
$utils = new GSUtils(array(
  'pluginid' => $plugin->id(),
));

// Set the sidebar link
$plugin->sidebar($plugin->i18n('PLUGIN_SIDEBAR'));

// Cache some variables we want available in the admin panel
$exports = array(
  'plugin' => $plugin,
  'ui' => $ui,
  'utils' => $utils,
);

// Setting up the admin panel
$plugin->admin('',              'backend/view.php',   $exports);
$plugin->admin('create',        'backend/create.php', $exports);
$plugin->admin('/edit=(.*)/',   'backend/edit.php',   $exports);
$plugin->admin('/delete=(.*)/', 'backend/delete.php', $exports);

// Setting up the front page
$plugin->index('pages',         'frontend/pages.php', $exports);
$plugin->index('/pages\/(.*)/', 'frontend/page.php',  $exports);

// Initialization
$plugin->init();
