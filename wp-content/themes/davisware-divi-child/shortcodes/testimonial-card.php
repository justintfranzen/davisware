<?php

function create_dvswr_testimonial_shortcode($atts)
{
  // Shortcode attributes.
  $a = shortcode_atts(
    [
      'title' => 'Testimonial Title',
      'rating' => '0.0',
      'text' => 'Testimonal Text',
      'person' => 'Person Name',
      'company' => 'Person Company',
    ],
    $atts,
    'dvswr_testimonial',
  );

  $rating = esc_html($a['rating'] ?? '0.0');
  $rating_int = round($rating, 0, PHP_ROUND_HALF_DOWN);

  ob_start();
  ?>

  <article class="dvswr-testimonial-card">
    <h3 class="dvswr-testimonial-card_title">
      <?= esc_html($a['title'] ?? '') ?>
    </h3>
    <div class="dvswr-testimonial-card_rating">
      <span class="dvswr-testimonial-card_rating-text">
        <?= $rating ?>
      </span>
      <span class="dvswr-testimonial-card_rating-stars">
        <?php for ($x = 1; $x < 6; $x++): ?>
          <?php $filled = $x <= $rating_int ? 'filled' : 'empty'; ?>
          <span class="dvswr-testimonial-card_rating-star <?= $filled ?>"></span>
        <?php endfor; ?>
      </span>
    </div>
    <p class="dvswr-testimonial-card_text">
      <?= esc_html($a['text'] ?? '') ?>
    </p>
    <div class="dvswr-testimonial-card_person">
      <span class="dvswr-testimonial-card_person-name">
        <?= esc_html($a['person'] ?? '') ?>
      </span>
      <span class="dvswr-testimonial-card_person-company">
        <?= esc_html($a['company'] ?? '') ?>
      </span>
    </div>
  </article>

  <?php
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

function register_dvswr_testimonial_shortcode()
{
  add_shortcode('dvswr_testimonial', 'create_dvswr_testimonial_shortcode');
}
