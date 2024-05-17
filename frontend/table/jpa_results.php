<?php

if (!empty($_GET)) {
  $search_result = search($_GET);
}
if (!empty($_POST)) {
  $search_result = search($_POST);
}

if (!empty($_GET) || !empty($_POST)) {
//  $search_result = search(($_GET));
//  $record_keys = array_keys($search_result['results'][0] ?? []);
  $record_keys = JPAS_KEYS;
  $total_pages = isset($search_result["total_pages"]) ? (int)$search_result["total_pages"] : 0;
  $page = (int)$search_result["page_number"] ?? 1;
  $result_per_page = $search_result['result_per_page'] ?? RESULTS_PER_PAGE;
  $num_results_on_page = $search_result['per_page'];
  $total_records = $search_result['total_records'] ?? 0;
  $search_results = $search_result['results'] ?? [];
  $current_user = wp_get_current_user();
  $user_id = $current_user->ID;
  $user_email = $current_user->user_email;
  $api_endpoint = trim(get_option('scjpc_es_host'), '/') . "/data-export";
  if ($total_records > 0 && count($search_results) > 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/jpa_response.php';
  } else {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/not_found.php';
  }
}