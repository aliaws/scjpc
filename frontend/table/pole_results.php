<?php
if (!empty($_REQUEST)) {
//  echo "<pre>GET=" . count($_GET) . "==POST=" . count($_POST) . "==FILES=" . count($_FILES) . "==REQUEST=" . count($_REQUEST) . print_r($_GET, true) . print_r($_POST, true) . print_r($_FILES, true) . print_r($_REQUEST, true) . "</pre>";
  
  $search_result = search($_REQUEST);
  $record_keys = array_keys($search_result['results'][0] ?? []);
  $total_pages = isset($search_result["total_pages"]) ? (int)$search_result["total_pages"] : 0;
  $page = (int)$search_result["page_number"];
  $result_per_page = $search_result['result_per_page'];
  $num_results_on_page = $search_result['per_page'];
  $total_records = $search_result['total_records'] ?? 0;
  $current_user = wp_get_current_user();
  $user_id = $current_user->ID;
  $user_email = $current_user->user_email;
  $export_endpoint = trim(get_option('scjpc_es_host'), '/') . "/data-export";
  if ($total_records == 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/not_found.php';
  } else {
    if ($_REQUEST['action'] == 'quick_pole_search') {
      if ($search_result['results'][0]['status'] === 'A') {
        $pole_result = $search_result['results'][0];
        $jpa_results = $search_result['results'][0]['associated_jpas'];
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/single_pole.php";
      } else {
        include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/multiple_pole.php";
      }
    } elseif ($_REQUEST['action'] == 'advanced_pole_search' || $_REQUEST['action'] == 'jpa_detail_search') {
      include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/multiple_pole.php";
    } elseif ($_REQUEST['action'] == 'pole_detail') {
      $pole_result = $search_result['results'][0];
      $jpa_results = $search_result['results'][0]['associated_jpas'];
      include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/single_pole.php";
    }
  }
}
