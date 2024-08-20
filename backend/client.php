<?php

function search_scjpc($request) {
  $action = $request['action'] ?? 'ads_nothing'; // Check if 'action' key exists
  $data = call_user_func_array('perform_' . $action, [$request]);
  $data["per_page"] = $request["per_page"] ?? 50;
  $data["page_number"] = $request["page_number"] ?? 1;
  return $data;
}

function make_search_api_call($api_url, $append_search_query = false) {
  $headers = ["Content-Type: application/json", "security_key: " . get_option('scjpc_client_auth_key')];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  $parsed_response = json_decode($response, true);
  if ($append_search_query) {
    $parsed_response["search_query"] = prepare_search_query($api_url, $parsed_response["total_records"]);
  }
  return $parsed_response;

}

function make_post_api_call($api_url, $body = []) {
  $headers = ["Content-Type: application/json", "security_key: " . get_option('scjpc_client_auth_key')];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true); // Set request method to POST
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body)); // Attach the JSON-encoded body

  $response = curl_exec($ch);
  if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    curl_close($ch);
    return ['error' => $error_msg];
  }

  curl_close($ch);
  $parsed_response = json_decode($response, true);
  return $parsed_response;
}

function perform_jpa_search($request): array {
  $request['action'] = 'single-jpa';
  $request['columns'] = implode(",", array_keys(JPAS_KEYS));
  $request['contains_headers'] = !empty($request['contains_headers']) ? 'true' : 'false';
  $request['active_only'] = !empty($request['active_only']) ? 'true' : 'false';

  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}


function perform_multiple_jpa_search($request): array {
  $request['action'] = 'multiple-jpa';
  $upload = upload_and_read_file($request);

  $request['jpa_number'] = $upload["numbers"] ?? [];
  $request['s3_key'] = $upload["s3_key"] ?? "";
//  $request['contains_headers'] = !!$upload["headers"];
  $request['columns'] = implode(",", array_keys(JPAS_KEYS));

  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);
  $response['s3_key'] = $request['s3_key'];
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['per_page'] = $request["per_page"];
  return $response;
}

function perform_advanced_pole_search($request): array {
  $request['action'] = 'advanced-pole';
  $request['location'] = $request['location_encoded'] ?? '';
  $request['columns'] = implode(",", array_keys(POLES_KEYS));
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['per_page'] = $request["per_page"];
  return $response;
}

function perform_quick_pole_search($request) {
  $request['action'] = 'single-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}

function perform_pole_detail($request): array {
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
  $response = make_search_api_call($api_url, true);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}

function perform_multiple_pole_search($request) {
  $request['action'] = 'multiple-pole';
  $upload = upload_and_read_file($request);
  $request['pole_number'] = $upload["numbers"] ?? [];
  $request['s3_key'] = $upload["s3_key"] ?? "";
//  $request['contains_headers'] = !!$upload["headers"];
  $request['active_only'] = !empty($request['active_only']) ? 'true' : 'false';
  $request['contains_headers'] = !empty($request['contains_headers']) ? 'true' : 'false';

  $columns = !empty($request['choices']) ? $request['choices'] : [];
  $multi_keys = ["members_code", "antenna_info"];
  foreach ($multi_keys as $multi_key) {
    if (in_array($multi_key, $columns)) {
      $columns = array_merge($columns, array_keys(EXTRA_COLUMNS_LABELS[$multi_key]));
      unset($columns[$multi_key]);
      if (($key = array_search($multi_key, $columns)) !== false) {
        unset($columns[$key]);
      }
    }
  }
  unset($request["choices"]);
  $request['columns'] = implode(",", $columns);
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['s3_key'] = $request['s3_key'];
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}


function get_pole_result($request): array {
  $request['action'] = 'multiple-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}


function upload_and_read_file($request): array|string {
  $searchable_numbers = [];
  $contains_headers = isset($request['contains_header']);
  $s3_key = $request['s3_key'] ?? '';
  if (empty($_FILES) || $s3_key != '') {
    return ['s3_key' => $s3_key, 'headers' => $contains_headers];
//    return implode( " ", $searchable_numbers);
  }
  $file_name = preg_replace('/xls$/', 'xlsx', $_FILES['uploaded_file']['name']);
  $upload_file_path = WP_CONTENT_DIR . '/uploads/scjpc-exports/' . $file_name;
  $s3_key = "";
//  $contains_headers = false;
  if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $upload_file_path)) {
    $time = time();
    $s3_key = "search/{$time}-{$file_name}";
    $client = getS3Client();

    $result = $client->putObject([
      'Bucket' => 'scjpc-data',
      'Key' => $s3_key,
      'SourceFile' => $upload_file_path,
    ]);

    include_once SCJPC_PLUGIN_PATH . 'excel-reader/SimpleXLSX.php';
    if ($xlsx = SimpleXLSX::parse($upload_file_path)) {
      $searchable_numbers = array_column($xlsx->rows(), 0);
//      if ($contains_headers) {
//        unset($searchable_numbers[0]);
//      }
    } else {
      echo SimpleXLSX::parseError();
    }
    unlink($upload_file_path);
  }
//  if (count($searchable_numbers) === 400) {
//    return ["numbers" => implode(" ", $searchable_numbers)];
//  }
  return ["s3_key" => $s3_key, "headers" => $contains_headers];
}

function perform_activate() {
  //Not sure why wp is giving
}

function perform_ads_nothing() {
  // do nothing
}

function get_export_status($request) {
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/data-export?" . http_build_query($request);
  return make_search_api_call($api_url);
}

function update_jpa_search_pdf($request) {
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-pdf-update";
  $body = [
    "jpa_unique_id" => $request['jpa_unique_id'],
    "pdf_s3_key" => $request['s3_key']
  ];
  return make_post_api_call($api_url, $body);
}

function prepare_search_query($api_url, $total_records) {
  return $api_url . "&export=1&total_records=" . $total_records;
}

function getS3Client() {
  return new \Aws\S3\S3Client([
    'region' => 'us-east-2',
    'version' => '2006-03-01',
    'credentials' => [
      'key' => get_option('scjpc_aws_key'),
      'secret' => get_option('scjpc_aws_secret'),
    ]
  ]);
}

