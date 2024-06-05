<?php
function load_bootstrap_assets(): void {
  wp_enqueue_style('bootstrap_css', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");

  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
  wp_enqueue_script('jquery');

  wp_enqueue_style('frontend_css', SCJPC_ASSETS_URL . 'css/frontend.css', false, '4.9');
  wp_enqueue_style('responsive_css', SCJPC_ASSETS_URL . 'css/responsive.css', false, '1.7');
  wp_enqueue_style('print_css', SCJPC_ASSETS_URL . 'css/print.css', array(), '7.8', 'print');
  wp_enqueue_script('frontend_js', SCJPC_ASSETS_URL . 'js/frontend.js', false, '4.2', true);
}

/**
 * @param $date
 * @return false|string
 * December 24, 2023
 */
function change_date_format($date) {
  return date('F j, Y', strtotime($date));
}

function get_active_columns(): array {
  $active_columns = [];
  foreach (CHOICES as $checked_column) {
    $active_columns[$checked_column] = $checked_column;
  }
  return $active_columns;
}

function print_checkboxes($group): void {
  $active_columns = CHOICES ? get_active_columns() : '';
  foreach ($group as $key => $column) {
    if (CHOICES) {
      $checked = !empty($active_columns[$key]) ? 'checked' : '';
    } else {
      $checked = $column['default'] ? 'checked' : '';

    }
    if ($key != "all") {
      echo '<div  class="col-md-6 mb-2"><label for="id_choices_' . $key . '"><input id="id_choices_' . $key . '" name="choices[]" type="checkbox" value="' . $key . '" ' . $checked . '> ' . $column['label'] . '</label></div>';
    } else {
      echo '<div class="col-md-6 mb-2"><label for="id_choices_' . $key . '" id="select_all" style="cursor:pointer"><input id="id_choices_all"  type="checkbox" /> ' . $column['label'] . '</label></div>';
    }
  }
}

//function search_web_and_docs($query) {
//  if (!empty($_REQUEST['s'])) {
//      $args = [
//          'search_attachment' => $_REQUEST['s'],
//           'posts_per_page' => 200,
////          'tax_query' => [
////              'relation' => 'AND',
////              [
////                  'taxonomy' => 'bwdmfmx_mediafiles_category',
////                  'field' => 'term_id',
////                  'terms' => [15, 16, 17, 18, 19, 20]
////              ],
////          ],
//          'post_status' =>  [ 'publish', 'inherit'],
//          'post_type' =>   [$query->query_vars["post_type"], "attachment", "page"],
//      ];
//      foreach($args as $arg => $value) {
//          $query->set( $arg, $value);
//      }
////      add_filter('posts_where', 'ads_posts_where', 10, 2);
//  }
//}

//add_action('elementor/query/search_web_doc', 'search_web_and_docs');


add_shortcode('post_url_change', 'post_url_change');
function post_url_change() {
  global $post;
  if ($post->post_type == 'attachment') {
    return $post->guid;
  } else {
    return get_permalink($post);
  }
}

function getSortingAttributes($key, $sort_keys, $response_sort_key, $response_sort_order) {
    if($css_sort_classes = isset($sort_keys[$key]) ? 'has_sort' : '') {
        if($key == $response_sort_key) {
            $css_sort_classes.=  " ".$response_sort_order;
        }

        $data_sort_order = $response_sort_order == 'asc' || $response_sort_order == '' ? 'desc' :'asc';
        return [$css_sort_classes, $data_sort_order];
    }
    return ["", ""];
}