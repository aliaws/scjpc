<?php

function search($request) {
  $action = $request['action'] ?? ''; // Check if 'action' key exists
  $data = call_user_func_array('perform_' . $action, [$request]);
  $data["per_page"] = $_GET["per_page"];
  $data["page_number"] = $_GET["page_number"];
  return $data;
}

function make_search_api_call($api_url, $headers) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);

}

function perform_jpa_search($request): array {
  $headers = ['Content-Type: application/json', "security_key: " . get_option('scjpc_client_auth_key')];
  $request['action'] = 'single-jpa';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}


function perform_multiple_jpa_search($request): array {
  $headers = ['Content-Type: application/json', "security_key: " . get_option('scjpc_client_auth_key')];
  $request['action'] = 'multiple-jpa';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['per_page'] = $request["per_page"];
  return $response;
}

function perform_advanced_pole_search($request): array {
  $headers = ['Content-Type: application/json', "security_key: " . get_option('scjpc_client_auth_key')];
  $request['action'] = 'advanced-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['per_page'] = $request["per_page"];
  return $response;
}

function perform_quick_pole_search($request) {
  $headers = ['Content-Type: application/json', "security_key: " . get_option('scjpc_client_auth_key')];
  $request['action'] = 'single-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}

function perform_multiple_pole_search($request) {
  $headers = ['Content-Type: application/json', "security_key: " . get_option('scjpc_client_auth_key')];
  $request['action'] = 'multiple-pole';
  $request['active_only'] = !empty($request['active_only']) ? 'true' : 'false';

  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}

function perform_website_doc_search($request): array {
  return SEARCH_RESULT;
}


function get_migration_logs(): array {
  return MIGRATION_LOGS;
}

function get_pole_result($request): array {
  $headers = ['Content-Type: application/json', "security_key: " . get_option('scjpc_client_auth_key')];
  $request['action'] = 'multiple-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}