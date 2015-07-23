<?php
// Autoloader
// Loads classes in /lib directory (lowercase)
$file = dirname(__FILE__) . '/lib/' . strtolower($class) . '.php';
if (file_exists($file)) {
  include($file);
}