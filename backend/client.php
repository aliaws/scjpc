<?php

function search($request) {
  $action = $request['action'] ?? ''; // Check if 'action' key exists
  $data = call_user_func_array('perform_' . $action, [$request]);
  $data["per_page"] = $request["per_page"] ?? 50;
  $data["page_number"] = $request["page_number"] ?? 1;
  return $data;
}

function make_search_api_call($api_url) {
  $headers = ["Content-Type: application/json", "security_key: " . get_option('scjpc_client_auth_key')];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);

}

function perform_jpa_search($request): array {
  $request['action'] = 'single-jpa';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['search_query'] = $api_url;
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}


function perform_multiple_jpa_search($request): array {
  $request['action'] = 'multiple-jpa';
  $request['jpa_number'] = upload_and_read_file($request);
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['per_page'] = $request["per_page"];
  return $response;
}

function perform_advanced_pole_search($request): array {
  $request['action'] = 'advanced-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['per_page'] = $request["per_page"];
  return $response;
}

function perform_quick_pole_search($request) {
  $request['action'] = 'single-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}

function perform_pole_detail($request) {
  $request['action'] = 'pole-detail';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-detail?" . http_build_query($request);
  return [
    'result_per_page' => 1,
    'page_number' => 1,
    'total_records' => 1,
    'per_page' => 1,
    'results' => [make_search_api_call($api_url)]
  ];

}

function perform_jpa_detail_search($request) {
  $request['action'] = 'jpa-detail';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}

function perform_multiple_pole_search($request) {
  $request['action'] = 'multiple-pole';
  $request['pole_number'] = upload_and_read_file($request);
  $request['active_only'] = !empty($request['active_only']) ? 'true' : 'false';

  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}

function perform_website_doc_search($request): array {
  return SEARCH_RESULT;
}


function get_migration_logs(): array {
  return MIGRATION_LOGS;
}

function get_pole_result($request): array {
  $request['action'] = 'multiple-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}


function upload_and_read_file($request): string {
  $searchable_numbers = [];
  if (empty($_FILES)) {
    return implode(" ", $searchable_numbers);
  }
  $contains_headers = isset($request['contains_header']);
  $file_name = preg_replace('/xls$/', 'xlsx', $_FILES['uploaded_file']['name']);
  $upload_file_path = WP_CONTENT_DIR . '/uploads/scjpc-exports/' . $file_name;
  if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $upload_file_path)) {
    include_once SCJPC_PLUGIN_PATH . 'excel-reader/SimpleXLSX.php';
    if ($xlsx = SimpleXLSX::parse($upload_file_path)) {
      $searchable_numbers = array_column($xlsx->rows(), 0);
      if ($contains_headers) {
        unset($searchable_numbers[0]);
      }
    } else {
      echo SimpleXLSX::parseError();
    }
    unlink($upload_file_path);
  }
  return implode(" ", $searchable_numbers);
}