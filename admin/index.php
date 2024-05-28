<?php
require_once SCJPC_PLUGIN_ADMIN_BASE . 'migration_logs.php';
require_once SCJPC_PLUGIN_ADMIN_BASE . 'create_users.php';

function scjpc_export_logs_page() {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'export_requests_table.php');
//  return ob_get_clean();
}


// Function to add the custom admin page to the WordPress admin menu
function add_export_logs_page(): void {
  add_menu_page(
    'Export Requests', // Page title
    'Export Requests', // Menu title
    'manage_options',    // Capability required to access this page
    'export-requests', // Menu slug
    'scjpc_export_logs_page', // Function to generate the page content
    'dashicons-admin-generic',   // Icon URL or Dashicon class
    99 // Position in the menu
  );
}

add_action('admin_menu', 'add_export_logs_page');


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
    'all_items' => __('All Migration Logs', 'scjpc'),
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
  );
  register_post_type('migration_logs', $args);
}

add_filter('manage_migration_logs_posts_columns', 'scjpc_migrations_logs_columns');
function scjpc_migrations_logs_columns($columns): array {
  $scjpc_columns = [
    'status' => __('Status', 'scjpc'),
    'jpas_s3_key' => __('JPAs S3 Key', 'scjpc'),
    'jpas_created' => __('JPAs Created', 'scjpc'),
    'jpas_updated' => __('JPAs Updated', 'scjpc'),
    'poles_s3_key' => __('Poles S3 Key', 'scjpc'),
    'poles_created' => __('Poles Created', 'scjpc'),
    'poles_updated' => __('Poles Updated', 'scjpc'),
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


add_action('admin_menu', 'scjpc_options_menu');

function scjpc_options_menu(): void {
  add_menu_page(
    'SCJPC - Admin Settings', 'SCJPC Settings', 'administrator', __FILE__,
    'scjpc_options_menu_settings_page', plugins_url('/images/icon.png', __FILE__)
  );
  add_action('admin_init', 'scjpc_options_menu_settings');
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