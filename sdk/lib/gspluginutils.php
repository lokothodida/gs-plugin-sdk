<?php

if (!class_exists('GSPluginUtils')) {

class GSPluginUtils {
  // == CONSTANTS ==
  const EXCEPTION_MKDIR  = 'mkDirErr';
  const EXCEPTION_MKFILE = 'mkFileErr';
  const EXCEPTION_COPY = 'copyErr';

  // == PROPERTIES ==
  protected static $defaults = array(
    'htaccess' => 'Deny from all',
    'xml' => '<?xml version="1.0" encoding="UTF-8"?><channel/>',
  );
  protected static $globals = array();
  private $lambdas = array();

  // Type casting map (for globals)
  private static $typeCast = array(
    'bool' => array('PRETTYURLS'),
  );

  // == PUBLIC METHODS ==
  // == Folder manipulation ==
  // Make a directory in /data/other
  // @throw EXCEPTION_MKDIR if mkdir fails
  // @throw EXCEPTION_MKFILE if .htaccess creation fails
  public static function mkDir($dir, $mode = 0755, $recursive = false, $htaccess = true) {
    $directory = GSDATAOTHERPATH . '/'. $dir . '/';

    if (static::dirExists($dir)) {
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

      static::mkFile($directory . '.htaccess', $htaccess);

      return true;
    }
  }

  // Copy a directory in /data/other
  public static function cpDir($source, $dest) {
    $source = static::prefixDirectory($source);
    $dest = static::prefixDirectory($dest);
    $copy = @copy($source, $dest);

    if (!$copy) {
      throw new Exception(static::EXCEPTION_COPY);
    } else {
      return true;
    }
  }

  public static function dirExists($dir) {
    $directory = GSDATAOTHERPATH . '/' . $dir;
    return (bool) !is_file($directory) && @file_exists($directory);
  }

  // == File manipulation ==
  public static function mkFile($file, $data, $clean = true) {
    $fileInfo = pathinfo($file);

    // Default to an xml file
    if (!isset($fileInfo['extension'])) {
      $file .= '.xml';
      $fileInfo = pathinfo($file);
    }

    $file = GSDATAOTHERPATH . '/' . $file;

    if (is_array($data) && $fileInfo['extension'] == 'xml') {
      // Process array
      $xml = new SimpleXMLExtended(static::$defaults['xml']);
      $xml = static::arrayToXML($data, $xml, $root = true);
      $xml = static::beautifyXML($xml);
      //var_dump('<pre>'. htmlentities($xml->save()) . '</pre>', $file);
      $save = @$xml->save($file);
    } else {
      $contents = $clean ? static::clean($data) : $data;
      $save = @file_put_contents($file, $contents);
    }
  
    if (!$save) {
      throw new Exception(static::EXCEPTION_MKFILE);
    } else {
      return true;
    }
  }

  // == String manipulation ==
  public static function clean($string) {
    $string = cl($string);

    return $string;
  }

  // == Site information ==
  // Get site url
  public static function siteUrl() {
    return static::getGlobal('SITEURL');
  }

  public static function prettyUrls() {
    return static::getGlobal('PRETTYURLS');
  }

  // Admin panel url
  public static function adminUrl() {
    return '';
  }

  // Current url
  public static function currentUrl() {
    $https    = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $protocol = ($https) ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  }
  
  // Current admin url
  public static function currentAdminUrl() {
    $current = $_SERVER['REQUEST_URI'];
    $start = strpos($current, 'load.php?');
    return substr($current, $start);
  }

  // Current index url
  public static function currentIndexUrl($relative = true) {
    $url = static::currentUrl();

    if ($relative) {
      $siteUrl = static::siteUrl();

      if (!static::prettyUrls()) {
        $siteUrl .= 'index.php?id=';
      }

      $url = str_replace($siteUrl, '', $url);
    }

    return $url;
  }

  // Setting a global
  private static function getGlobal($name) {
    $globalSet = isset(static::$globals[$name]);
    $globalExists = !$globalSet && isset($GLOBALS[$name]);

    if ($globalExists) {
      $global = $GLOBALS[$name];
      // The global might be a SimpleXML object, so we need to ensure that
      // it is a primitive
      if (is_a($global, 'SimpleXMLExtended')) {
        $global = (string) $global;

        foreach (static::$typeCast as $type => $values) {
          if (in_array($name, $values)) {
            settype($global, $type);
          }
        }
      }

      static::$globals[$name] = $global;
    }

    return static::$globals[$name];
  }

  // == PRIVATE METHODS ==
  // Prefix a directory
  private static function prefixDirectory($dir) {
    if (strpos(GSROOTPATH, $dir) === false) {
      $dir = GSDATAOTHERPATH . '/' . $dir . '/';
    } else {
      $dir = $dir . '/';
    }

    return $dir;
  }

  // Convert array structure to xml
  private static function arrayToXML($array, SimpleXMLExtended &$xml, $root = false) {
    if (!$root) static::arrayToXMLSetAttributes($array, $xml);

    foreach ($array as $key => $value) {
      $isArray       = is_array($value);
      $isAssociative = $isArray && static::isAssociativeArray($value);

      if ($isAssociative) {
        // Add key => value pairs for associative array
        $child = $xml->addChild($key);
        static::arrayToXML($value, $child);
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
            static::arrayToXML($value, $child);
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
  private static function isAssociativeArray($array) {
    $keys = array_keys($array);
    $keys = array_filter($keys, 'is_string');
    return (bool) count($keys);
  }
  
  // Creates a function and caches it
  private static function createFunction($params, $code) {
    if (!isset(static::$lambdas[$code])) {
      static::$lambdas[$code] = create_function($params, $code);
    }

    return $lambdas[$code];
  }
}

}
