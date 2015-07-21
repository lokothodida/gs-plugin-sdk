<?php

if (!class_exists('GSPluginUtils')) {

class GSPluginUtils {
  protected static $SITEURL;

  // == PUBLIC METHODS ==
  // Get site url
  public static function siteUrl() {
    if (!static::$SITEURL) {
      global $SITEURL;
      static::$SITEURL = $SITEURL;
    }
    return static::$SITEURL;
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
      $url = str_replace(static::siteUrl(), '', $url);
    }

    return $url;
  }
}

}