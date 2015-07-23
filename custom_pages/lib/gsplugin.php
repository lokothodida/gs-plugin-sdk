<?php

if (class_exists('GSPlugin')) return;

class GSPlugin {
  // == PROPERTIES ==
  protected $urls = array();
  protected $info = array();
  protected $hooks = array();
  protected $lambda = array();
  protected $utils, $ui;
  protected $admin;
  protected $indexPage;

  // == PUBLIC METHODS ==
  // Constructor
  public function __construct($info) {
    $this->info = $info;
  }

  // Initialization
  public function init() {
    // Register the plugin
    register_plugin(
      $this->id(),
      $this->i18n('PLUGIN_TITLE'),
      $this->info['version'],
      $this->info['author'],
      $this->info['website'],
      $this->i18n('PLUGIN_DESC'),
      $this->info['tab'],
      array($this, 'setupAdmin')
    );

    // Process the hooks
    foreach ($this->hooks as $hook) {
      list($name, $function, $arguments) = $hook;
      add_action($name, $function, $arguments);
    }
    
    // Register autoloader
    spl_autoload_register(array($this, 'autoloader'));
    $this->utils = new GSUtils($this->id());
    $this->ui = new GSUI($this->id());
  }
  
  // Get plugin id
  public function id() {
    return $this->info['id'];
  }
  
  // Get plugin path
  public function path() {
    return GSPLUGINPATH . $this->id() . '/';
  }
  
  // Internationalization
  public function i18n($hash) {
    $string = i18n_r($this->id() . $hash);

    if (strpos($hash, '{') !== -1) {
      $string = i18n_r($hash);
    }

    return $string;
  }
  
  // Hook wrapper
  public function hook($name, $function, $arguments) {
    $this->hooks[] = array($name, $function, $arguments);
  }
  
  // Sidebar
  public function sidebar($label, $action = '', $visible = true, $page = null) {
    $page = $page ? $page : $this->info['tab'];
    $this->hook($page . '-sidebar', 'createSideMenu', array($this->id(), $label, $action, $visible));
  }

  // Setting up the admin panel
  public function setupAdmin() {
    // Initialize variables
    //$router = $this->router();
    $utils = $this->utils;
    $ui = $this->ui;
    $plugin = $this;
    $currentURL = $utils->currentURL();
    $request = str_replace($utils->adminURL() . '/load.php?id=' . $this->id(), '', $currentURL);
    $route = new GSRouter(array(
      'request' => $request,
      'path' => $this->path(),
      'prefix' => '&',
    ));
    
    // Run admin method
    include($this->admin);
    
    $run = $route->run();
    if (count($run) == 2) {
      $params = $run[1];
      include($run[0]);
    }
  }
  
  // Admin panel
  public function admin($script) {
    $this->admin = GSPLUGINPATH . $this->id() . '/' . $script;
  }
  
  // Admin url
  // If current = true, gives current admin url
  // If current is a string, gives url to plugin with the string appended
  // If current = false, gives base url to admin panel
  public function adminURL($page = false, $relative = false) {
    $url = $this->utils->adminURL() . '/load.php?id=' . $this->id();
    
    if ($page) {
      $url = $url . '&' . $page;
    }

    return $url;
  }
  
  // Index page
  public function index($script) {
    $this->lambda['index'] = $this->lambdaFile($script);
  }
  
  // == PRIVATE METHODS ==
  // Creating a router
  private function router() {
    if (!$this->router) {
      $this->router = new GSRouter('');
    }
    return $this->router;
  }
  
  // Efficient wrapper for PHP 4 lambdas
  private function lambda($params, $code) {
    if (!isset($this->lambda[$code])) {
      $this->lambda[$code] = create_function($params, $code);
    }

    return $this->lambda[$code];
  }
  
  // Lambda from an external script
  private function lambdaFile($script) {
    $path = GSPLUGINPATH . $this->id() . '/' . $script;
    return $this->lambda('', 'return include("' . $path . '");');
  }
  
  // Autoloader
  private function autoloader($class) {
    return include($this->path() . 'autoloader.php');
  }
}
