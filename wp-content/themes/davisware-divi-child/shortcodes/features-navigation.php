<?php

// Menu Creation
add_action('init', 'dvswr_register_features_nav_menus');
function dvswr_register_features_nav_menus()
{
  register_nav_menus([
    'features-menu-1' => __('Features Menu 1'),
    'features-menu-2' => __('Features Menu 2'),
  ]);
}

// Nav 1 Shortcode
function create_dvswr_features_nav_1_shortcode($atts)
{
  // Shortcode attributes.
  $a = shortcode_atts(
    [
      'text_list' => '',
    ],
    $atts,
    'dvswr_feature_nav_1',
  );

  ob_start();
  ?>
  <div class="dvsw-features-navigation dvsw-features-navigation--1">
    <?php wp_nav_menu(['theme_location' => 'features-menu-1']); ?>
  </div>
  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

// Nav 2 Shortcode
function create_dvswr_features_nav_2_shortcode($atts)
{
  // Shortcode attributes.
  $a = shortcode_atts(
    [
      'text_list' => '',
    ],
    $atts,
    'dvswr_feature_nav_2',
  );

  ob_start();
  ?>
  <div class="dvsw-features-navigation dvsw-features-navigation--2">
    <?php wp_nav_menu(['theme_location' => 'features-menu-2']); ?>
  </div>
  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function register_dvswr_features_nav_shortcode()
{
  add_shortcode('dvswr_feature_nav_1', 'create_dvswr_features_nav_1_shortcode');
  add_shortcode('dvswr_feature_nav_2', 'create_dvswr_features_nav_2_shortcode');
}
