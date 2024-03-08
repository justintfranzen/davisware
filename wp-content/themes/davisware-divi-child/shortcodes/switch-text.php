<?php

function create_dvswr_switch_text_shortcode($atts)
{
  // Shortcode attributes.
  $a = shortcode_atts(
    [
      'text_list' => '',
    ],
    $atts,
    'dvswr_testimonial',
  );

  if (!$a['text_list'] || !is_string($a['text_list'])) {
    return '';
  }
  $text_array = explode(',', $a['text_list']);
  if (!count($text_array)) {
    return '';
  }
  ob_start();
  ?>
  <span class="dvsw-text-switcher">
    <?php foreach ($text_array as $text): ?>
      <span><?= esc_html(trim($text)) ?></span>
    <?php endforeach; ?>
  </span>
  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function register_dvswr_switch_text_shortcode()
{
  add_shortcode('dvswr_switch_text', 'create_dvswr_switch_text_shortcode');
}
