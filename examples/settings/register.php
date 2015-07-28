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
  'tab'      => 'settings',
  'lang'     => 'en_US',
));

// Set the sidebar link
$plugin->sidebar($plugin->i18n('PLUGIN_SIDEBAR'));

// Setting the admin panel
$plugin->admin(null, 'backend/index.php', array('plugin' => $plugin));

// Initialization
$plugin->init();
