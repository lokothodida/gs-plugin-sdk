<?php

// Prevent this class from being included if another plugin
// has already loaded it
if (class_exists('GSUtils')) return;

class GSUtils {
  // == CONSTANTS ==
  const EXCEPTION_MKDIR = 'mkDirErr';
  const EXCEPTION_MKFILE = 'mkFileErr';

  // == PROPERTIES ==
  protected $options;
  protected $defaults = array(
    'htaccess' => 'Deny from all',
  );

  // == METHODS ==
  // Constructor
  public function __construct($options = array()) {
    $this->options = array_merge(array(
      'basepath' => GSDATAOTHERPATH,
    ), $options);
  }

  // Get a path (from the basepath)
  public function path($path = null) {
    if (strpos($path, $this->options['basepath']) !== 0) {
      $path = $this->options['basepath'] . $path;
    }
    return $path;
  }

  // Get file info
  public function fileinfo($file) {
    $file = $this->path($file);
    $info = pathinfo($file);
    $info = array_merge(array(
      'extension' => false,
    ), $info);

    return $info;
  }

  // Make a directory
  public function mkdir($path, $htaccess = true, $mode = 0755) {
    $path = $this->path($path);
    $mkdir = file_exists($path) || @mkdir($path, $mode, $recursive = true);
    if ($mkdir) {
      if ($htaccess === true) {
        $htaccess = $this->defaults['htaccess'];
      }

      if ($htaccess) {
        return $this->mkfile($path . '/.htaccess', $htaccess);
      } else {
        return $mkdir;
      }
    } else {
      throw new Exception(static::EXCEPTION_MKDIR);
    }
  }

  // Make a file
  public function mkfile($file, $data) {
    $file = $this->path($file);
    $info = $this->fileinfo($file);

    if ($info['extension'] == 'json') {
      $json = json_encode($data);
      $mkfile = @file_put_contents($file, $json);
    } elseif ($info['extension'] == 'xml') {
      // TODO
      $mkfile = false;
    } else {
      $mkfile = @file_put_contents($file, $data);
    }

    if ($mkfile) {
      return $mkfile;
    } else {
      throw new Exception(static::EXCEPTION_MKFILE);
    }
  }
}
