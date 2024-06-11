<?php

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
  $jpa_contacts = scjpc_get_jpa_contacts($_REQUEST);
  return new WP_REST_Response(['export_file' => $jpa_contacts], 200);

}


function scjpc_get_jpa_contacts($query): string {
  $fields_group = acf_get_field_group('group_662d0256002a6'); // Server
//  $fields_group = acf_get_field_group('group_666716c1891dd'); // Local
  $fields = acf_get_fields($fields_group);
  if (!empty($query['jpa_contact_id'])) {
    $jpa_contacts = [get_post($query['jpa_contact_id'])];
  } else {
    $jpa_contacts = get_posts([
      'post_type' => 'member', // Server
//      'post_type' => 'jpa-contact', // Local
      'posts_per_page' => -1,
      'order' => 'ASC'
    ]);
  }
  return scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts);
}

function scjpc_fetch_jpa_contacts_fields(array $fields, array $jpa_contacts): string {
  $response = [];
  foreach ($jpa_contacts as $post) {
    $response[$post->ID] = [];
    foreach ($fields as $field) {
      if ($field['name'] != 'cable_tags') {
        if ($field['type'] == 'wysiwyg') {
          $response[$post->ID][$field['label']] = strip_tags(get_field($field['name'], $post->ID));
        } else {
          $response[$post->ID][$field['label']] = get_field($field['name'], $post->ID);
        }
      }
    }
  }
  return scjpc_process_contacts_csv_writing($response);
}


function scjpc_process_contacts_csv_writing(array $data): string {
  $excel_file_name = "jpa-contacts_" . time() . "_" . get_current_user_id() . ".xlsx";
  $excel_file_path = WP_CONTENT_DIR . "/uploads/scjpc-exports/" . $excel_file_name;
  $excelFile = fopen($excel_file_path, 'w');

  $transposedContacts = [];
  foreach ($data[array_key_first($data)] as $key => $value) {
    $transposedContacts[$key] = [$key];
  }

  foreach ($data as $contact) {
    foreach ($contact as $field => $value) {
      $transposedContacts[$field][] = $value;
    }
  }

  foreach ($transposedContacts as $contact) {
    fputcsv($excelFile, $contact);
  }

  fclose($excelFile);
  return wp_get_upload_dir()['baseurl'] . "/scjpc-exports/" . $excel_file_name;

}

