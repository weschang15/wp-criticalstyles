<?php

/**
 * Plugin Name: WP Critical Styles
 * Plugin URI: https://www.criticalstyles.com
 * Description: WordPress integration for Critical Styles
 * Version: 1.0.0
 * Author: Wesley Chang
 * Author URI: https://wesleychang.me
 * License: GPLv2 or later
 * Text Domain: criticalstyles
 */

defined("ABSPATH") or die("Hey, you don't belong here!");

// import custom autoloader
require_once plugin_dir_path(__FILE__) . 'inc/autoloader.php';

if (class_exists('CriticalStyles\\Plugin\\Classes\\Plugin')) {
  function criticalstyles_init()
  {
    return CriticalStyles\Plugin\Classes\Plugin::init();
  }

  if (!\wp_installing()) {
    add_action('plugins_loaded', 'criticalstyles_init', 14);
  }
}