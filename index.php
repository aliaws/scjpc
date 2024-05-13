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

require_once (ROOT_DIR . '/const.php');

require_once (ROOT_DIR . '/file.php');

/**
 * this method is use for the create pages when plugin active
 */
register_activation_hook(__FILE__, 'scjpc_plugin_activation');
function scjpc_plugin_activation(): void
{
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
      wp_insert_post(
        array(
          'post_title' => $page_title,
          'post_content' => $shortcode,
          'post_status' => 'publish',
          'post_type' => 'page',
        )
      );
    } else {
      wp_update_post(
        array(
          'ID' => $page->ID,
          'post_content' => $shortcode,
        )
      );
    }
  }
}
function scjpc_get_page_by_title(string $page_title): ?WP_Post
{
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