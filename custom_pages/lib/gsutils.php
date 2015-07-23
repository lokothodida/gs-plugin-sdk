<?php

class GSUtils {
  // == PROPERTIES ==
  private $options = array();
  private $urls = array();
  private $defaults = array(
    'htaccess' => 'Deny from all',
    'xml' => '<?xml version="1.0" encoding="UTF-8"?><channel/>',
  );

  // == PUBLIC METHODS ==
  // Constructor
  public function __construct($options) {
    $this->options = $options;
    $this->urls['admin'] = $GLOBALS['SITEURL'] . $GLOBALS['GSADMIN'];
  }

  // Folders
  public function mkdir($dir, $mode = 0755, $recursive = false, $htaccess = true) {
    $directory = GSDATAOTHERPATH . '/'. $dir . '/';

    if ($this->dirExists($dir)) {
      return false;
    }

    // Create the directory
    $mkdir = @mkdir($directory, $mode, $recursive);
    if (!$mkdir) {
      throw new Exception($this->EXCEPTION_MKDIR);
    } elseif($htaccess !== false) {
      // Create the htaccess file
      // First get the default contents if a boolean was passed
      if ($htaccess === true) {
        $htaccess = $this->defaults['htaccess'];
      }

      //$this->mkFile($directory . '.htaccess', $htaccess);

      return true;
    }
  }

  public function copydir() {
  }

  public function mvdir() {
  }

  public function rmdir() {
  }

  public function getdir() {
  }

  public function isdir() {
  }

  public function dirExists($dir) {
    $directory = GSDATAOTHERPATH . '/' . $dir;
    return (bool) !is_file($directory) && @file_exists($directory);
  }

  // Files
  public function mkfile($file, $data, $clean = true) {
    $fileInfo = pathinfo($file);

    // Default to an xml file
    if (!isset($fileInfo['extension'])) {
      $file .= '.xml';
      $fileInfo = pathinfo($file);
    }

    $file = GSDATAOTHERPATH . '/' . $file;

    if (is_array($data) && $fileInfo['extension'] == 'xml') {
      // Process array
      $xml = new SimpleXMLExtended($this->defaults['xml']);
      $xml = $this->arrayToXML($data, $xml, $root = true);
      $xml = $this->beautifyXML($xml);
      //var_dump('<pre>'. htmlentities($xml->save()) . '</pre>', $file);
      $save = @$xml->save($file);
    } else {
      $contents = $clean ? $this->clean($data) : $data;
      $save = @file_put_contents($file, $contents);
    }
  
    if (!$save) {
      throw new Exception($this->EXCEPTION_MKFILE);
    } else {
      return true;
    }
  }

  public function copyfile() {
  }

  public function mvfile() {
  }

  public function rmfile() {
  }

  public function getFile($file) {
    echo $file;
    $file = $this->fullpath($file);
    $fileinfo = pathinfo($file);

    if (!isset($fileinfo['extension'])) {
      $file .= '.xml';
      $fileinfo = pathinfo($file);
    }

echo $file;
    if ($fileinfo['extension'] == 'xml') {
      // Process array
      $xml = new SimpleXMLExtended($file, 0, true);
      $data = array();
      $data = $this->xmlToArray($xml, $data, $root = true);
    } elseif ($fileInfo['extension'] == 'json') {
      $data = json_decode(file_get_contents($file));
    } else {
      $contents = @file_get_contents($file);
    }
  
    if ($data === false) {
      throw new Exception($this->EXCEPTION_GETFILE);
    } else {
      return $data;
    }
  }

  public function getFiles($dir) {
    $dir = $this->fullpath($dir);
    $glob = glob($dir . '/*');
    $files = array();

    foreach ($glob as $file) {
      $files[$file] = $this->getFile($file);
    }

    return $files;
  }

  public function savefile() {
  }

  public function isfile() {
  }

  public function currentUrl() {
    $https    = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $protocol = ($https) ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  }
  
  public function adminURL() {
    return $this->urls['admin'];
  }

  public function currentAdminUrl() {
    $current = $_SERVER['REQUEST_URI'];
    $start = strpos($current, 'load.php');
    return substr($current, $start);
  }

  // == STRINGS ==
  public function clean($string) {
    return cl($string);
  }

  public function slug($string) {
    $slug = '';

    if (function_exists('prepareSlug')) {
      // GS 3.4
      $slug = prepareSlug($string);
    } else {
      // http://gilbert.pellegrom.me/php-quick-convert-string-to-slug/
      $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    return $slug;
  }

  public function translit($string) {
    $translit = '';

    if (function_exists('doTransliteration')) {
      // GS 3.4
      $translit = doTransliteration($string);
    } else {
      
    }

    return $translit;
  }

  public function excerpt($string, $max = 30) {
    $excerpt = $this->clean($string);
    $words = explode(' ', $excerpt);

    if (count($words) > $max) {
      $words = array_slice($words, 0, $max);
    }

    $words = implode(' ', $words);
    return $words;
  }

  // Private methods
  // Convert array structure to xml
  private static function arrayToXML($array, SimpleXMLExtended &$xml, $root = false) {
    if (!$root) $this->arrayToXMLSetAttributes($array, $xml);

    foreach ($array as $key => $value) {
      $isArray       = is_array($value);
      $isAssociative = $isArray && $this->isAssociativeArray($value);

      if ($isAssociative) {
        // Add key => value pairs for associative array
        $child = $xml->addChild($key);
        $this->arrayToXML($value, $child);
      } elseif ($isArray && !$isAssociative) {
        // TODO: Implement behaviour for indexed arrays
        // This implementation is still buggy

        // Add each array element as a new node with the same name
        if (isset($array['@attributes'])) {
          unset($array['@attributes']);
        }
        foreach ($value as $node) {
          $child = $xml->addChild($key);
          
          if (is_array($node)) {
            $this->arrayToXML($value, $child);
          } else {
            $child->addCData($node);
          }
        }

      } else {
        // Add CData
        $xml->{$key} = null;
        $xml->{$key}->addCData($value);
      }
    }

    return $xml;
  }

  // Pretty XML
  private static function beautifyXML($xml) {
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = false;
    $dom->loadXML($xml->saveXML());
    $dom->formatOutput = true;
    return $dom;
  }

  // Check attributes on array to xml
  private static function arrayToXMLSetAttributes(&$array, &$xml) {
    // Attributes
    $attributes = isset($array['@attributes']);

    if ($attributes) {
      $attributes = $array['@attributes'];
      foreach ($attributes as $attrName => $attrValue) {
        $xml->addAttribute($attrName, $attrValue);
      }

    }

    if ($attributes) unset($array['@attributes']);
  }

  // Convert xml back to array structure
  private static function xmlToArray($xml, $array) {
    
  }

  // Checks if an array is pure
  private function isAssociativeArray($array) {
    $keys = array_keys($array);
    $keys = array_filter($keys, 'is_string');
    return (bool) count($keys);
  }

  private function fullpath($path) {
    $path = str_replace(GSDATAOTHERPATH, '', $path);
    $path = GSDATAOTHERPATH . '/' . $path;
    return $path;
  }
}

?>
