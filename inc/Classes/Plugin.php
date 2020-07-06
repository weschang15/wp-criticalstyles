<?php

namespace CriticalStyles\Plugin\Classes;

use CriticalStyles\Plugin\Classes\Component;

final class Plugin
{
  public static function handle(): string
  {
    static $return = null;
    if (is_null($return)) {
      $return = plugin_basename(dirname(__FILE__, 3));
    }

    return $return;
  }

  public static function path(): string
  {
    static $return = null;
    if (is_null($return)) {
      $return = plugin_dir_path(dirname(__FILE__, 2));
    }

    return $return;
  }

  public static function url(): string
  {
    static $return = null;
    if (is_null($return)) {
      $return = plugin_dir_url(dirname(__FILE__, 2));
    }

    return $return;
  }

  public static function partial($path, $args = [], $echo = true)
  {
    if (!empty($args)) {
      extract($args);
    }

    if ($echo) {
      include self::path() . $path;
      return;
    }

    ob_start();

    
    include self::path() . $path;
    
    return ob_get_clean();
  }

  public static function get(string $config = ''): array
  {
    $file = sprintf('%s%s.php', self::path(), $config);

    $data = [];

    if (is_readable($file)) {
      $data = require $file;
    }

    return (array) $data;
  }

  public static function register(Component $component = null): void
  {
    if (method_exists($component, 'register')) {
      $component->register();
    }
  }

  public static function init(): void
  {
    $components = require_once self::path() . 'config/components.php';
    array_walk($components, [
      'CriticalStyles\\Plugin\\Classes\\Plugin',
      'register'
    ]);
  }
}