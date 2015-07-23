<?php

class GSUtils {
  // == PROPERTIES ==
  private $defaults = array(
    'htaccess' => 'Deny from all',
    'xml' => '<?xml version="1.0" encoding="UTF-8"?><channel/>',
  );

  // == PUBLIC METHODS ==
  public function mkdir($dir, $mode = 0755, $recursive = false, $htaccess = true) {
    $directory = GSDATAOTHERPATH . '/'. $dir . '/';

    if ($this->dirExists($dir)) {
      return false;
    }

    // Create the directory
    $mkdir = @mkdir($directory, $mode, $recursive);
    if (!$mkdir) {
      throw new Exception(static::EXCEPTION_MKDIR);
    } elseif($htaccess !== false) {
      // Create the htaccess file
      // First get the default contents if a boolean was passed
      if ($htaccess === true) {
        $htaccess = static::$defaults['htaccess'];
      }

      //static::mkFile($directory . '.htaccess', $htaccess);

      return true;
    }
  }
  
  public function dirExists($dir) {
    $directory = GSDATAOTHERPATH . '/' . $dir;
    return (bool) !is_file($directory) && @file_exists($directory);
  }
  
  public function currentUrl() {
    $https    = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $protocol = ($https) ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  }
  
  public function adminURL() {
    return '';
  }

  public function currentAdminUrl() {
    $current = $_SERVER['REQUEST_URI'];
    $start = strpos($current, 'load.php');
    return substr($current, $start);
  }
}

?>