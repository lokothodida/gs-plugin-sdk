<?php

// Prevent this class from being included if another plugin
// has already loaded it
if (class_exists('GSUtils')) return;

class GSUtils {
  // == CONSTANTS ==
  const SDK_VERSION = 0.1;
  const EXCEPTION_MKDIR = 'mkDirErr';
  const EXCEPTION_RMDIR = 'rmDirErr';
  const EXCEPTION_MKFILE = 'mkFileErr';
  const EXCEPTION_PUTFILE = 'putFileErr';
  const EXCEPTION_GETFILE = 'getFileErr';
  const EXCEPTION_RMFILE = 'rmFileErr';
  const EXCEPTION_MOVE = 'moveErr';
  const EXCEPTION_MOVE_SOURCE = 'moveSrcErr';
  const EXCEPTION_MOVE_DEST = 'moveDestErr';

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
      'adminurl' => $GLOBALS['SITEURL'] . $GLOBALS['GSADMIN'] . '/',
      'pluginid' => null,
      'siteurl' => $GLOBALS['SITEURL'],
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
        return $this->putfile($path . '/.htaccess', $htaccess);
      } else {
        return $mkdir;
      }
    } else {
      throw new Exception(static::EXCEPTION_MKDIR);
    }
  }

  // List files in a directory
  public function scandir($path, $fullpath = true, $parents = false) {
    $path = $this->path($path);
    $files = scandir($path);

    if (!$parents) {
      $files = array_diff($files, array('.', '..'));
    }

    if ($fullpath) {
      foreach ($files as $i => $file) {
        $files[$i] = $path . '/' . $file;
      }
    }

    return $files;
  }

  // Remove a directory
  public function rmdir($path, $force = false) {
    $path = $this->path($path);
    if ($force) {
      $files = $this->scandir($path);

      foreach ($files as $file) {

        if (is_dir($file)) {
          $rmdir = $this->rmdir($file, true);
        } else {
          $rmdir = @unlink($file);
        }

        if (!$rmdir) {
          throw new Exception(static::EXCEPTION_RMDIR);
        }
      }

    }

    $rmdir = @rmdir($path);

    if ($rmdir) {
      return $rmdir;
    } else {
      throw new Exception(static::EXCEPTION_RMDIR);
    }
  }

  // Make a file
  public function mkfile($file, $data) {
    if ($this->exists($file)) {
      throw new Exception(static::EXCEPTION_MKFILE);
    } else {
      return $this->putfile($file, $data);
    }
  }

  // Put contents in a file
  public function putfile($file, $data) {
    $file = $this->path($file);
    $info = $this->fileinfo($file);

    if ($info['extension'] == 'json') {
      $json = json_encode($data);
      $putfile = @file_put_contents($file, $json);
    } elseif ($info['extension'] == 'xml') {
      // TODO
      $putfile = false;
    } else {
      $putfile = @file_put_contents($file, $data);
    }

    if ($putfile) {
      return $putfile;
    } else {
      throw new Exception(static::EXCEPTION_PUTFILE);
    }
  }

  // Get file contents
  public function getfile($file) {
    $file = $this->path($file);
    $info = $this->fileinfo($file);


    if ($info['extension'] == 'json') {
      $contents = @file_get_contents($file);

      if ($contents !== false) {
        $data = @json_decode($contents);

        if ($data) {
          $data = (array) $data;
        }
      } else {
        $data = false;
      }
    } elseif ($info['extension'] == 'xml') {
      // TODO
      // Currently doesn't read xml files
      $data = false;
    } else {
      $data = @file_get_contents($file);
    }

    if ($data !== false) {
      return $data;
    } else {
      throw new Exception(static::EXCEPTION_GETFILE);
    }
  }

  // Get a list of files
  public function getfiles($query) {
    $files = array();

    if (is_string($query)) {
      $path = $this->path($query);
      $list = glob($path);
    } elseif (is_array($query)) {
      $list = $query;
    }

    foreach ($list as $file) {
      $file = $this->path($file);
      $files[$file] = $this->getfile($file);
    }

    return $files;
  }

  // Remove a file
  public function rmfile($file) {
    $file = $this->path($file);
    $rmfile = @unlink($file);
    if ($rmfile) {
      return $rmfile;
    } else {
      throw new Exception(static::EXCEPTION_RMFILE);
    }
  }

  // Move a directory/file
  public function move($source, $dest) {
    $source = $this->path($source);
    $dest = $this->path($dest);
    $sourceExists = $this->exists($source);
    $destExists = $this->exists($dest);

    if (!$sourceExists) {
      throw new Exception(static::EXCEPTION_MOVE_SOURCE);
    } elseif ($destExists) {
      throw new Exception(static::EXCEPTION_MOVE_DEST);
    } else {
      $move = @rename($source, $dest);
      if (!$move) {
        throw new Exception(static::EXCEPTION_MOVE);
      } else {
        return $move;
      }
    }
  }

  // Copy a file/directory
  public function copy($source, $dest, $permission = 0755) {
    $source = $this->path($source);
    $dest = $this->path($dest);

    if (is_file($source)) {
      $copy = @copy($source, $dest);
      if (!$copy) {
        throw new Exception(static::EXCEPTION_COPY);
      }
    } else {

      $files = $this->scandir($source);
      foreach ($files as $file) {
        $f = '/' . basename($file);

        if (!is_dir($file)) {
          $copy = @copy($source . $f, $dest . $f);
          if (!$copy) {
            throw new Exception(static::EXCEPTION_COPY);
          }
        } else {
          $mkdir = $this->mkdir($source . $f, $dest . $f);
          $copy = $mkdir && $this->copy($source . $f, $dest . $f, $permission);
        }
      }
    }

    return $copy;
  }

  // File/folder exists
  public function exists($resource) {
    return file_exists($this->path($resource));
  }

  // Print
  public function dump() {
    $args = func_get_args();
    echo '<pre><code>';
    call_user_func_array('var_dump', $args);
    echo '</code></pre>';
  }

  // Slug
  public function slug($string, $default = 'temp') {
    if (function_exists('prepareSlug')) {
      // GS 3.4
      return prepareSlug($string, $default);
    } else {
      $slug = $string;
      // Need to implement
      // Copied from slugging done on 3.4
      //$slug = truncate($slug,GSFILENAMEMAX);
      $slug = $this->translit($slug);
      $slug = to7bit($slug, "UTF-8");
      $slug = clean_url($slug); //old way @todo what does that mean ?
      if(trim($slug) == '' && $default) return $default;
      return $slug;
    }
  }

  // Transliteration
  public function translit() {
    $args = func_get_args();
    if (count($args) === 0) {
      return i18n_r('TRANSLITERATION', null);
    } else {
      $string = $args[0];

      if (function_exists('doTransliteration')) {
        // GS 3.4
        $string = doTransliteration($string);
      } else {
        $translit = $this->translit();

        if (is_array($translit) && count($translit > 0)) {
          $string = str_replace(array_keys($translit), array_values($translit), $string);
        }
      }

      return $string;
    }
  }

  // Clean strings
  public function clean($string) {
    return cl($string);
  }

  // Admin url
  public function adminurl($path = null) {
    $url = $this->options['adminurl'];

    if ($this->options['pluginid']) {
      $url = $url . 'load.php?id=' . $this->options['pluginid'];
    }

    if ($path &&  $this->options['pluginid']) {
      $url = $url . '&';
    }

    return $url . $path;
  }

  public function siteurl($path = null) {
    return $this->options['siteurl'] . $path;
  }
}
