<?php

add_action('init', 'jvl_create_resource_post_type');
function jvl_create_resource_post_type()
{
  register_post_type('resource', [
    'labels' => [
      'name' => __('Resources'),
      'singular_name' => __('Resource'),
    ],
    'public' => true,
    'exclude_from_search' => false,
    'menu_position' => 5,
    // 'menu_icon' => 'dashicons-cart',
    'taxonomies' => ['resource-category', 'industry'],
    'supports' => ['title', 'revisions', 'page-attributes', 'excerpt', 'thumbnail', 'editor'],
    'has_archive' => false,
  ]);
}
