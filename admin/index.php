<?php
function custom_admin_page_content() {
  ob_start();
  include_once(SCJPC_PLUGIN_ADMIN_BASE . '/migration_logs_table.php');
}

// Function to add the custom admin page to the WordPress admin menu
function add_custom_admin_page() {
  add_menu_page(
    'Migration Logs', // Page title
    'Migration Logs', // Menu title
    'manage_options',    // Capability required to access this page
    'migration-logs', // Menu slug
    'custom_admin_page_content', // Function to generate the page content
    'dashicons-admin-generic',   // Icon URL or Dashicon class
    99 // Position in the menu
  );
}

add_action('admin_menu', 'add_custom_admin_page');

