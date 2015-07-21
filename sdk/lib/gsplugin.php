<?php

if (!class_exists('GSPlugin')) {

class GSPlugin {
  // == PROPERTIES
  protected static $id, $tab;
  protected static $routes = array('admin' => array(), 'index' => array());
  protected static $hooks = array(), $filters = array();
  protected static $index;

  // == PUBLIC METHODS ==
  // Get plugin id
  public static function getId() {
    return static::$id;
  }
  
  // Get plugin tab
  public static function getTab() {
    return static::$tab;
  }

  // Internationalization
  public static function i18n($key, $replacements = array(), $echo = false) {
    $fullKey = static::$id . '/' . $key;
    $string = i18n_r($fullKey);
    $stringExists = strpos($string, '{') !== 0;
    if (!$stringExists) {
      $string = i18n_r($key);
    }

    // Process placeholders
    if (!is_array($replacements)) {
      $replacements = array($replacements);
    }

    foreach ($replacements as $i => $replacement) {
      $placeholder = '$' . ($i+1);
      $string = str_replace($placeholder, $replacement, $string);
    }

    if (!$echo) {
      return $string;
    } else {
      echo $string;
    }
  }

  // Administration panel url
  public static function adminUrl($page = false) {
    $url    = GSPluginUtils::adminUrl() . 'load.php?id=' . static::$id;
    $suffix = ($page !== false) ? '&p=' . $page : null;
    return $url . $suffix;
  }
  
  // Administration path
  public static function adminPath() {
  }
  
  // Admin routing
  public static function adminRoute($routes, $file) {
    if (!is_array($routes)) {
      $routes = array($routes);
    }
    
    foreach ($routes as $route) {
      static::$routes['admin'][$route] = $file;
    }

    //$url = GSPluginUtils::getCurrentUrl();
    //$path = dirname(dirname(__FILE__)) . '/' . $file;
    //include($path);
  }
  
  // Front-page paths
  public static function frontPath() {
  }
  
  // Front-page routing
  public static function frontRoute() {
  }
  
  // Initialization (developer doesn't need to ever call this)
  public static function initialize($info) {
    $class = static::getClass();

    static::setId($info['id']);
    static::setTab($info['tab']);

    static::i18nMerge($info);
    static::register($info, $class);
    static::setupSideBar();
    static::setupAutoloader();
    static::addIndex();
    static::processHooks();
  }
  
  public static function addHook($name, $callback, $args = array()) {
    static::$hooks[] = array($name, $callback, $args);
  }
  
  // == PUBLIC INTERFACES ==
  // Sidebar
  public static function sideBar() {
    return array();
  }

  // Administration Panel
  public static function adminPanel() { }

  // Front pages
  public static function index() { }
  
  // == PROTECTED METHODS
  // Langauge initialization
  protected static function i18nMerge($info) {
    i18n_merge(static::$id) || i18n_merge(static::$id, $info['defaultLang']);
  }

  // Regisration
  protected static function register($info, $class) {
    register_plugin(
      static::$id,
      static::i18n('PLUGIN_TITLE'),
      $info['version'],
      $info['author'],
      $info['website'],
      static::i18n('PLUGIN_DESC'),
      $info['tab'],
      array($class, 'setupAdminPanel')
    );
  }

  // Set up admin panel
  // Public because the register_function method needs to access it
  public static function setupAdminPanel() {
    static::adminPanel();
    
    // Do routing
    static::doAdminRoutes();
  }

  // Routing
  protected static function doAdminRoutes() {
    $routes = static::$routes['admin'];
    $url = GSPluginUtils::currentUrl(true);
    
    ///*
    // Sort the routes in order of length (longest to shortest)
    uksort($routes, create_function('$route1, $route2', '
      return strlen($route2) - strlen($route1);
    '));
    //*/
    
    // Check to see if there is a suitable selector
    // Pick the first one
    $currentUrl = GSPluginUtils::currentAdminUrl();
    foreach ($routes as $route => $page) {
      $prefix = $route !== '' ? '&p=' : null;
      $url = static::adminUrl() . $prefix . $route;
      $url = trim($url);

      if (strpos($currentUrl, $url) === 0) {
        $path = dirname(dirname(__FILE__)) . '/' . $page;
        include($path);
        break;
      }
    }
  }
  
  // Setting the index page's title
  protected static function setIndexTitle($title) {
    self::$index->title = $title;
  }
  
  // Index routing
  protected static function indexRoute($route, $procedure) {
    static::$routes['index'][$route] = $procedure;
  }
  
  // Doing the index route
  protected static function indexRouteRun() {
    // We will modify the $data_index object
    self::setIndex();

    $requestUrl = GSPluginUtils::currentIndexUrl();

    foreach (static::$routes['index'] as $route => $procedure) {
      $valid = false;
      $params = array();
      
      if ($route == $requestUrl) {
        // Equality
        $valid = true;
      } elseif (!$valid && @preg_match($route, $requestUrl, $params) === 1) {
        // Regular expression matches (error is suppressed here)
        $valid = true;
        array_shift($params);
      }

      if ($valid) {
        // Buffer the contents of what has been given
        ob_start();
        // Check if lambda is given
        
        // Otherwise just a file to include
        $path = dirname(dirname(__FILE__)) . '/' . $procedure;
        include($path);
        
        // Finish buffering the contents and clean the contents
        self::$index->content = ob_get_contents();
        ob_end_clean();
        break;
      }
    }
  }
  
  // Setup sidebar
  protected static function setupSideBar() {
    $sidebar = static::sideBar();

    foreach ($sidebar as $item) {
      $tab    = isset($item['tab']) ? $item['tab'] : static::getTab();
      $action = isset($item['action']) ? $item['action'] : null;
      $params = array(static::$id, $item['label'], $action);
      add_action($tab . '-sidebar', 'createSideMenu', $params);
    }
  }
  
  // Register autloader
  private static function setupAutoloader() {
    spl_autoload_register(array(static::getClass(), 'autoload'));
  }
  
  // Implement autoloader
  protected static function autoload($class) {
    $class = strtolower($class);
    $file = basename(__FILE__) . '/' . $class . '.php';
    if (file_exists($file)) {
      include($file);
    }
  }
  
  // Set the plugin's id
  protected static function setId($id) {
    static::$id = $id;
  }

  // Set the plugin's tab
  protected static function setTab($tab) {
    static::$tab = $tab;
  }

  // Process hooks
  protected static function processHooks() {
    foreach (static::$hooks as $hook) {
      list($name, $callback, $arguments) = $hook;
      add_action($name, $callback, $arguments);
    }
  }
  
  // Add index page
  protected static function addIndex() {
    static::addHook('error-404', array(static::getClass(), 'index'));
  }
  
  // Get the current class name
  protected static function getClass() {
    return get_class();
  }
  
  // Set the index page
  private static function setIndex() {
    global $data_index;
    self::$index = &$data_index;
  }
}

}
