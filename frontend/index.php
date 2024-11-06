<?php
include_once(SCJPC_PLUGIN_PATH . 'aws/aws-autoloader.php');
include_once(SCJPC_PLUGIN_FRONTEND_BASE . 'functions.php');
include_once(SCJPC_PLUGIN_FRONTEND_BASE . 'relevanssi.php');

function scjpc_prepare_date_format($db_date, $input_format, $output_format): string {
  $formatted_date = DateTime::createFromFormat($input_format, $db_date);
  if (!$formatted_date) {
    $formatted_date = DateTime::createFromFormat('Y-m-d', $db_date);
  }
  return $formatted_date->format($output_format);
}

function scjpc_database_update_information(): string {
  $migration_date = scjpc_prepare_date_format(get_option('scjpc_migration_date'), 'd/m/Y', 'm/d/Y');
  $latest_billed_jpa = scjpc_prepare_date_format(get_option('scjpc_latest_billed_jpa_date'), 'm/y', 'm/y');
  $latest_billed_jpa_pdf = scjpc_prepare_date_format(get_option('scjpc_latest_billed_jpa_pdf_date'), 'm/y', 'm/y');
  return "Last database update on: $migration_date (B/S $latest_billed_jpa)<br>PDF Finals available from: 2003 to B/S $latest_billed_jpa_pdf";
}


add_action('admin_post_nopriv_make_export_data_call', 'make_export_data_call');
add_action('wp_ajax_make_export_data_call', 'make_export_data_call');
add_action('wp_ajax_nopriv_make_export_data_call', 'make_export_data_call');

function make_export_data_call() {
//  echo "<pre>GET=" . count($_GET) . "==POST=" . count($_POST) . "==FILES=" . count($_FILES) . print_r($_GET, true) . print_r($_POST, true) . print_r($_FILES, true) . "</pre>";

  $api_url = rtrim(get_option('scjpc_es_host'), '/') . "/data-export";
  $headers = ["Content-Type: application/json", "security_key: " . get_option('scjpc_client_auth_key')];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($_POST));
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  echo $response;
  wp_die();
}

add_action('admin_post_nopriv_multiple_jpa_search', 'ajax_jpa_search');
add_action('wp_ajax_multiple_jpa_search', 'ajax_jpa_search');
add_action('wp_ajax_nopriv_multiple_jpa_search', 'ajax_jpa_search');

add_action('admin_post_nopriv_jpa_search', 'ajax_jpa_search');
add_action('wp_ajax_jpa_search', 'ajax_jpa_search');
add_action('wp_ajax_nopriv_jpa_search', 'ajax_jpa_search');


function ajax_jpa_search() {
  include_once SCJPC_PLUGIN_FRONTEND_BASE . 'results/jpa_results.php';
  wp_die();
}


add_action('admin_post_nopriv_quick_pole_search', 'ajax_pole_search');
add_action('wp_ajax_quick_pole_search', 'ajax_pole_search');
add_action('wp_ajax_nopriv_quick_pole_search', 'ajax_pole_search');

add_action('admin_post_nopriv_advanced_pole_search', 'ajax_pole_search');
add_action('wp_ajax_advanced_pole_search', 'ajax_pole_search');
add_action('wp_ajax_nopriv_advanced_pole_search', 'ajax_pole_search');

add_action('admin_post_nopriv_pole_detail', 'ajax_pole_search');
add_action('wp_ajax_pole_detail', 'ajax_pole_search');
add_action('wp_ajax_nopriv_pole_detail', 'ajax_pole_search');


add_action('admin_post_nopriv_jpa_detail_search', 'ajax_pole_search');
add_action('wp_ajax_jpa_detail_search', 'ajax_pole_search');
add_action('wp_ajax_nopriv_jpa_detail_search', 'ajax_pole_search');


function ajax_pole_search() {
//  echo "<pre>GET=" . count($_GET) . "==POST=" . count($_POST) . "==FILES=" . count($_FILES) . "==REQUEST=" . count($_REQUEST) . print_r($_GET, true) . print_r($_POST, true) . print_r($_FILES, true) . print_r($_REQUEST, true) . "</pre>";
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "results/pole_results.php";
  wp_die();
}

add_action('admin_post_nopriv_multiple_pole_search', 'ajax_multiple_pole_search');
add_action('wp_ajax_multiple_pole_search', 'ajax_multiple_pole_search');
add_action('wp_ajax_nopriv_multiple_pole_search', 'ajax_multiple_pole_search');


function ajax_multiple_pole_search() {
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "results/multiple_pole_results.php";
  wp_die();
}

function get_export_status_callback() {
  if (function_exists('get_export_status')) {
    $response = get_export_status($_GET);
    if (!empty($response) && !empty($response['status'])) {
      $response_array = download_export_array($response);
      wp_send_json_success($response_array);
    }
  } else {
    wp_send_json_error('Function not found.');
  }
}

add_action('wp_ajax_get_export_status', 'get_export_status_callback');
add_action('wp_ajax_nopriv_get_export_status', 'get_export_status_callback');


add_shortcode('scjpc_reveal_contact', 'scjp_reveal_contact_callback');
function scjp_reveal_contact_callback($attributes): string {
  $contact_info = isset($attributes['contact']) ? esc_attr($attributes['contact']) : '';
  $label = isset($attributes['label']) ? esc_html($attributes['label']) : 'Click to Reveal';

  return "<span class='reveal-contact' data-contact='$contact_info' style='cursor: pointer;'>$label</span>";
}


add_action('template_redirect', 'authorize_search_page');
function authorize_search_page(): void {
  if (isset($_GET['s'])) {
    if (!is_user_logged_in()) {
      wp_redirect("/login?redirect_to=" . get_home_url() . "?s=");
      exit;
    }
  }
}

