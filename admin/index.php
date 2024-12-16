<?php
require_once SCJPC_PLUGIN_ADMIN_BASE . 'migration_logs.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'jpa_contacts.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'jpa_contact_request.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'pole_contacts.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'create_users.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'api.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'functions.php';


// add_action('init', 'scjpc_register_post_type_migration_logs');
//function scjpc_register_post_type_migration_logs() {
//  $supports = [
//    'title', // post title
//    'editor', // post content
////    'author', // post author
////    'thumbnail', // featured images
////    'excerpt', // post excerpt
//    'custom-fields', // custom fields
////    'comments', // post comments
////    'revisions', // post revisions
////    'post-formats', // post formats
//  ];
//  $labels = array(
//    'name' => _x('Migration Logs', 'plural', 'scjpc'),
//    'singular_name' => _x('Migration Log', 'singular', 'scjpc'),
//    'menu_name' => _x('Migration Logs', 'admin menu', 'scjpc'),
//    'name_admin_bar' => _x('Migration Logs', 'admin bar', 'scjpc'),
////    'add_new' => _x('Add New', 'add new'),
////    'add_new_item' => __('Add New news'),
////    'new_item' => __('New news', 'scjpc'),
////    'edit_item' => __('Edit news'),
//    'view_item' => __('View Migration Logs', 'scjpc'),
//    'all_items' => __('Database Migration Logs', 'scjpc'),
//    'search_items' => __('Search Migration Logs', 'scjpc'),
//    'not_found' => __('No Migration Logs found.', 'scjpc'),
//  );
//  $args = array(
//    'supports' => $supports,
//    'labels' => $labels,
//    'public' => true,
//    'query_var' => true,
//    'rewrite' => array('slug' => 'migration-logs'),
//    'has_archive' => true,
//    'hierarchical' => false,
//    'show_in_menu' => 'scjpc', // Add this line to place under the "Scjpc" menu
//  );
//  register_post_type('migration_logs', $args);
//}
//
//add_filter('manage_migration_logs_posts_columns', 'scjpc_migrations_logs_columns');
//function scjpc_migrations_logs_columns($columns) {
//  $scjpc_columns = [
//    'status' => __('Status', 'scjpc'),
//    'jpas_s3_key' => __('JPAs S3 Key', 'scjpc'),
//    'poles_s3_key' => __('Poles S3 Key', 'scjpc'),
//    'request_host' => __('Request Host', 'scjpc'),
////    'job_datetime' => __('Job Datetime', 'scjpc'),
//    'date' => __('Date', 'scjpc'),
//  ];
//  return array_merge(array_slice($columns, 0, 2), $scjpc_columns);
//}
//
//add_action('manage_migration_logs_posts_custom_column', 'scjpc_fill_migrations_logs_columns', 10, 2);
//function scjpc_fill_migrations_logs_columns($column, $post_id) {
//  $scjpc_columns = get_scjpc_columns_array();
//  if (isset($scjpc_columns[$column])) {
//    echo get_post_meta($post_id, $scjpc_columns[$column], true);
//  }
//}

//function get_scjpc_columns_array() {
//  return [
//    'status' => 'scjpc_status',
//    'jpas_s3_key' => 'scjpc_jpas_s3_key',
//    'jpas_created' => 'scjpc_no_of_jpas_created',
//    'jpas_updated' => 'scjpc_no_of_jpas_updated',
//    'poles_s3_key' => 'scjpc_poles_s3_key',
//    'poles_created' => 'scjpc_no_of_poles_created',
//    'poles_updated' => 'scjpc_no_of_poles_updated',
////    'job_datetime' => 'scjpc_job_datetime',
//    'request_host' => 'scjpc_request_host',
//  ];
//}

function scjpc_options_menu_migration_dates(): void {
  register_setting('scjpc-migration-dates-settings', 'scjpc_migration_date');
  register_setting('scjpc-migration-dates-settings', 'scjpc_latest_billed_jpa_date');
  register_setting('scjpc-migration-dates-settings', 'scjpc_latest_billed_jpa_pdf_date');
}

function scjpc_options_menu_settings(): void {
  register_setting('scjpc-settings-group', 'scjpc_es_host');
  register_setting('scjpc-settings-group', 'scjpc_client_auth_key');
  register_setting('scjpc-settings-group', 'scjpc_aws_cdn');
  register_setting('scjpc-settings-group', 'scjpc_aws_key');
  register_setting('scjpc-settings-group', 'scjpc_aws_secret');
}

function scjpc_options_menu_settings_page(): void {
  ob_start();
  include_once SCJPC_PLUGIN_ADMIN_BASE . 'templates/settings_group.php';
  echo ob_get_clean();
}

add_action('admin_menu', 'scjpc_custom_admin_menu');

function scjpc_custom_admin_menu() {
  // Add the main menu item for jpa
  add_menu_page(
    __('Dashboard', 'scjpc'),   // Page title
    'SCJPC',                     // Menu title
    'manage_options',          // Capability
    'scjpc',         // Menu slug
    'admin_scjpc_dashboard',                        // Function (empty for now)
    'dashicons-admin-generic', // Icon URL
    6                          // Position
  );
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Jpa', 'scjpc'), // Page title
    'Dashboard',         // Menu title
    'manage_options',          // Capability
    'scjpc',         // Menu slug
    'admin_scjpc_dashboard'   // Function to display page content
  );
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Jpa', 'scjpc'), // Page title
    'Update JPA PDF',         // Menu title
    'manage_options',          // Capability
    'update-jpa-pdf',         // Menu slug
    'admin_jpa_search'   // Function to display page content
  );
  // Add the Export Requests submenu item
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Export Excel/Csv Processed Requests', 'scjpc'), // Page title
    'Export Excel/Csv Processed Requests',         // Menu title
    'manage_options',          // Capability
    'export-requests-processed',         // Menu slug
    'scjpc_export_logs_page'   // Function to display page content
  );
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Export Excel/Csv Pending Requests', 'scjpc'), // Page title
    'Export Excel/Csv Pending Requests',         // Menu title
    'manage_options',          // Capability
    'export-requests-pending',         // Menu slug
    'scjpc_export_logs_page'   // Function to display page content
  );
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Export Excel/Csv Processing Requests', 'scjpc'), // Page title
    'Export Excel/Csv Processing Requests',         // Menu title
    'manage_options',          // Capability
    'export-requests-processing',         // Menu slug
    'scjpc_export_logs_page'   // Function to display page content
  );

  // Add the migration_logs submenu item
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Database Migration Logs', 'scjpc'), // Page title
    'Database Migration Logs',         // Menu title
    'manage_options',          // Capability
    'migration-logs',         // Menu slug
    'scjpc_migration_logs'   // Function to display page content
  );

  // Add the SCJPC Settings submenu item

  add_submenu_page(
    'scjpc',
    __('SCJPC Settings', 'scjpc'), // Page title
    'Settings',
    'administrator',
    'settings',
    'scjpc_options_menu_settings_page',
  );
  add_action('admin_init', 'scjpc_options_menu_settings');

  // Add the migration_logs submenu item
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('ES Shards', 'scjpc'),              // Page title
    'ES Shards',                  // Menu title
    'manage_options',             // Capability
    'es-shards',                  // Menu slug
    'scjpc_es_shards'              // Function to display page content
  );

  // Add the migration_logs submenu item
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('ES Health', 'scjpc'), // Page title
    'ES Health',         // Menu title
    'manage_options',          // Capability
    'es-health',         // Menu slug
    'scjpc_es_health'   // Function to display page content
  );

  // Add the base owners submenu item
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Base Owners', 'scjpc'), // Page title
    'Base Owners',         // Menu title
    'manage_options',          // Capability
    'base-owners',         // Menu slug
    'scjpc_base_owners_settings'   // Function to display page content
  );
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Migration Dates', 'scjpc'), // Page title
    'Migration Dates',         // Menu title
    'manage_options',          // Capability
    'migration-dates',         // Menu slug
    'scjpc_update_migration_dates_manually'   // Function to display page content
  );
  add_action('admin_init', 'scjpc_options_menu_migration_dates');

}

function load_admin_assets(): void {
  wp_enqueue_style('admin_css', SCJPC_ASSETS_URL . 'css/admin.css', false, '2.7');
  wp_enqueue_script('admin_js', SCJPC_ASSETS_URL . 'js/admin.js', false, '3.0', true);
}

function admin_scjpc_dashboard() {
  ob_start();
  include_once SCJPC_PLUGIN_ADMIN_BASE . "pages/admin_scjpc_dashboard.php";
}

function admin_jpa_search() {
  ob_start();
  include_once SCJPC_PLUGIN_ADMIN_BASE . "pages/admin_jpa_search.php";
}

function scjpc_export_logs_page() {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'pages/export_requests_table.php');
//  return ob_get_clean();
}

function scjpc_migration_logs() {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'pages/migration_logs_table.php');
//  return ob_get_clean();
}

function scjpc_es_shards(): void {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'pages/es_shards_table.php');
//  return ob_get_clean();
}

function scjpc_es_health(): void {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'pages/es_health_table.php');
//  return ob_get_clean();
}


function scjpc_base_owners_settings(): void {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'pages/base_owners.php');
}

function scjpc_update_migration_dates_manually(): void {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'templates/migration_dates.php');
}

add_action('admin_enqueue_scripts', 'scjpc_enqueue_admin_scripts');

function scjpc_enqueue_admin_scripts($hook): void {
  if ($hook == 'scjpc_page_base-owners') {
    wp_enqueue_script('base-owners', SCJPC_ASSETS_URL . 'js/base-owners.js', array('jquery'), '1.001', true);
    wp_localize_script('base-owners', 'scjpc_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
  }
}

add_action('wp_ajax_scjpc_toggle_status', 'scjpc_toggle_status');
/**
 * this method toggles the status of the base owners to show in the dropdown on multiple poles search page
 * @return void
 */
function scjpc_toggle_status(): void {
  global $wpdb;

  $base_owner_code = sanitize_text_field($_POST['base_owner_code']);
  $table_name = scjpc_get_base_owners_table_name();
  $current_status = $wpdb->get_var($wpdb->prepare(
    "SELECT status FROM $table_name WHERE base_owner_code = %s",
    $base_owner_code
  ));

  $new_status = $current_status === 'active' ? 'inactive' : 'active';
  $updated = $wpdb->update(
    $table_name,
    ['status' => $new_status],
    ['base_owner_code' => $base_owner_code],
    ['%s'],
    ['%s']
  );

  if ($updated !== false) {
    wp_send_json_success(['new_status' => $new_status]);
  } else {
    wp_send_json_error();
  }
}


function ajax_jpa_search_update_pdf(): void {
//  echo "<pre>GET=" . count($_GET) . "==POST=" . count($_POST) . "==FILES=" . count($_FILES) . "==REQUEST=" . count($_REQUEST) . print_r($_GET, true) . print_r($_POST, true) . print_r($_FILES, true) . print_r($_REQUEST, true) . "</pre>";
  $s3_key = $_REQUEST['s3_key'];
  $client = getS3Client();
  $result = $client->putObject([
    'Bucket' => 'scjpc-data',
    'Key' => $s3_key,
    'SourceFile' => $_FILES['pdf_s3_key']['tmp_name'],
  ]);

  $response = update_jpa_search_pdf($_REQUEST);
  wp_send_json_success($response);
}

add_action('admin_post_nopriv_jpa_search_update_pdf', 'ajax_jpa_search_update_pdf');
add_action('wp_ajax_jpa_search_update_pdf', 'ajax_jpa_search_update_pdf');
add_action('wp_ajax_nopriv_jpa_search_update_pdf', 'ajax_jpa_search_update_pdf');

function flush_cache() {
  $api_url = rtrim(get_option('scjpc_es_host'), '/') . "/" . $_REQUEST['apiAction'];
  $headers = ["Content-Type: application/json", "security_key: " . get_option('scjpc_client_auth_key')];

  $request_method = !empty($_REQUEST["method"]) ? $_REQUEST["method"] : "DELETE";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_method); // Use DELETE method
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  if (!empty($_REQUEST['key'])) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["keys" => [$_REQUEST['key']]])); // Set the request body
  }
  if (!empty($_REQUEST['elastic_search_re_index'])) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["elastic_search_re_index" => $_REQUEST['key']])); // Set the request body
  }
 if (!empty($_REQUEST['remove_deleted_data'])) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["remove_deleted_data" => $_REQUEST['key']])); // Set the request body
 }

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  } else {
    echo $response;
  }

  curl_close($ch);
  wp_die();
}

add_action('admin_post_nopriv_flush_cache', 'flush_cache');
add_action('wp_ajax_flush_cache', 'flush_cache');
add_action('wp_ajax_nopriv_flush-cache', 'flush_cache');


function custom_lost_password_html_link($html_link) {
  return "";
}

add_filter('lost_password_html_link', 'custom_lost_password_html_link');
