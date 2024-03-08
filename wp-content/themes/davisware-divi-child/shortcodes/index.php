<?php
require_once 'features-navigation.php';
require_once 'resource-page.php';
require_once 'switch-text.php';
require_once 'testimonial-card.php';
require_once 'top-banner.php';

function dvsw_register_shortcodes()
{
  register_dvswr_features_nav_shortcode();
  register_dvswr_resource_page_shortcode();
  register_dvswr_switch_text_shortcode();
  register_dvswr_testimonial_shortcode();
  register_dvswr_banner_shortcode();
}
add_action('init', 'dvsw_register_shortcodes');
