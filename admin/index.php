<?php
require_once SCJPC_PLUGIN_ADMIN_BASE . 'migration_logs.php';
function scjpc_migrations_log_page(): void {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . 'migration_logs_table.php');
}

// Function to add the custom admin page to the WordPress admin menu
function add_custom_admin_page(): void {
  add_menu_page(
    'Migration Logs', // Page title
    'Migration Logs', // Menu title
    'manage_options',    // Capability required to access this page
    'migration-logs', // Menu slug
    'scjpc_migrations_log_page', // Function to generate the page content
    'dashicons-admin-generic',   // Icon URL or Dashicon class
    99 // Position in the menu
  );
}

add_action('admin_menu', 'add_custom_admin_page');


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
//    'job_datetime' => __('Job Datetime', 'scjpc'),
    'date' => __('Date', 'scjpc'),
  ];
  return array_merge(array_slice($columns, 0, 2), $scjpc_columns);
}

add_action('manage_migration_logs_posts_custom_column', 'scjpc_fill_migrations_logs_columns', 10, 2);
function scjpc_fill_migrations_logs_columns($column, $post_id): void {
  if ($column == 'status') {
    echo get_post_meta($post_id, 'scjpc_status')[0];
  } elseif ($column == 'jpas_s3_key') {
    echo get_post_meta($post_id, 'scjpc_jpas_s3_key')[0];
  } elseif ($column == 'jpas_created') {
    echo get_post_meta($post_id, 'scjpc_no_of_jpas_created')[0];
  } elseif ($column == 'jpas_updated') {
    echo get_post_meta($post_id, 'scjpc_no_of_jpas_updated')[0];
  } elseif ($column == 'poles_s3_key') {
    echo get_post_meta($post_id, 'scjpc_poles_s3_key')[0];
  } elseif ($column == 'poles_created') {
    echo get_post_meta($post_id, 'scjpc_no_of_poles_created')[0];
  } elseif ($column == 'poles_updated') {
    echo get_post_meta($post_id, 'scjpc_no_of_poles_updated')[0];
  } elseif ($column == 'job_datetime') {
    echo get_post_meta($post_id, 'scjpc_job_datetime')[0];
  }
}
