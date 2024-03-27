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





register_activation_hook( __FILE__, 'spjcp_plugin_activation' );

function spjcp_plugin_activation() {
  $pages_data = array(
    'jpa search' => '[scjpc_jpg_search]',
    'Multiple JPA Search' => '[scjpc_multiple_jpa_search]',
    'Quick Pole Search' => '[scjpc_quick_pole_search]',
    'Advanced Pole Search' => '[scjpc_advanced_pole_search]',
    'Multiple Pole Search' => '[scjpc_multiple_pole_search]',
    'Website/Doc Search' => '[scjpc_website_doc_search]',
  );
  foreach ( $pages_data as $page_name => $shortcode ) {
    $page = get_page_by_title( $page_name );
    if ( ! $page ) {
      wp_insert_post( array(
        'post_title'   => $page_name,
        'post_content' => $shortcode,
        'post_status'  => 'publish',
        'post_type'    => 'page',
      ) );
    } else {
      wp_update_post( array(
        'ID'           => $page->ID,
        'post_content' => $shortcode,
      ) );
    }
  }
}
?>