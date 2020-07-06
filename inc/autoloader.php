<?php

spl_autoload_register('criticalstyles_autoloader');

function criticalstyles_autoloader($class)
{
  // project-specific namespace prefix
  $prefix = 'CriticalStyles\\Plugin\\';

  // base directory for the namespace prefix
  $base_dir = dirname(__FILE__);

  // does the class use the namespace prefix?
  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    // no, move to the next registered autoloader
    return;
  }

  // get the relative class name and lowercase the first letter since our lib structure
  // uses lowercase naming for first level directories
  $relative_class = ucfirst(substr($class, $len));

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = $base_dir . '/' . str_replace('\\', '/', $relative_class) . '.php';
  // if the file exists, require it
  if (file_exists($file)) {
    require_once $file;
  }
}
