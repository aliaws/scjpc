<?php
//echo "<pre>" . print_r($_FILES, true) . "</pre>";
//echo "<pre>" . print_r($_POST, true) . "</pre>";

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
  $total_pages = (int)$search_result["total_pages"];
  $page = (int)$search_result["page_number"];
  $result_per_page = $search_result['result_per_page'];
  $num_results_on_page = $search_result['per_page'];
  if ($search_result['total_records'] > 0 && count($search_result['results']) > 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/jpa_response.php';
  } else {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/not_found.php';
  }
}