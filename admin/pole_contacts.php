<?php
add_action("elementor/frontend/after_enqueue_styles", "scjpc_enqueue_pole_members_script");
function scjpc_enqueue_pole_members_script() {
  wp_enqueue_style('poles-contacts-css', SCJPC_ASSETS_URL . 'css/pole-contacts.css', false, '1.09');
}

add_shortcode('scjpc_pole_inspection_contacts', 'scjpc_pole_inspection_contacts_callback');
function scjpc_pole_inspection_contacts_callback(): void {
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/pole-contacts.php';
  echo ob_get_clean();
}


function scjpc_get_pole_inspection_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_662e732c50241"); // Server
  $fields = acf_get_fields($fields_group);
  if (!empty($query["jpa_contact_id"])) {
    $jpa_contacts = [get_post($query["jpa_contact_id"])];
  } else {
    $jpa_contacts = get_posts([
      "post_type" => "member", // Server
//      "post_type" => "jpa-contact", // Local
      "posts_per_page" => -1,
      "order" => "ASC"
    ]);
  }
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_pole_members', 'ajax_scjpc_export_pole_members');
add_action('wp_ajax_nopriv_scjpc_export_pole_members', 'ajax_scjpc_export_pole_members');


function ajax_scjpc_export_pole_members() {
  [$fields, $jpa_contacts] = scjpc_get_pole_inspection_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'pole');
  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'pole');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}


add_shortcode('scjpc_emergency_claim_contacts', 'scjpc_emergency_claim_contacts_callback');
function scjpc_emergency_claim_contacts_callback(): void {
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/emergency-contacts.php';
  echo ob_get_clean();
}


function scjpc_get_emergency_claim_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_66464f0e51512"); // Server
  $fields = acf_get_fields($fields_group);
  $jpa_contacts = get_posts([
    "post_type" => "member", // Server
//      "post_type" => "jpa-contact", // Local
    "posts_per_page" => -1,
    "order" => "ASC"
  ]);
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_emergency_contacts', 'ajax_scjpc_export_emergency_contacts');
add_action('wp_ajax_nopriv_scjpc_export_emergency_contacts', 'ajax_scjpc_export_emergency_contacts');


function ajax_scjpc_export_emergency_contacts() {
  [$fields, $jpa_contacts] = scjpc_get_emergency_claim_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'emergency');
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'emergency', true);
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}