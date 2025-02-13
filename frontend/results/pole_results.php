<?php
if ( ! empty ( $_REQUEST ) ) {

  $search_result = search_scjpc( $_REQUEST );

  $redirect_url  = $search_result['redirect_url'];
  $query_id      = $search_result['query_id'];

  $search_key = !empty($_REQUEST['pole_number']) && $_REQUEST['action'] != 'advanced_pole_search' ? $_REQUEST['pole_number'] : '';

  $search_query = urlencode( http_build_query( $_REQUEST ) );
  $record_keys = array_keys($search_result['results'][0] ?? []);
  $total_pages = isset($search_result["total_pages"]) ? (int)$search_result["total_pages"] : 0;
  $page = (int)$search_result["page_number"];
  $result_per_page = $search_result['result_per_page'];
  $num_results_on_page = $search_result['per_page'];
  $total_records = $search_result['total_records'] ?? 0;

  if ($_REQUEST['action'] == 'quick_pole_search' && $total_records == 0) {
    try {
      if ( is_countable($search_result['results'])){
        $total_records = count($search_result['results']);
      } else {
        $total_records = 0;
      }
    } catch (Exception $e) {
      $total_records = 0;
    }
  }

  $sort_keys = POLE_SORT_KEYS;
  $response_sort_key = $search_result['sort_key'] ?? 'unique_id';
  $response_sort_order = $search_result['sort_order'] ?? 'asc';

  $current_user = wp_get_current_user();
  $user_id = $current_user->ID;
  $user_email = $current_user->user_email;
  $export_endpoint = trim(get_option('scjpc_es_host'), '/') . "/data-export";

  if ($total_records == 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/partials/_not_found.php';
  } else {
    if ($_REQUEST['action'] == 'quick_pole_search') {
      if ($search_result['results'][0]['status'] === 'A') {
        $pole_result = $search_result['results'][0];
        $jpa_results = $search_result['results'][0]['associated_jpas'];
        if (count($jpa_results) == 0) {
          try {
            $jpa_number = $search_result['results'][0]['jpa_number'];
            $jpa_results = [['jpa_number_2' => $jpa_number, 'pdf_s3_key' => '']];
          } catch (Exception $e) {
          }
        }
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/single_pole.php";
      } else {
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/poles/_simple_table.php";
      }
    } elseif ($_REQUEST['action'] == 'advanced_pole_search' || $_REQUEST['action'] == 'jpa_detail_search') {
      include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/poles/_simple_table.php";
    } elseif ($_REQUEST['action'] == 'pole_detail') {
      $pole_result = $search_result['results'][0];
      $jpa_results = $search_result['results'][0]['associated_jpas'];
      include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/single_pole.php";
    }
  }
}
