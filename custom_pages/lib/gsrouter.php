<?php

// Very simply router class
class GSRouter {
  protected $params = array();
  protected $routes = array();
  
  public function __construct($params) {
    $this->params = $params;
  }

  public function add($path, $action) {
    $this->routes[$path] = $action;
  }

  public function run() {
    $action = array();

    $request = $this->params['request'];
    $prefix = $this->params['prefix'];

    foreach ($this->routes as $route => $procedure) {
      $valid = false;
      $matches = array();
      if ($route != '') {
        // Shave off the prefix
        if (substr($request, 0, strlen($prefix)) == $prefix) {
          $request = substr($request, strlen($prefix));
        }
      }

      if ($route == $request) {
        // Equality
        $valid = true;
      } elseif (!$valid && @preg_match($route, $request, $matches) === 1) {
        // Regular expression matches (error is suppressed here)
        $valid = true;
        array_shift($matches);
      }

      if ($valid) {
        $action = array($this->params['path'] . $procedure, $matches);
        break;
      }
    }

    return $action;
  }
}
