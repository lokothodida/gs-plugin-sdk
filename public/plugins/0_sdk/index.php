<?php

$plugin = new GSPlugin(array(
  'id'       => $id,
  'version'  => '1.0',
  'author'   => 'You',
  'website'  => 'http://yourwebsite.com',
  'tab'      => 'plugins',
  'lang'     => 'en_US',
));

// Set the sidebar link
$plugin->sidebar($plugin->i18n('PLUGIN_SIDEBAR'));

// Exports
$ui = new GSUI();
$utils = new GSUtils(array('pluginid' => $plugin->id()));
$exports = array(
  'plugin' => $plugin,
  'ui' => $ui,
  'utils' => $utils,
);

// Setting the admin panel
$plugin->admin(null, 'backend/index.php', $exports);

// Initialization
$plugin->init();
