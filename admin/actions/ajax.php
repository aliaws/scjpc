<?php

add_action('wp_ajax_create_setting', 'handle_setting');
add_action('wp_ajax_update_setting', 'handle_setting');
add_action('wp_ajax_delete_setting', 'delete_setting');
add_action('wp_ajax_delete_email_tag', 'delete_email_tag');
add_action('wp_ajax_get_settings_html', 'scjpc_get_settings_html');
add_action('wp_ajax_progress', 'handle_progress');

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

function delete_email_tag() {
    $api_url = $_POST['api_url'] ?? '';

    if (empty($api_url)) {
        wp_send_json_error(['message' => 'Missing API URL']);
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

add_action('wp_ajax_render_setting_row', 'render_setting_row_callback');

function render_setting_row_callback() {
    if (empty($_POST['key']) || empty($_POST['value'])) {
        wp_send_json_error('Missing key or value');
    }

    $key = sanitize_text_field($_POST['key']);
    $value = sanitize_text_field($_POST['value']);

    $setting = compact('key', 'value');

    $template_path = SCJPC_PLUGIN_ADMIN_BASE . 'partials/_settings/_setting_row.php';

    if (!file_exists($template_path)) {
        wp_send_json_error('Template file not found: ' . $template_path);
    }

    ob_start();
    include $template_path;
    $html = ob_get_clean();

    echo $html;
    wp_die();
}

function handle_progress() {
    $api_url = rtrim(get_option('scjpc_es_host'), '/') . '/' . API_NAMESPACE . '/es-indexing-progress';
    $response = make_search_api_call($api_url);

    $progress = isset($response['progress']) ? (float) $response['progress'] : 0;
    $interval_seconds = isset($response['interval_seconds']) ? (int) $response['interval_seconds'] : 0;

    wp_send_json_success([
        'progress' => $progress,
        'interval_seconds' => $interval_seconds,
    ]);
}

