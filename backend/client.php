<?php
/**
 * this method returns the .env file data
 * @return bool|array
 */
function getEnvData(): bool|array {
  $env = ROOT_DIR . '/.env';
  if (file_exists($env)) {
    return parse_ini_file($env);
  } else {
    return [];
  }
}


function search($request) {
//  echo "<pre>" . print_r($request, true) . "</pre>";
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
  $env = getEnvData();
  $headers = ['Content-Type: application/json', "security_key: {$env['SECURITY_KEY']}"];
  $request['action'] = 'single-jpa';
  $api_url = "{$env['ELASTICSEARCH_HOST']}/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = [50, 100, 200, 500, 1000];
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}


function perform_multiple_jpa_search($request): array {
  echo "<pre>" . print_r($request, true) . "</pre>";
  $env = getEnvData();

  $headers = ['Content-Type: application/json', "security_key: {$env['SECURITY_KEY']}"];
  $request['action'] = 'multiple-jpa';
  $api_url = "{$env['ELASTICSEARCH_HOST']}/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, $headers);
  $response['result_per_page'] = [50, 100, 200, 500, 1000];
  $response['per_page'] = $request["per_page"];
  return $response;
}

function perform_advance_pole_search($request): array {
  return SEARCH_RESULT;
}

function perform_quick_pole_search($request): array {

  return POLE_RESULT;
}

function perform_website_doc_search($request): array {
  return SEARCH_RESULT;
}


function get_migration_logs(): array {
  return MIGRATION_LOGS;
}

function get_pole_result(): array {
  return POLE_RESULT;
}