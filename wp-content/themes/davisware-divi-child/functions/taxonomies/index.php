<?php

add_action('init', 'dvsw_create_industry_taxonomy', 0);
function dvsw_create_industry_taxonomy()
{
  $labels = [
    'name' => _x('Industries', 'taxonomy general name'),
    'singular_name' => _x('Industry', 'taxonomy singular name'),
  ];

  register_taxonomy(
    'industry',
    ['post'],
    [
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
    ],
  );
}

add_action('init', 'dvsw_create_resource_category_taxonomy', 0);
function dvsw_create_resource_category_taxonomy()
{
  $labels = [
    'name' => _x('Categories', 'taxonomy general name'),
    'singular_name' => _x('Category', 'taxonomy singular name'),
  ];

  register_taxonomy(
    'resource-category',
    ['resource'],
    [
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
    ],
  );
}
