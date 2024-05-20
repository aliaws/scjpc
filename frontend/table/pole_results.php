<?php
if (!empty($_GET)) {
  $search_result = search($_GET);
  $record_keys = array_keys($search_result['results'][0] ?? []);
  $total_pages = isset($search_result["total_pages"]) ? (int)$search_result["total_pages"] : 0;
  $page = (int)$search_result["page_number"];
  $result_per_page = $search_result['result_per_page'];
  $num_results_on_page = $search_result['per_page'];
  $total_records = $search_result['total_records'] ?? 0;
  if ($total_records == 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/not_found.php';
  } else {
    if ($_GET['action'] == 'quick_pole_search') {
      if ($search_result['results'][0]['status'] === 'A') {
        $pole_result = $search_result['results'][0];
        $jpa_results = $search_result['results'][0]['associated_jpas'];
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/single_pole.php";
      } else {
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/multiple_pole.php";
      }
    } elseif ($_GET['action'] == 'advanced_pole_search' || $_GET['action'] == 'jpa_detail_search') {
      include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/multiple_pole.php";
    } elseif ($_GET['action'] == 'pole_detail') {
      $pole_result = $search_result['results'][0];
      $jpa_results = $search_result['results'][0]['associated_jpas'];
      include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/single_pole.php";
    }
  }
}
