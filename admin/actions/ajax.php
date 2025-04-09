<?php

add_action('wp_ajax_create_setting', 'handle_setting');
add_action('wp_ajax_update_setting', 'handle_setting');
add_action('wp_ajax_delete_setting', 'delete_setting');
add_action('wp_ajax_delete_email_tag', 'delete_email_tag');
add_action('wp_ajax_get_settings_html', 'scjpc_get_settings_html');

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

function scjpc_get_settings_html() {
    $settings = get_option('scjpc_settings', []);

    ob_start();
    foreach ($settings as $setting) {
        $key = esc_attr($setting['key']);
        $emails = array_filter(array_map('trim', explode(',', $setting['value'])));

        echo "<tr>";
        echo "<td>{$key}</td>";
        echo "<td>";
        foreach ($emails as $email) {
            echo "<span class='tag badge bg-primary me-1 mb-1 p-2'>";
            echo esc_html($email);
            echo " <i class='fas fa-times ms-2 text-white delete-tag' data-key='{$key}' data-email='" . esc_attr($email) . "' style='cursor:pointer;'></i>";
            echo "</span>";
        }
        echo "</td>";
        echo "<td>";
        echo "<button class='btn btn-warning btn-sm edit-setting' data-key='{$key}' data-value='" . esc_attr($setting['value']) . "'><i class='fas fa-edit'></i></button> ";
        echo "<button class='btn btn-danger btn-sm delete-setting' data-key='{$key}'><i class='fas fa-trash-alt'></i></button>";
        echo "</td>";
        echo "</tr>";
    }
    $html = ob_get_clean();

    wp_send_json_success(['html' => $html]);
}
