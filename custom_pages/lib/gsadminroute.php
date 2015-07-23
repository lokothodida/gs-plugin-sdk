<?php

if (class_exists('GSAdminRoute')) return;

class GSAdminRoute {
  public static function instance() {
    return create_function('', 'return;');
  }
}