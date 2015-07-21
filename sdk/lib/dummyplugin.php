<?php

class Dummy extends GSPlugin {
  // ...

  // Get the current class name (late static-binding workaround for PHP < 5.3)
  protected static function getClass() {
    return get_class();
  }
}