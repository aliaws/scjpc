<?php
function load_bootstrap_assets() {

  wp_enqueue_style('bootstrap_css', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
//  wp_enqueue_script('bootstrap_js', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js");


  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

  wp_enqueue_script('jquery');

  wp_enqueue_style('frontend_css', SCJPC_ASSETS_URL . 'css/frontend.css', false, '2.1');
  wp_enqueue_script('frontend_js', SCJPC_ASSETS_URL . 'js/frontend.js', false, '2.2', true);
}

/**
 * @param $date
 * @return false|string
 *      December 24, 2023
 */
function change_date_format($date) {
  return date('F j, Y', strtotime($date));
}

/*
 * custom pagination with bootstrap .pagination class
 * source: http://www.ordinarycoder.com/paginate_links-class-ul-li-bootstrap/
 */

function custom_bootstrap_pagination($total_pages, $current_page) {
  $big = 999999999; // need an unlikely integer
  echo "<br>";
  echo str_replace($big, '%#%', esc_url(get_pagenum_link($big)));
  echo paginate_links(array(
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format' => '?page_number=%#%',
    'current' => max(1, $current_page),
    'total' => $total_pages,
    'prev_text' => __('« Previous', 'text-domain'),
    'next_text' => __('Next »', 'text-domain'),
    'type' => 'list',
    'prev_next' => true,
    'before_page_number' => '<span class="page-link">',
    'after_page_number' => '</span>',
    'show_all' => false,
    'end_size' => 1,
    'mid_size' => 2,
  ));
}

?>
