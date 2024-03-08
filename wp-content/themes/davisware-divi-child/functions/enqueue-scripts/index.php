<?php
function davisware_child_theme_enqueue_styles()
{
  $site_version = defined('DVSW_SITE_VERSION') ? DVSW_SITE_VERSION : '1.0.0';
  wp_enqueue_style('davisware-divi-child', get_stylesheet_directory_uri() . '/dist/index.css', [], $site_version);
}
add_action('wp_enqueue_scripts', 'davisware_child_theme_enqueue_styles', 9999999);

function davisware_child_theme_enqueue_scripts()
{
  $site_version = defined('DVSW_SITE_VERSION') ? DVSW_SITE_VERSION : '1.0.0';
  wp_enqueue_script('davisware-divi-child', get_stylesheet_directory_uri() . '/dist/index.js', [], $site_version, true);
}
add_action('wp_enqueue_scripts', 'davisware_child_theme_enqueue_scripts', 11);
