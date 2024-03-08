<?php

function create_dvswr_banner_shortcode($atts)
{
  // Shortcode attributes.
  $a = shortcode_atts(
    [
      'text' => 'Banner text',
      'button-link' => 'button link',
    ],
    $atts,
    'dvswr_banner',
  );

  ob_start();
  ?>

<?php if ($a['text']): ?>
  <div class="dvswr-banner">
     <a href="<?= $a['button-link'] ?>" target="_blank">
          <p class="banner-text"><?= $a['text'] ?></p>
          <i class="fa-solid fa-arrow-right"></i>
      </a>
  </div>
 <?php endif; ?>

  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function register_dvswr_banner_shortcode()
{
  add_shortcode('dvswr_banner', 'create_dvswr_banner_shortcode');
}
