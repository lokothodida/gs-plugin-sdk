<?php

// Prevent this class from being included if another plugin
// has already loaded it
if (class_exists('GSPlugin')) return;

// This is a wrapper class for creating GetSimple plugins.
// It is designed to allow you to easily drop it into your project
// to give a wrapper for the GetSimple plugin registration methods
// You should not modify this script directly. It is part of the
// proposed GetSimple Plugin SDK, and thus should be imported as-is.
// If however you wish to add new functions, you can simpl extend
// the class with your own, and instantiate that class instead of
// GSPlugin.
// Documentation for this class is available at @TODO
class GSPlugin {
  // == CONSTANTS ==
  const SDK_VERSION = 0.1;

  // == PROPERTIES ==
  protected $hooks = array();
  protected $hookScripts = array();
  protected $scripts = array(
    'hooks' => array(),
    'filters' => array(),
  );
  protected $filters = array();
  protected $info = array();
  protected $actions = array('admin' => array());
  protected $javascript = array();
  protected $stylesheet = array();
  protected $callbacks = array('admin' => array(), 'autoload' => array(), 'index' => array());
  private   $adminmode, $admincallback, $routes = array('admin' => array(), 'index' => array());
  private   $indexmode, $indexcallback;

  // == PUBLIC METHODS ==
  public function __construct($info) {
    // TODO: autoloading and dependency recognition
    $this->info = $info;
    $this->info['path'] = GSPLUGINPATH . '/' . $this->id() . '/'; 
    $this->i18nMerge();
  }

  // Plugin ID
  public function id() { return $this->info['id']; }

  // Plugin Version
  public function version() { return $this->info['version']; }

  // Plugin Author
  public function author() { return $this->info['author']; }

  // Author Website
  public function website() { return $this->info['website']; }

  // Plugin Tab
  // Return the plugin tab or register a new tab
  public function tab() {
    $args = func_get_args();

    if (count($args) == 0) {
      return $this->info['tab'];
    } else {
      if (empty($args[0])) {
        $args[0] = '';
      }

      if (!isset($args[1])) {
        $args[1] = null;
      }

      if (!isset($args[2])) {
        $args[2] = null;
      }

      $tab = array(
        'tabname'  => $args[0],
        'pluginid' => $this->id(),
        'label'    => $args[1],
        'action'   => $args[2],
      );

      $this->hook('nav-tab', 'createNavTab', $tab);
    }
  }

  // Add a sidebar
  public function sidebar($label, $action = null, $visibility = true, $tab = null) {
    if (empty($tab)) {
      $tab = $this->tab();
    }

    $this->hook($tab . '-sidebar', 'createSideMenu', array(
      $this->id(),
      $label,
      $action,
      $visibility)
    );
  }

  // Register a hook
  // If the $fn is a PHP script, run the script
  public function hook($name, $fn, $args = array()) {
    if (strpos($name, '-') === false) {
      $name = $this->tab() . '-' . $name;
    }

    if ($this->isPHPScript($fn)) {
      $scriptId = count($this->hookScripts);
      $this->hookScripts[] = $fn;
      $fn = array($this, 'hookScript_' . $scriptId);
    }

    $this->hooks[] = array($name, $fn, $args);
  }

  // Trigger a hook
  public function trigger($hookname) {
    exec_action($hookname);
  }

  // Register a filter
  public function filter($name, $fn) {
    if ($this->isPHPScript($fn)) {
      $id = count($this->scripts['filters']);
      $this->scripts['filters'][] = $fn;
      $fn = array($this, 'filterScript_' . $id);
    }

    $this->filters[] = array($name, $fn);
  }

  // Register and queue script
  public function script($params = array()) {
    $params = array_merge(array(
      'id' => null,
      'src' => null,
      'baseurl' => $GLOBALS['SITEURL'] . $GLOBALS['GSADMIN'] . '/',
      'version' => '0.1',
      'where' => GSBACK,
      'footer' => false,
    ), $params);

    $id = $params['id'];
    $src = $params['baseurl'] . $params['src'];
    $version = $params['version'];
    $footer = $params['footer'];
    $where = $params['where'];

    $this->javascript[] = array($id, $src, $version, $footer, $where);
  }

  // Register and queue style
  public function style($params = array()) {
    $params = array_merge(array(
      'id' => null,
      'href' => null,
      'baseurl' => $GLOBALS['SITEURL'] . $GLOBALS['GSADMIN'] . '/',
      'version' => '0.1',
      'where' => GSBACK,
      'media' => null,
    ), $params);

    $id = $params['id'];
    $href = $params['baseurl'] . $params['href'];
    $version = $params['version'];
    $media = $params['media'];
    $where = $params['where'];

    $this->stylesheet[] = array($id, $href, $version, $media, $where);
  }

  // Initialize the plugin
  public function init() {
    $this->register();
    $this->hook('error-404', array($this, 'executeIndex'));
    $this->processHooks();
    $this->processFilters();
    $this->processStylesheet();
    $this->processJavascript();
  }

  // i18n hashes, namespaced by the plugin id
  public function i18n($hash) {
    return i18n_r($this->id() . '/' . $hash);
  }

  // Plugin path
  public function path() {
    return $this->info['path'];
  }

  // Tells you if plugins are enabled
  public function enabled() {
    $plugins = func_get_args();

    if (count($plugins) && is_array($plugins[0])) {
      $plugins = array_merge($plugins, $plugins[0]);
    }

    $live = $GLOBALS['live_plugins'];
    $enabled = true;

    foreach ($plugins as $plugin) {
      $file = $plugin . '.php';

      $enabled = isset($live[$file]) && $live[$file] != 'false';
      if (!$enabled) break;
    }

    return $enabled;
  }

  // Registers admin panel actions
  // If $args[0] is a PHP script, run that script
  // If $args[0] is a function, run the function
  // If $args[0] is a URL route and $args[1] a PHP script/function, run the
  // script/function
  public function admin() {
    // Check the arguments
    $args = func_get_args();

    if (count($args) == 1) {
      $this->adminmode = 1;
      $callback = $this->createCallback($args[0], 'admin');
      $this->admincallback = $callback;
    } elseif (count($args) >= 2) {
      if (empty($args[0]) && !is_string($args[0])) {
        $args[0] = '/(.*)/';
      }
      $this->adminmode = 2;
      $this->routes['admin'][] = $args;
    }
  }

  // Register index actions
  public function index() {
    // Check the arguments
    $args = func_get_args();

    if (count($args) == 1) {
      $this->indexmode = 1;
      $callback = $this->createCallback($args[0], 'index');
      $this->indexcallback = $callback;
    } elseif (count($args) >= 2) {
      $this->indexmode = 2;
      $this->routes['index'][] = $args;
    }
  }

  // Autoload
  public function autoload($callback) {
    $cb = $this->createCallback($callback, 'autoload');
    if ($this->isPHPScript($callback)) {
      spl_autoload_register(create_function('$class', 'include "' . $this->path() . $callback . '";'));
    } else {
      spl_autoload_register($cb);
    }
  }

  // == MAGIC METHODS ==
  // Implements the hook PHP scripts
  public function __call($name, $args) {
    $explode = explode('_', $name);
    if ($explode[0] == 'hookScript') {
      // Hooks
      return $this->runScript($this->hookScripts[$explode[1]]);
    } elseif ($explode[0] == 'filterScript') {
      // Filters
      return $this->runScript($this->scripts['filters'][$explode[1]]);
    } elseif ($explode[0] == 'runCallback' && $explode[1] == 'admin') {
      $args = array_merge(array('plugin' => $this), $args[0]);
      return $this->runCallback('admin', $explode[2], $args);
    } elseif ($explode[0] == 'runCallback' && $explode[1] == 'index') {
      return $this->runCallback('index', $explode[2], $args[0]);
    } elseif ($explode[0] == 'runCallback' && $explode[1] == 'autoload') {
      return $this->runCallback('autoload', $explode[2], array('class' => $args[0]));
    } else {
      throw new Exception('Method not found');
    }
  }

  // == PROTECTED METHODS ==
  protected function i18nMerge() {
    i18n_merge($this->id()) || i18n_merge($this->id(), 'en_US');
  }

  protected function register() {
    call_user_func_array('register_plugin', array(
      'id'          => $this->id(),
      'title'       => $this->i18n('PLUGIN_TITLE'),
      'version'     => $this->version(),
      'author'      => $this->author(),
      'website'     => $this->website(),
      'description' => $this->i18n('PLUGIN_DESCRIPTION'),
      'tab'         => $this->tab(),
      'admin'       => array($this, 'executeAdmin'),
    ));
  }

  protected function processHooks() {
    foreach ($this->hooks as $hook) {
      list ($name, $fn, $args) = $hook;
      add_action($name, $fn, $args);
    }
  }

  protected function processFilters() {
    foreach ($this->filters as $filter) {
      list ($name, $fn) = $filter;
      add_filter($name, $fn);
    }
  }

  protected function processStylesheet() {
    foreach ($this->stylesheet as $stylesheet) {
      list($id, $src, $version, $media, $where) = $stylesheet;
      register_style($id, $src, $version, $media);
      queue_style($id, $where);
    }
  }

  protected function processJavascript() {
    foreach ($this->javascript as $javascript) {
      list($id, $src, $version, $footer, $where) = $javascript;
      register_script($id, $src, $version, $footer);
      queue_script($id, $where);
    }
  }

  protected function isPHPScript($script) {
    if (is_string($script)) {
      $explode = explode('.', $script);
      $ext = end($explode);
      $ext = strtolower($ext);
      return $ext == 'php';
    } else {
      return false;
    }
  }

  private function createCallback($function, $type) {
    $callback = null;
    if ($this->isPHPScript($function)) {
      $id = count($this->callbacks[$type]);
      $this->callbacks[$type][] = $function;
      $callback = array($this, 'runCallback_' . $type . '_' . $id);
    } else {
      $callback = $function;
    }

    return $callback;
  }

  private function runCallback($type, $id, $args = array()) {
    extract($args);
    if (isset($args['exports'])) {
      extract($args['exports']);
    }
    return include($this->path() . $this->callbacks[$type][$id]);
  }

  private function runScript($script, $import = array()) {
    if (!isset($import['plugin'])) {
      $import['plugin'] = $this;
    }
    extract($import);
    return include($this->path() . $script);
  }

  // Executes the administration panel
  public function executeAdmin() {
    $exports = array();
    if ($this->adminmode === 1) {
      call_user_func($this->admincallback, array('exports' => $exports));
    } elseif ($this->adminmode === 2) {
      $route = $this->executeAdminRoutes();
      if ($route['success']) {
        $callback = $route['callback'];
        $exports['matches'] = $route['matches'];
        $exports = array_merge($exports, $route['params']);
        $this->invoke($callback, array('exports' => $exports));
      }
    }
  }

  private function invoke($callback, $params) {
    call_user_func($callback, $params);
  }

  // Executes admin panel routes
  private function executeAdminRoutes() {
    $url = $_SERVER['REQUEST_URI'];
    $prefix = 'load.php?id=' . $this->id();
    $url = substr($url, strpos($url, $prefix) + strlen($prefix));

    if (strpos($url, '&') === 0) {
      $url = substr($url, 1);
    }

    $route = $this->executeRoutes($url, $this->routes['admin']);

    if ($route['success']) {
      $route['callback'] = $this->createCallback($route['callback'], 'admin');
    }

    return $route;
  }

  //
  private function executeRoutes($request, $routes) {
    foreach ($routes as $id => $route) {
      list($url, $action) = $route;

      if (isset($route[2])) {
        $params = $route[2];
      } else {
        $params = array();
      }

      $valid = false;
      $matches = array();

      if ($url == $request) {
        // Equality
        $valid = true;
      } elseif (!$valid && @preg_match($url, $request, $matches) === 1) {
        // Regular expression matches (error is suppressed here)
        $valid = true;
        array_shift($matches);
      }

      if ($valid) {
        return array(
          'params' => $params,
          'matches' => $matches,
          'callback' => $action,
          'success' => true,
        );
      }
    }

    return array(
      'success' => false,
    );
  }

  public function executeIndex() {
    $exports = array();
    if ($this->indexmode === 1) {
      call_user_func($this->indexcallback, $exports);
    } elseif ($this->indexmode === 2) {
      $route = $this->executeIndexRoutes();
      if ($route['success']) {
        $callback = $route['callback'];
        $exports['matches'] = $route['matches'];
        $exports = array_merge($exports, $route['params']);
        $exports['_index'] = $GLOBALS['data_index'];

        ob_start();
          $this->invoke($callback, array('exports' => $exports));
          $GLOBALS['data_index']->content = ob_get_contents();
        ob_end_clean();
      }
    }
  }

  private function executeIndexRoutes() {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $prefix = 'index.php?id=';
    $url = str_replace($GLOBALS['SITEURL'], '', $url);

    if (strpos($url, $prefix) === 0) {
      $url = substr($url, strpos($url, $prefix) + strlen($prefix));
    }

    if (strpos($url, '&') === 0) {
      $url = substr($url, 1);
    }

    $route = $this->executeRoutes($url, $this->routes['index']);

    if ($route['success']) {
      $route['callback'] = $this->createCallback($route['callback'], 'index');
    }

    return $route;
  }
}

