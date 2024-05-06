<?php
/**
 * Plugin Name: Scjpc
 * Plugin URI: https://not.yet/
 * Description: create search forms
 * Version: 1.1
 * Author: Ali Abbas
 * Author URI: https://office.com/
 * License: GPLv2 or later
 * Text Domain: ali
 */

const ROOT_DIR = __DIR__;

require_once(ROOT_DIR . '/const.php');

require_once(ROOT_DIR . '/file.php');

/**
 * this method is use for the create pages when plugin active
 */
register_activation_hook(__FILE__, 'scjpc_plugin_activation');

function scjpc_plugin_activation(): void {

  $pages_data = array(
    'JPA Search' => '[scjpc_jpa_search]',
    'Quick Pole Search' => '[scjpc_quick_pole_search]',
    'Multiple JPA Search' => '[scjpc_multiple_jpa_search]',
    'Advanced Pole Search' => '[scjpc_advanced_pole_search]',
    'Multiple Pole Search' => '[scjpc_multiple_pole_search]',
    'Website/Doc Search' => '[scjpc_website_doc_search]',
  );
  foreach ($pages_data as $page_title => $shortcode) {
    $page = scjpc_get_page_by_title($page_title);
    if (!$page) {
      wp_insert_post(array(
        'post_title' => $page_title,
        'post_content' => $shortcode,
        'post_status' => 'publish',
        'post_type' => 'page',
      ));
    } else {
      wp_update_post(array(
        'ID' => $page->ID,
        'post_content' => $shortcode,
      ));
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

function create_users_from_csv_on_activation() {
  $csv_file = plugin_dir_path( __FILE__ ) . 'scjpc_users.csv';

  if ( ! file_exists( $csv_file ) ) {
      return;
  }

  if ( ( $handle = fopen( $csv_file, 'r' ) ) !== false ) {
      while ( ( $data = fgetcsv( $handle ) ) !== false ) {
          if ( $data[0] == 'Username' ) {
              continue;
          }

          $username = $data[0];
          $email = $data[1];
          $first_name = $data[2];
          $last_name = $data[3];
          $admin = $data[4]; // 0 or 1

          if ( email_exists( $email ) ) {
              continue;
          }

          $role = ( $admin == 1 ) ? 'administrator' : 'subscriber';

          $user_data = array(
              'user_login' => $username,
              'user_email' => $email,
              'user_pass'  => USERS_PASSWORD,
              'first_name' => $first_name,
              'last_name'  => $last_name,
              'role'       => $role,
          );

          $user_id = wp_insert_user( $user_data );

          if ( is_wp_error( $user_id ) ) {
              continue;
          }

          add_user_meta( $user_id, 'created_from_csv', true );
      }
      fclose( $handle );
  }
}
register_activation_hook( __FILE__, 'create_users_from_csv_on_activation' );
