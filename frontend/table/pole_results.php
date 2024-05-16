<?php
if (!empty($_GET)) {
  $search_results = search(($_GET));
  $record_keys = array_keys($search_results['results'][0] ?? []);
  $total_pages = (int)$search_results["total_pages"];
  $page = (int)$search_results["page_number"];
  $result_per_page = $search_results['result_per_page'];
  $num_results_on_page = $search_results['per_page'];
//  echo "<pre>" . print_r($search_results, true) . "</pre>";
  if ($search_results['total_records'] == 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/not_found.php';
  } else {
    if ($_GET['action'] == 'quick_pole_search') {
      if ($search_results['results'][0]['status'] === 'A') {
        $pole_result = $search_results['results'][0];
        $jpa_results = $search_results['results'][0]['associated_jpas'];
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/single_pole.php";
      } else {
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/multiple_pole.php";
      }
    } elseif ($_GET['action'] == 'advanced_pole_search' || $_GET['action'] == 'jpa_detail_search') {
      include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/multiple_pole.php";
    }
  }
}
