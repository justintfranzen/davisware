<?php
// register a mobile menu
function dvsw_top_banner_menu()
{
  register_nav_menu('dvsw-top-banner-menu', __('Top Banner Menu'));
}
add_action('init', 'dvsw_top_banner_menu');

function dvsw_add_top_banner_menu()
{
  ?>

  <?php
  $args = ['theme_location' => 'dvsw-top-banner-menu', 'menu_class' => 'top-banner-menu'];
  wp_nav_menu($args);
}
add_action('et_before_main_content', 'dvsw_add_top_banner_menu');
