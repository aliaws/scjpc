<?php
include_once(SCJPC_PLUGIN_PATH . 'aws/aws-autoloader.php');
include_once(SCJPC_PLUGIN_FRONTEND_BASE . 'functions.php');
function scjpc_database_update_information(): string {
  $migration_date = get_option('scjpc_migration_date');
  $migration_date = DateTime::createFromFormat('d/m/Y', $migration_date);
  $migration_date = $migration_date->format('m/d/Y');
  $latest_billed_jpa = get_option('scjpc_latest_billed_jpa_date');
  $latest_billed_jpa_pdf = get_option('scjpc_latest_billed_jpa_pdf_date');
  return "Last database update on: $migration_date (B/S $latest_billed_jpa)<br>PDF Finals available from: 2003 to B/S $latest_billed_jpa_pdf";
}

function scjpc_jpa_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/jpa_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_jpa_search', 'scjpc_jpa_search');

function scjpc_multiple_jpa_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/multiple_jpa_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_multiple_jpa_search', 'scjpc_multiple_jpa_search');

function scjpc_quick_pole_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/quick_pole_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_quick_pole_search', 'scjpc_quick_pole_search');

function scjpc_advanced_pole_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/advanced_pole_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_advanced_pole_search', 'scjpc_advanced_pole_search');
function scjpc_multiple_pole_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/multiple_pole_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_multiple_pole_search', 'scjpc_multiple_pole_search');

function scjpc_pole_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/jpa_detail_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_pole_search', 'scjpc_pole_search');

function scjpc_pole_detail() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/pole_detail.php";
  return ob_get_clean();
}

add_shortcode('scjpc_pole_detail', 'scjpc_pole_detail');

function scjpc_download_export() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/download_export.php";
  return ob_get_clean();
}

add_shortcode('scjpc_download_export', 'scjpc_download_export');

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


function scjp_reveal_contact_callback($attributes): string {
  $contact_info = isset($attributes['contact']) ? esc_attr($attributes['contact']) : '';
  $label = isset($attributes['label']) ? esc_html($attributes['label']) : 'Click to Reveal';

  return "<span class='reveal-contact' data-contact='$contact_info' style='cursor: pointer;'>$label</span>";
}

add_shortcode('scjpc_reveal_contact', 'scjp_reveal_contact_callback');
