<?php
include_once(SCJPC_PLUGIN_PATH . 'aws/aws-autoloader.php');
include_once(SCJPC_PLUGIN_FRONTEND_BASE . 'functions.php');
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

function scjpc_website_doc_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/website_doc_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_website_doc_search', 'scjpc_website_doc_search');

add_action('admin_post_nopriv_make_export_data_call', 'make_export_data_call');
add_action('wp_ajax_make_export_data_call', 'make_export_data_call');
add_action('wp_ajax_nopriv_make_export_data_call', 'make_export_data_call');

function make_export_data_call() {
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

add_action('admin_post_nopriv_jpa_search', 'ajax_jpa_search');
add_action('wp_ajax_jpa_search', 'ajax_jpa_search');
add_action('wp_ajax_nopriv_jpa_search', 'ajax_jpa_search');


function ajax_jpa_search() {
  include_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/jpa_results.php';
  wp_die();
}

add_action('admin_post_nopriv_multiple_jpa_search', 'ajax_mulitple_jpa_search');
add_action('wp_ajax_multiple_jpa_search', 'ajax_mulitple_jpa_search');
add_action('wp_ajax_nopriv_multiple_jpa_search', 'ajax_mulitple_jpa_search');


function ajax_mulitple_jpa_search() {
  echo "<pre>" . count($_GET) . "==" . count($_POST) . "==" . count($_FILES) . print_r($_GET, true) . print_r($_POST, true) . print_r($_FILES, true) . "</pre>";
  include_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/jpa_results.php';
  wp_die();
}
