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
  if (!isset($security_key) || $security_key != get_option('scjpc_client_auth_key')) {
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
  if (!isset($security_key) || $security_key != get_option('scjpc_client_auth_key')) {
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
  if (!isset($security_key) || $security_key != get_option('scjpc_client_auth_key')) {
    return new WP_REST_Response(['message' => 'Please provide the API security key'], 401);
  }
  $body = $request->get_json_params();
  update_option('scjpc_base_owners', array_combine($body, $body));
  return new WP_REST_Response(['message' => 'Base Owners updated successfully.'], 200);

}

add_action('rest_api_init', 'scjpc_update_database_update_date');
function scjpc_update_database_update_date(): void {
  register_rest_route('scjpc/v1/', 'database_update_date/', [
    'methods' => 'PUT',
    'callback' => 'scjpc_update_database_date',
  ]);
}

function scjpc_update_database_date(WP_REST_Request $request): WP_REST_Response {
  $security_key = $request->get_header('security_key');
  if (!isset($security_key) || $security_key != get_option('scjpc_client_auth_key')) {
    return new WP_REST_Response(['message' => 'Please provide the API security key'], 401);
  }
  $body = $request->get_json_params();
  update_option('scjpc_migration_date', $body['migration_date']);
  update_option('scjpc_latest_billed_jpa_date', $body['latest_billed_jpa_date']);
  update_option('scjpc_latest_billed_jpa_pdf_date', $body['latest_billed_jpa_pdf_date']);
  return new WP_REST_Response(['message' => 'Migration Dates Added Successfully!'], 200);

}

add_action('rest_api_init', 'scjpc_export_jpa_contacts_data');
function scjpc_export_jpa_contacts_data(): void {
  register_rest_route('scjpc/v1/', 'jpa-contacts/', [
    'methods' => 'GET',
    'callback' => 'scjpc_export_jpa_contacts',
  ]);
}


function scjpc_export_jpa_contacts(WP_REST_Request $request): WP_REST_Response {
  $security_key = $request->get_header('security_key');
  if (!isset($security_key) || $security_key != get_option('scjpc_client_auth_key')) {
    return new WP_REST_Response(['message' => 'Please provide the API security key'], 401);
  }
  $jpa_contacts = scjpc_get_jpa_contacts();
  return new WP_REST_Response(['contacts' => $jpa_contacts], 200);
//  return insert_migration_log($body);

}

function scjpc_get_jpa_contacts(): array {
//  $fields_group = acf_get_field_group('group_666716c1891dd');
  $fields_group = acf_get_field_group('group_662d0256002a6');
  $fields = acf_get_fields($fields_group);
  $response = [];
  foreach (scjpc_get_jpa_contacts_posts() as $post) {
    $response[$post->ID] = [];
    foreach ($fields as $field) {
      if ($field['type'] == 'wysiwyg') {
        $response[$post->ID][$field['label']] = strip_tags(get_field($field['name'], $post->ID));
      } else {
        $response[$post->ID][$field['label']] = get_field($field['name'], $post->ID);
      }
    }
  }
  return $response;
//  return $fields;
}

function scjpc_get_jpa_contacts_posts(): array {
  $args = array(
    'post_type' => 'member',
//    'post_type' => 'jpa-contact',
    'posts_per_page' => -1,
  );
  return get_posts($args);
}