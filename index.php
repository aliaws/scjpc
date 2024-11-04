<?php
/**
 * Plugin Name: SCJPC
 * Plugin URI: https://accuratedigitalsolutions.com/web-application-development/
 * Description: This plugin creates the search forms to search the JPAs and POLEs based on various filters
 * Version: 1.1
 * Author: Ali Abbas
 * Author URI: https://accuratedigitalsolutions.com/
 * License: GPLv2 or later
 * Text Domain: scjpc
 */

const ROOT_DIR = __DIR__;

require_once(ROOT_DIR . '/const.php');

require_once(ROOT_DIR . '/file.php');

/**
 * this method is use for the create pages when plugin active
 */
register_activation_hook(__FILE__, 'scjpc_plugin_activation');
function scjpc_plugin_activation(): void {
  scjpc_create_default_pages();
  scjpc_create_files_directory_in_uploads();
  scjpc_create_default_tables();
}

/**
 * this method is used to create default tables required by the plugin.
 * @return void
 */
function scjpc_create_default_tables(): void {
  global $wpdb;
  $table_name = "{$wpdb->prefix}base_owners";
  update_option('scjpc_base_owners_table', $table_name);
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    base_owner_code   VARCHAR(5) PRIMARY KEY,
    base_owner_name   VARCHAR(100) NOT NULL,
    status            ENUM('active', 'inactive') DEFAULT 'active'
  );";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}


/**
 * this method is used to create the plugin's default pages to perform searches
 * @return void
 */
function scjpc_create_default_pages(): void {
  $pages_data = [
    'JPA Search' => '[scjpc_jpa_search]',
    'Multiple JPA Search' => '[scjpc_multiple_jpa_search]',
    'Pole Search' => '[scjpc_pole_search]',
    'Pole Detail' => '[scjpc_pole_detail]',
    'Download Export' => '[scjpc_download_export]',
    'Quick Pole Search' => '[scjpc_quick_pole_search]',
    'Advanced Pole Search' => '[scjpc_advanced_pole_search]',
    'Multiple Pole Search' => '[scjpc_multiple_pole_search]',
    'Website/Doc Search' => '[scjpc_website_doc_search]',
  ];
  foreach ($pages_data as $page_title => $shortcode) {
    $page = scjpc_get_page_by_title($page_title);
    if (!$page) {
      wp_insert_post([
        'post_title' => $page_title,
        'post_content' => $shortcode,
        'post_status' => 'publish',
        'post_type' => 'page',
      ]);
    } else {
      wp_update_post([
        'ID' => $page->ID,
        'post_content' => $shortcode,
      ]);
    }
  }
}


function scjpc_get_page_by_title(string $page_title): ?WP_Post {
  $query = new WP_Query(
    array(
      'post_type' => 'page',
      'title' => $page_title,
      'post_status' => 'all',
      'posts_per_page' => 1,
      'no_found_rows' => true,
      'ignore_sticky_posts' => true,
      'update_post_term_cache' => false,
      'update_post_meta_cache' => false,
      'orderby' => 'post_date ID',
      'order' => 'ASC',
    )
  );
  if (!empty($query->post)) {
    $page = $query->post;
  } else {
    $page = null;
  }
  return $page;
}

function scjpc_create_files_directory_in_uploads(): void {
  if (!file_exists(WP_CONTENT_DIR . '/uploads/scjpc-exports/')) {
    wp_mkdir_p(WP_CONTENT_DIR . '/uploads/scjpc-exports');
  }
}

add_action('wp_login', 'scjpc_redirect_to_requested_page', 20, 2);
function scjpc_redirect_to_requested_page($user_login, WP_User $user): void {
  if (!empty($_REQUEST) && !empty($_REQUEST['redirect_to']) && $_REQUEST['redirect_to'] != '') {
    $redirect_url = explode('=', $_REQUEST['redirect_to']);
    $redirect_url = !empty($redirect_url[1]) ? urldecode($redirect_url[1]) : urldecode($redirect_url[0]);
    header("Location: $redirect_url");
    die();
  }
}

add_action('wp_footer', 'scjpc_dequeue_unwanted_scripts', 15);
function scjpc_dequeue_unwanted_scripts(): void { wp_dequeue_script('smartmenus'); }

add_action('wp_enqueue_scripts', 'scjpc_enqueue_scripts');
function scjpc_enqueue_scripts(): void {
  wp_enqueue_script('scjpc-smartmenus', SCJPC_ASSETS_URL . '/js/smartmenus.min.js', ['jquery'], '', true);
}

add_action('wp_enqueue_scripts', function () {
  if (!class_exists('\Elementor\Core\Files\CSS\Post')) {
    return;
  }
  $css_file = new \Elementor\Core\Files\CSS\Post(5203);
  $css_file->enqueue();
}, 10);
