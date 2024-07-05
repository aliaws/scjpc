<?php
require_once SCJPC_PLUGIN_ADMIN_BASE . 'migration_logs.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'jpa_contacts.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'pole_contacts.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'create_users.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'functions.php';



add_action('init', 'scjpc_register_post_type_migration_logs');
function scjpc_register_post_type_migration_logs(): void {
  $supports = [
    'title', // post title
    'editor', // post content
//    'author', // post author
//    'thumbnail', // featured images
//    'excerpt', // post excerpt
    'custom-fields', // custom fields
//    'comments', // post comments
//    'revisions', // post revisions
//    'post-formats', // post formats
  ];
  $labels = array(
    'name' => _x('Migration Logs', 'plural', 'scjpc'),
    'singular_name' => _x('Migration Log', 'singular', 'scjpc'),
    'menu_name' => _x('Migration Logs', 'admin menu', 'scjpc'),
    'name_admin_bar' => _x('Migration Logs', 'admin bar', 'scjpc'),
//    'add_new' => _x('Add New', 'add new'),
//    'add_new_item' => __('Add New news'),
//    'new_item' => __('New news', 'scjpc'),
//    'edit_item' => __('Edit news'),
    'view_item' => __('View Migration Logs', 'scjpc'),
    'all_items' => __('Database Migration Logs', 'scjpc'),
    'search_items' => __('Search Migration Logs', 'scjpc'),
    'not_found' => __('No Migration Logs found.', 'scjpc'),
  );
  $args = array(
    'supports' => $supports,
    'labels' => $labels,
    'public' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'migration-logs'),
    'has_archive' => true,
    'hierarchical' => false,
    'show_in_menu' => 'scjpc', // Add this line to place under the "Scjpc" menu
  );
  register_post_type('migration_logs', $args);
}

add_filter('manage_migration_logs_posts_columns', 'scjpc_migrations_logs_columns');
function scjpc_migrations_logs_columns($columns): array {
  $scjpc_columns = [
    'status' => __('Status', 'scjpc'),
    'jpas_s3_key' => __('JPAs S3 Key', 'scjpc'),
    'poles_s3_key' => __('Poles S3 Key', 'scjpc'),
    'request_host' => __('Request Host', 'scjpc'),
//    'job_datetime' => __('Job Datetime', 'scjpc'),
    'date' => __('Date', 'scjpc'),
  ];
  return array_merge(array_slice($columns, 0, 2), $scjpc_columns);
}

add_action('manage_migration_logs_posts_custom_column', 'scjpc_fill_migrations_logs_columns', 10, 2);
function scjpc_fill_migrations_logs_columns($column, $post_id): void {
  $scjpc_columns = get_scjpc_columns_array();
  if (isset($scjpc_columns[$column])) {
    echo get_post_meta($post_id, $scjpc_columns[$column], true);
  }
}

function get_scjpc_columns_array(): array {
  return [
    'status' => 'scjpc_status',
    'jpas_s3_key' => 'scjpc_jpas_s3_key',
    'jpas_created' => 'scjpc_no_of_jpas_created',
    'jpas_updated' => 'scjpc_no_of_jpas_updated',
    'poles_s3_key' => 'scjpc_poles_s3_key',
    'poles_created' => 'scjpc_no_of_poles_created',
    'poles_updated' => 'scjpc_no_of_poles_updated',
//    'job_datetime' => 'scjpc_job_datetime',
    'request_host' => 'scjpc_request_host',
  ];
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
    __('Scjpc', 'textdomain'),   // Page title
    'Scjpc',                     // Menu title
    'manage_options',          // Capability
    'scjpc',                     // Menu slug
    '',                        // Function (empty for now)
    'dashicons-admin-generic', // Icon URL
    6                          // Position
  );
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Jpa', 'textdomain'), // Page title
    'Dashboard',         // Menu title
    'manage_options',          // Capability
    'scjpc-dashboard',         // Menu slug
    'admin_scjpc_dashboard'   // Function to display page content
  );
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Jpa', 'textdomain'), // Page title
    'Update JPA PDF',         // Menu title
    'manage_options',          // Capability
    'update-jpa-pdf',         // Menu slug
    'admin_jpa_search'   // Function to display page content
  );
  // Add the Export Requests submenu item
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Export Excel/Csv Requests', 'textdomain'), // Page title
    'Export Excel/Csv Requests',         // Menu title
    'manage_options',          // Capability
    'export-requests',         // Menu slug
    'scjpc_export_logs_page'   // Function to display page content
  );
  // Add the migration_logs submenu item
  add_submenu_page(
    'scjpc',                     // Parent slug
    __('Database Migration Logs V2', 'textdomain'), // Page title
    'Database Migration Logs V2',         // Menu title
    'manage_options',          // Capability
    'migration-logs',         // Menu slug
    'scjpc_migration_logs'   // Function to display page content
  );

  // Add the SCJPC Settings submenu item

  add_submenu_page(
    'scjpc',
    __('SCJPC Settings', 'textdomain'), // Page title
    'Settings',
    'administrator',
    __FILE__,
    'scjpc_options_menu_settings_page',
  );
  add_action('admin_init', 'scjpc_options_menu_settings');
}

function admin_scjpc_dashboard(){
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

function ajax_jpa_search_update_pdf() {
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