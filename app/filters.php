<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});


add_action('pre_get_posts', function ($q) {
  if (is_admin() || !$q->is_main_query()) {
    return;
  }
  if ($q->is_search()) {
    if (!empty($_GET['post_type']) && $_GET['post_type'] === 'produkty') {
      $q->set('post_type', 'produkty');
    }
  }
});


/*--- BREACRUMB SEPARATOR ---*/
add_filter( 'woocommerce_breadcrumb_defaults', function ( $defaults ) {
    // Opakowujemy separator w element <span> z własną klasą CSS.
    $defaults['delimiter'] = '<span class="__separator">•</span>';
    return $defaults;
} );