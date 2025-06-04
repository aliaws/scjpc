<?php

function search_scjpc($request) {
  $action = $request['action'] ?? 'ads_nothing';
  $data = call_user_func_array('perform_' . $action, [$request]);
  $data["per_page"]    = $request["per_page"] ?? 50;
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
//  scjpc_internal_log($parsed_response, "parsed response");
  if ( $append_search_query ) {
    $total_records = $parsed_response["total_records"] ?? null;
    $parsed_response["search_query"] = prepare_search_query($api_url, $total_records);
  }
  return $parsed_response;

}

function make_api_call($api_url, $body = [], $method = "POST") {
  $headers = ["Content-Type: application/json", "security_key: " . get_option('scjpc_client_auth_key')];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body)); // Attach the JSON-encoded body

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $parsed_response = [];

  if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    $parsed_response =  ['error' => $error_msg, "status_code" =>  $http_code];
  }

  curl_close($ch);

  if(!isset($parsed_response["error"])) {
    $parsed_response = json_decode($response, true);
    $parsed_response["status_code"] = $http_code;
  }

  return $parsed_response;
}

function perform_jpa_search($request): array {
  $request['action']  = 'single-jpa';
  $request['columns'] = implode(",", array_keys(JPAS_KEYS));
  $request['contains_headers'] = ! empty ( $request['contains_headers'] ) ? 'true' : 'false';
  $request['active_only']      = ! empty ( $request['active_only'] ) ? 'true' : 'false';

  $api_url = trim(get_option('scjpc_es_host'), '/') . "/jpa-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);

  $response = scjpc_add_query_transient_log( $request, $response );

  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}

function scjpc_add_query_transient_log( $request, $response, $nested = true ): array {

  if ( $query_transient_id = scjpc_get_query_transient_id( $request ) ) {

    if ( $query_transient = scjpc_set_query_transient( $query_transient_id, $request, $nested ) ) {
      $response['transient']    = $query_transient;
      $response['query_id']     = $query_transient_id;
      $response['redirect_url'] = scjpc_get_query_transient_url( $query_transient_id );
    }

  }
  return $response;
}

function scjpc_get_query_transient_url( $query_transient_id ) {
  if ( $query_transient = scjpc_get_query_transient( $query_transient_id ) ) {
    if ( ! empty( $query_transient[ count( $query_transient ) - 2] ) && ! empty ( $query_transient[ count( $query_transient ) - 2]['original'] ) ) {
      $redirect_url = $query_transient[ count( $query_transient ) - 2]['original'];
      parse_str($redirect_url, $query_params);
      if ( isset ( $query_params['page_slug'] ) ){
        $redirect_url = $query_params['page_slug'];
        return "/" . $redirect_url . '?' . http_build_query( $query_params );
      }
      return $redirect_url;
    }
  }
  return false;
}


function scjpc_set_query_transient( $query_id, $request, $nested = true ) {
  $transient      = get_transient( $query_id );
  $original_query = http_build_query($request);
  $filtered_query = scjpc_remove_filters_from_request( $request );
  $transient_value = ['original' => $original_query, 'filtered' => $filtered_query];
  $set = false;

  if ( ! empty ( $request ) && isset ( $request['go_back'] ) && $nested ) {

    if ( $transient ) {

      unset( $transient[array_key_last( $transient )] );
      unset( $request['go_back'] );
      $set = true;
    }
  } else {

    if ( ! $transient || ! $nested ) {

      $transient   = [$transient_value];

    } else {

      if ( scjpc_is_new_search_query( $filtered_query, $transient ) ) {

        $transient[] = $transient_value;

      } else {

        $transient[array_key_last($transient)] = $transient_value;

      }
    }
    $set = true;
  }

  if ( $set ) {
    set_transient( $query_id, $transient, HOUR_IN_SECONDS * 6 );
  }
  return $transient;
}

function scjpc_get_query_transient( $query_id ) {
  if ( $transient = get_transient( $query_id ) ) {
    return $transient;
  }
  return false;
}

function scjpc_get_query_transient_id( $request ) {

  if ( ! empty ( $request['query_id'] ) ) {
    // scjpc_internal_log("found query transient ID");

    return $request['query_id'];
  }

  return false;
}


function scjpc_remove_filters_from_request( $request ) {

  unset( $request['sort_key'] );
  unset( $request['sort_order'] );
  unset( $request['per_page'] );
  unset( $request['page_number'] );
  unset( $request['search_query'] );


  return http_build_query( $request );
}

function scjpc_is_new_search_query( $filtered_query, $transient ) {
  $last_set = $transient[array_key_last( $transient )];
  if ( ! empty ( $last_set['filtered'] ) ) {
    return $last_set['filtered'] != $filtered_query;
  }
  return true;
}


function scjpc_get_last_search_query( $query_id ) {
  if ( $query_transient = scjpc_get_query_transient( $query_id ) ) {
    $redirect_url = $query_transient[ array_key_last( $query_transient ) ][ 'original' ];

    parse_str( $redirect_url, $query_params );

    if ( isset ( $query_params['page_slug'] ) ){
      $redirect_url = $query_params['page_slug'];
      $query_params[ 'query_id' ] = $query_id;
      return "/" . $redirect_url . '?' . http_build_query( $query_params );
    }
    return $redirect_url;

  }
  return false;
//  return scjpc_get_query_transient_url( $query_id );
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

  $response = scjpc_add_query_transient_log( $request, $response );

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

  $response = scjpc_add_query_transient_log( $request, $response );


  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['per_page'] = $request["per_page"];
  return $response;
}

  function perform_quick_pole_search( $request ) {
  // scjpc_internal_log("perform_quick_pole_search");
  $request['action']  = 'single-pole';
  $request['columns'] = implode(",", array_keys(POLES_KEYS));
  $api_url            = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response           = make_search_api_call($api_url, true);

  // scjpc_internal_log("performing quick pole search");

  $response = scjpc_add_query_transient_log( $request, $response );

  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id']         = $response['last_id'] ?? '';
  return $response;
}

function perform_pole_detail($request): array {
  // scjpc_internal_log("perform_pole_detail");
  $request['action'] = 'pole-detail';
  $api_url  = trim(get_option('scjpc_es_host'), '/') . "/pole-detail?" . http_build_query($request);
  $response = [
    'result_per_page' => 1,
    'page_number'     => 1,
    'total_records'   => 1,
    'per_page'        => 1,
    'results'         => [make_search_api_call($api_url, true)]
  ];
  return scjpc_add_query_transient_log( $request, $response, true );

}

function perform_jpa_detail_search($request) {
  // scjpc_internal_log("perform_jpa_detail_search");
  $request['action'] = 'jpa-detail';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);

  $response = scjpc_add_query_transient_log( $request, $response, true );

  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}

function perform_multiple_pole_search($request) {
  // scjpc_internal_log("perform_multiple_pole_search");
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

  $response = scjpc_add_query_transient_log( $request, $response );

  $response['result_per_page'] = RESULTS_PER_PAGE;
  $response['s3_key'] = $request['s3_key'];
  $_REQUEST['last_id'] = $response['last_id'] ?? '';
  return $response;
}


function get_pole_result($request): array {
  // scjpc_internal_log("get_pole_result");
  $request['action'] = 'multiple-pole';
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/pole-search?" . http_build_query($request);
  $response = make_search_api_call($api_url, true);
  $response['result_per_page'] = RESULTS_PER_PAGE;
  $_REQUEST['last_id'] = $response['last_id'];
  return $response;
}


function upload_and_read_file($request) {
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
  $api_url = trim(get_option('scjpc_es_host'), '/') . "/" . API_NAMESPACE . "/jpa-pdf-update";
  $body = [
    "jpa_unique_id" => $request['jpa_unique_id'],
    "pdf_s3_key" => $request['s3_key']
  ];
  return make_api_call($api_url, $body);
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

