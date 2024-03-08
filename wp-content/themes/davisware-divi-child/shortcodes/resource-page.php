<?php

function dvsw_make_blog_item($id, $type = 'post', $extra_classes = '')
{
  $link = get_the_permalink($id);
  $title = get_the_title($id);
  $featured_image = get_the_post_thumbnail($id, 'large');
  $excerpt = get_the_excerpt($id);
  $term_type = $type == 'resource' ? 'resource-category' : 'category';
  $terms = get_the_terms($id, $term_type);
  $category = $terms[0] ?? null;
  $category_title = $category?->name ?: 'Uncategorized';
  $category_link =
    get_the_permalink() . '?' . $term_type . '=' . ($category?->slug !== 'uncategorized' ? $category?->slug : '');

  $class_main = 'dvsw-blog-item';
  $class = $class_main . ' ' . esc_attr($extra_classes);

  ob_start();
  ?>
  <article class="<?= $class ?>">
    <div class="<?= $class_main . '_wrapper' ?>">
      <a href="<?= $link ?>" class="<?= $class_main . '_image-link' ?>">
        <?= $featured_image ?>
      </a>
      <p class="<?= $class_main . '_category' ?>">
        <a href="<?= $category_link ?>"  class="<?= $class_main . '_category-link' ?>">
          <?= $category_title ?>
        </a>
      </p>
      <h2 class="<?= $class_main . '_title' ?>">
        <a href="<?= $link ?>" class="<?= $class_main . '_title-link' ?>">
          <?= $title ?>
        </a>
      </h2>
      <div class="<?= $class_main . '_excerpt' ?>">
        <p class="<?= $class_main . '_excerpt-p' ?>">
          <?= $excerpt ?>
        </p>
      </div>
    </div>
  </article>
  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function dvsw_make_select_item($categories, $category, $placeholder, $type)
{
  if (!$categories || !is_array($categories) || !count($categories) > 1) {
    return;
  }

  // Make the options first, since we need the default option to be first (and know if it's selected)
  $has_cat = false;
  ob_start();
  foreach ($categories as $cat):

    if ($cat?->slug === 'uncategorized'):
      continue;
    endif;
    $selected = $cat?->slug === $category ? 'selected' : '';
    if ($selected):
      $has_cat = true;
    endif;
    ?>
      <option value="<?= esc_attr($cat?->slug) ?>" <?= $selected ?>>
        <?= esc_html($cat?->name) ?>
      </option>
    <?php
  endforeach;
  $options = ob_get_contents();
  ob_end_clean();

  ob_start();
  ?>
  <div class="dvsw-resource-page_filter dvsw-resource-page_filter--select">
    <select class="dvsw-resource-page_filter--select_input" name="<?= esc_attr($type) ?>">
      <?php $has_cat = true; ?>
      <option value="" <?= $has_cat ? '' : 'selected' ?>>
        <?= $placeholder ?>
      </option>
      <?= $options ?>
    </select>
  </div>
  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function create_dvswr_resource_page_filters_shortcode($atts)
{
  // Shortcode attributes.
  $a = shortcode_atts(
    [
      'type' => 'post',
    ],
    $atts,
    'dvswr_resource_page_filters',
  );

  $industry = tbp_get_parameters('industry');
  $category = tbp_get_parameters('category');
  $rcategory = tbp_get_parameters('resource-category');
  $topic = tbp_get_parameters('topic');

  $categories = get_terms([
    'taxonomy' => 'category',
    'hide_empty' => false,
  ]);
  $rcategories = get_terms([
    'taxonomy' => 'resource-category',
    'hide_empty' => false,
  ]);
  $industries = get_terms([
    'taxonomy' => 'industry',
    'hide_empty' => false,
  ]);

  ob_start();
  ?>
  <section class="dvsw-resource-page_filter-section">
    <form class="dvsw-resource-page_filter-section_wrapper" action="<?= get_the_permalink() ?>" method="GET">
      <div class="dvsw-resource-page_filter dvsw-resource-page_filter--text">
        <input 
          class="dvsw-resource-page_filter--text_input" 
          name="topic" 
          value="<?= esc_attr($topic) ?>" 
          placeholder="Search by Topic"
        />
        <button id="select_clear" type="button"><i class="fa-light fa-xmark"></i></button>
      </div>

      <?php if ($a['type'] == 'resource'): ?>
        <?= dvsw_make_select_item($rcategories, $rcategory, 'Filter Resources', 'resource-category') ?>

      <?php elseif ($a['type'] == 'post'): ?>
        <?= dvsw_make_select_item($categories, $category, 'Filter Resources', 'category') ?>

      <?php endif; ?>

      <?= dvsw_make_select_item($industries, $industry, 'Filter By Industry', 'industry') ?>

      <button class="dvsw-resource-page_submit et_pb_button dark" type="submit">
        Search
      </button>
    </form>
  </section>
  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function create_dvswr_resource_page_shortcode($atts)
{
  // Shortcode attributes.
  $a = shortcode_atts(
    [
      'type' => 'post',
      'highlight_enabled' => true,
      'highlight_title' => 'Highlight Story',
      'latest_title' => 'Latest Stories',
    ],
    $atts,
    'dvswr_resource_page',
  );
  $paged = max(1, get_query_var('paged'));

  if (!$a['type'] ?? null) {
    return;
  }

  $args = [
    'post_type' => $a['type'],
    'post_status' => 'publish',
    'posts_per_page' => 7,
    'paged' => $paged,
  ];

  $industry = tbp_get_parameters('industry');
  $category = tbp_get_parameters('category');
  $rcategory = tbp_get_parameters('resource-category');

  if ($category || $industry || $rcategory) {
    $args['tax_query'] = [];
    if ($category && $industry) {
      $args['tax_query']['relation'] = 'AND';
    }
    if ($category) {
      $args['tax_query'][] = [
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => [$category],
      ];
    }
    if ($rcategory) {
      $args['tax_query'][] = [
        'taxonomy' => 'resource-category',
        'field' => 'slug',
        'terms' => [$rcategory],
      ];
    }
    if ($industry) {
      $args['tax_query'][] = [
        'taxonomy' => 'industry',
        'field' => 'slug',
        'terms' => [$industry],
      ];
    }
  }

  $topic = tbp_get_parameters('topic');
  if ($topic) {
    $args['s'] = $topic;
  }

  $post_query = new WP_Query($args);

  if (!$post_query->have_posts()) {
    return '<div class="dvsw-resource-page no-posts-found"><span>Sorry, no posts found.</span></div>';
  }

  $highlight_post = $post_query->posts[0];
  $hp_id = $highlight_post->ID;
  $next_post = $post_query->posts[1] ?? null;

  ob_start();
  ?>
  <section class="dvsw-resource-page">
    <?php if ($a['highlight_enabled'] ?? null): ?>
      <section class="dvsw-resource-page_featured-section">
        <h3 class="dvsw-resource-page_featured-section_title">
          <?= $a['highlight_title'] ?? '' ?>
        </h3>
        <?= dvsw_make_blog_item($hp_id, $a['type'], 'dvsw-blog-item--featured') ?>
      </section>
    <?php endif; ?>
    <?php if ($next_post): ?>
      <section class="dvsw-resource-page_resource-section">
        <h4 class="dvsw-resource-page_resource-section_title">
          <?= $a['latest_title'] ?? '' ?>
        </h4>
        <hr class="dvsw-resource-page_resource-section_hr" />
        <div class="dvsw-resource-page_resource-section_resource-wrapper">
          <?php  ?>
          <?php while ($post_query->have_posts()): ?>
            <?php
            $post_query->the_post();
            $p_id = get_the_ID();
            if ($p_id === $hp_id) {
              continue;
            }
            echo dvsw_make_blog_item($p_id, $a['type']);
            ?>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
    <?php endif; ?>
    <?php the_tbp_pagination($post_query->max_num_pages); ?>
  </section>
  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function register_dvswr_resource_page_shortcode()
{
  add_shortcode('dvswr_resource_page_filters', 'create_dvswr_resource_page_filters_shortcode');
  add_shortcode('dvswr_resource_page', 'create_dvswr_resource_page_shortcode');
}
