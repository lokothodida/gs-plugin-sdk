<?php

// Load the correct libraries
include('lib/gspluginutils.php');
include('lib/gspluginui.php');
include('lib/gsplugin.php');
include('lib/plugin.php');


// Get the name of the plugin (the last declared class)
$classes = get_declared_classes();
$plugin = end($classes);

// Initialize the plugin with the correct information
include('info.php');
$plugin::initialize($info);