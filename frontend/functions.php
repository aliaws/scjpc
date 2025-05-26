<?php
function load_bootstrap_assets(): void {
  wp_enqueue_style('bootstrap_css', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");

  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
  wp_enqueue_script('jquery');
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

  wp_enqueue_style('frontend_css', SCJPC_ASSETS_URL . 'css/frontend.css', false, '6.8');
  wp_enqueue_style('responsive_css', SCJPC_ASSETS_URL . 'css/responsive.css', false, '1.7');
  wp_enqueue_style('print_css', SCJPC_ASSETS_URL . 'css/print.css', array(), '7.9', 'print');
  wp_enqueue_script('base64_js', SCJPC_ASSETS_URL . 'js/base64.js', false, '1.0', true);
  wp_enqueue_script('frontend_js', SCJPC_ASSETS_URL . 'js/frontend.js', false, '8.83', true);
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

add_shortcode('post_url_change', 'post_url_change');
function post_url_change() {
  global $post;
  if ($post->post_type == 'attachment') {
    return $post->guid;
  } else {
    return get_permalink($post);
  }
}

function get_sorting_attributes($key, $sort_keys, $response_sort_key, $response_sort_order): array {
  if ($css_sort_classes = isset($sort_keys[$key]) ? 'has_sort' : '') {
    $response_sort_key = str_replace('_sort', '', $response_sort_key);
    if ($key == $response_sort_key) {
      $css_sort_classes .= " " . $response_sort_order;
    }

    $data_sort_order = $response_sort_order == 'asc' || $response_sort_order == '' ? 'desc' : 'asc';
    return [$css_sort_classes, $data_sort_order];
  }
  return ["", ""];
}

function download_export_array($response): array {
  $response_array = [];
  if (!empty($response) && !empty($response['status'])) {
    $download_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
        </svg>';
    $processed_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
        <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
      </svg>';
    $status = $response['status'];
    $base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
    $base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";
    $response_array['download_url'] = $status == 'Processed' ? "$base_cdn_url/{$response['s3_path']}" : '';
    $response_array['btn_disabled'] = $response['status'] == 'Processed' ? '' : 'disabled';
    $response_array['btn_text'] = $response['status'] == 'Processed' ? "Download Export" : "Export In Progress";
    $response_array['btn_icon'] = $response['status'] == 'Processed' ? $download_icon : $processed_icon;
    $total_pages = intval($response['total_pages']);
    $processed_pages = intval($response['pages_processed']);
    $export_progress = $processed_pages / $total_pages;
    // Calculate remaining pages
    $remaining_pages = $total_pages - $processed_pages;

    // Each page takes 1 second to process, so remaining time in seconds
    $remaining_time = $remaining_pages * 1;

    // Setting the refresh interval
    // Let's say we want to refresh every 1/10th of the remaining time
    $no_of_seconds_interval = max(1, round($remaining_time / 10));
    if ($no_of_seconds_interval < 3) {
      $no_of_seconds_interval = 3;
    }
    $response_array['no_of_seconds_interval'] = $no_of_seconds_interval;

    $export_progress = number_format($export_progress, 2) * 100;
    if ($status == 'Pending') {
      $export_progress = 0;
    } else if ($status !== 'Processed' && $export_progress >= 90) {
      $export_progress -= 2;
    }
    $response_array['export_progress'] = $export_progress;
    $response_array['status'] = $status;
    $response_array['search_query'] = $response["original_query"];
    $response_array['export_format'] = $response["export_format"];
  }
  return $response_array;
}


/**
 * This method returns an array of the BASE Owners added while migration having status active
 * @param bool $active
 * @param bool $formatted if passed true, the response array will have key value pairs or base owners code and name
 *                        if passed false, the table row like array will be returned
 * @return array
 */
function scjpc_get_base_owners(bool $active = false, bool $formatted = false): array {
  $table_name = scjpc_get_base_owners_table_name();
  $active_check = $active ? "WHERE status='active'" : '';
  $sql = "SELECT * from $table_name $active_check";
  global $wpdb;
  if ($formatted) {
    $base_owners = [];
    foreach ($wpdb->get_results($sql, ARRAY_A) as $result) {
      $base_owners[$result['base_owner_code']] = $result['base_owner_name'];
    }
  } else {
    $base_owners = $wpdb->get_results($sql, ARRAY_A);
  }
  return $base_owners;
}

function scjpc_get_base_owners_table_name() {
  return get_option('scjpc_base_owners_table', '');
}


function scjpc_string_contains_html_table($value): bool {
  return preg_match('/<table\b[^>]*>/i', $value);
}

function scjpc_is_base64( $str ): bool {
  return base64_decode( $str, true );
}