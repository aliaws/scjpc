<?php

add_action('rest_api_init', 'scjpc_insert_post_migration_log');
function scjpc_insert_post_migration_log(): void {
  register_rest_route('scjpc/v1/', 'migration_log/', [
    'methods' => 'POST',
    'callback' => 'scjpc_insert_migration_log',
  ]);
}


function scjpc_insert_migration_log(WP_REST_Request $request): WP_REST_Response {
  $security_key = $request->get_header('security_key');
  if (!isset($security_key) || $security_key != API_SECURITY_KEY) {
    return new WP_REST_Response(['message' => 'Please provide the API security key'], 401);
  }
  $body = $request->get_json_params();
  return insert_migration_log($body);

}

function insert_migration_log($body): WP_REST_Response {
  $post_id = wp_insert_post([
    'post_type' => 'migration_logs',
    'post_status' => 'publish',
    'post_title' => $body['job_datetime'],
  ]);

  if ($post_id) {
    add_migration_logs_post_meta($post_id, $body);
    return new WP_REST_Response(['message' => 'Migration Log posted successfully.'], 200);
  } else {
    return new WP_REST_Response(['message' => 'Something went wrong. Please try again.'], 400);
  }
}


function add_migration_logs_post_meta(int $post_id, $body): void {
  foreach ($body as $key => $value) {
    add_post_meta($post_id, "scjpc_$key", $value);
  }
}


add_action('rest_api_init', 'scjpc_update_post_migration_log');
function scjpc_update_post_migration_log(): void {
  register_rest_route('scjpc/v1/', 'migration_log/', [
    'methods' => 'PUT',
    'callback' => 'scjpc_update_migration_log',
  ]);
}


function scjpc_update_migration_log(WP_REST_Request $request): WP_REST_Response {
  $security_key = $request->get_header('security_key');
  if (!isset($security_key) || $security_key != API_SECURITY_KEY) {
    return new WP_REST_Response(['message' => 'Please provide the API security key'], 401);
  }
  $body = $request->get_json_params();
  $migration_log = get_migration_log_by_title($body['job_datetime']);
  if ($migration_log) {
    update_migration_logs_post_meta($migration_log->ID, $body);
    $response = new WP_REST_Response(['message' => 'Migration Log updated successfully.'], 200);
  } else {
    return insert_migration_log($body);
  }
  return $response;
}

function get_migration_log_by_title($job_datetime): ?WP_Post {
  $query = new WP_Query([
    'post_type' => 'migration_logs',
    'title' => $job_datetime,
    'post_status' => 'all',
    'posts_per_page' => 1,
    'no_found_rows' => true,
    'ignore_sticky_posts' => true,
    'update_post_term_cache' => false,
    'update_post_meta_cache' => false,
    'orderby' => 'post_date ID',
    'order' => 'DESC',
  ]);

  if (!empty($query->post)) {
    return $query->post;
  } else {
    return null;
  }
}


function update_migration_logs_post_meta(int $post_id, $body): void {
  foreach ($body as $key => $value) {
    update_post_meta($post_id, "scjpc_$key", $value);
  }
}


add_action('rest_api_init', 'scjpc_update_pole_base_owners');
function scjpc_update_pole_base_owners(): void {
  register_rest_route('scjpc/v1/', 'base_owners/', [
    'methods' => 'PUT',
    'callback' => 'scjpc_update_base_owners',
  ]);
}

function scjpc_update_base_owners(WP_REST_Request $request): WP_REST_Response {
  $security_key = $request->get_header('security_key');
  if (!isset($security_key) || $security_key != API_SECURITY_KEY) {
    return new WP_REST_Response(['message' => 'Please provide the API security key'], 401);
  }
  $body = $request->get_json_params();
  update_option('scjpc_base_owners', array_combine($body, $body));
  return new WP_REST_Response(['message' => 'Base Owners updated successfully.'], 200);

}