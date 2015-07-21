<?php

if (!class_exists('GSPluginUtils')) {

class GSPluginUtils {
  protected static $globals = array(), $SITEURL, $PRETTYURLS;

  // Type casting map (for globals)
  private static $typeCast = array(
    'bool' => array('PRETTYURLS'),
  );

  // == PUBLIC METHODS ==
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
}

}
