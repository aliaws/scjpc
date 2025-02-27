<?php

add_action('wp_ajax_create_setting', 'handle_setting');
add_action('wp_ajax_update_setting', 'handle_setting');
add_action('wp_ajax_delete_setting', 'delete_setting');

function handle_setting() {
  $api_url = $_POST['api_url'] ?? '';
  $body = !empty($_POST['body']) ? json_decode(stripslashes($_POST['body']), true) : [];

  if (empty($api_url) || empty($body)) {
      wp_send_json_error(['message' => 'Invalid parameters']);
  }

  $method = $_POST['action'] === 'create_setting' ? 'POST' : 'PUT';
  $response = make_api_call($api_url, $body, $method);

  if (isset($response['error'])) {
      wp_send_json_error(['message' => $response['error']]);
  }

  if (!empty($response['success']) && !$response['success']) {
      wp_send_json_error(['message' => $response['data']['message'] ?? 'Unknown error']);
  }

  wp_send_json_success($response);
}

function delete_setting() {
  $api_url = $_POST['api_url'] ?? '';
  if (empty($api_url)) {
      wp_send_json_error(['message' => 'Invalid parameters']);
  }
  $response = make_api_call($api_url, [], 'DELETE');
  if (isset($response['error'])) {
      wp_send_json_error(['message' => $response['error']]);
  }
  if (!empty($response['success']) && !$response['success']) {
      wp_send_json_error(['message' => $response['data']['message'] ?? 'Unknown error']);
  }
  wp_send_json_success($response);
}

function make_api_call($url, $body = [], $method = 'POST') {
  $headers = ["Content-Type: application/json", "security_key: " . get_option('scjpc_client_auth_key')];
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
  if (!empty($body)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $error = curl_error($ch);
  curl_close($ch);

  if ($error) return ['error' => $error];

  $decoded_response = json_decode($response, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
      return ['error' => 'Invalid API response'];
  }

  if ($http_code >= 400) {
      return ['error' => $decoded_response['error'] ?? "HTTP error $http_code"];
  }

  return $decoded_response;
}



