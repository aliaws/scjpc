<?php
add_action("elementor/frontend/after_enqueue_styles", "scjpc_enqueue_pole_members_script");
function scjpc_enqueue_pole_members_script(): void {
  wp_enqueue_style('poles-contacts', SCJPC_ASSETS_URL . 'css/pole-contacts.css', false, '1.26');
  wp_enqueue_style('print', SCJPC_ASSETS_URL . 'css/print.css', array(), '7.9', 'print');

}

add_shortcode('scjpc_pole_inspection_contacts', 'scjpc_pole_inspection_contacts_callback');
function scjpc_pole_inspection_contacts_callback(): void {
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/pole-contacts.php';
  echo ob_get_clean();
}

add_shortcode('scjpc_jpa_contacts', 'scjpc_jpa_contacts_callback');
function scjpc_jpa_contacts_callback(): void {
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/jpa-contacts.php';
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
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'pole', true);
  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'pole');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}


add_shortcode('scjpc_buddy_pole_contacts', 'scjpc_buddy_pole_contacts_callback');
function scjpc_buddy_pole_contacts_callback(): void {
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/buddy-pole-contacts.php';
  echo ob_get_clean();
}


function scjpc_get_buddy_pole_contacts($query = []): array {
//  $fields_group = acf_get_field_group("group_662e732c50241"); // Server
//  $fields[] = ;  
//  $fields = [acf_get_field('dual_poles_in_field_buddy_poles')];
//  $fields[] = acf_get_field('contact_jpa');
  $fields = [acf_get_field('contact_jpa')];

  $jpa_contacts = get_posts([
    "post_type" => "member", // Server
//      "post_type" => "jpa-contact", // Local  
    "posts_per_page" => -1,
    "order" => "ASC"
  ]);
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_buddy_pole_members', 'ajax_scjpc_export_buddy_pole_members');
add_action('wp_ajax_nopriv_scjpc_export_buddy_pole_members', 'ajax_scjpc_export_buddy_pole_members');


function ajax_scjpc_export_buddy_pole_members() {
  [$fields, $jpa_contacts] = scjpc_get_buddy_pole_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'buddy-pole', true);
  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'buddy-pole');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}

add_shortcode('scjpc_graffiti_removal_contacts', 'scjpc_graffiti_removal_contacts_callback');
function scjpc_graffiti_removal_contacts_callback(): void {
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/graffiti-removal-contacts.php';
  echo ob_get_clean();
}


function scjpc_get_graffiti_removal_contacts($query = []): array {
//  $fields_group = acf_get_field_group("group_662e732c50241"); // Server  
//  $fields = [acf_get_field('single_point_of_contact_graffiti_removal')];
//  $fields[] = acf_get_field('contact_jpa');
  $fields = [acf_get_field('contact_jpa')];

  $jpa_contacts = get_posts([
    "post_type" => "member", // Server
//      "post_type" => "jpa-contact", // Local
    "posts_per_page" => -1,
    "order" => "ASC"
  ]);
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_graffiti_removal_members', 'ajax_scjpc_export_graffiti_removal_members');
add_action('wp_ajax_nopriv_scjpc_export_graffiti_removal_members', 'ajax_scjpc_export_graffiti_removal_members');


function ajax_scjpc_export_graffiti_removal_members() {
  [$fields, $jpa_contacts] = scjpc_get_graffiti_removal_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'graffiti-removal', true);
  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'graffiti-removal');
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
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'emergency', true);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'emergency', true);
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}

// New table for field assistance/joint meet contacts
add_shortcode('scjpc_field_assistance_contacts', 'scjpc_field_assistance_contacts_callback');
function scjpc_field_assistance_contacts_callback(): void {
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/field-assistance-contacts.php';
  echo ob_get_clean();
}

function scjpc_get_field_assistance_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_664639ed6409d"); // Server
  $fields = acf_get_fields($fields_group);
  $jpa_contacts = get_posts([
    "post_type" => "member", // Server  
    "posts_per_page" => -1,
    "order" => "ASC"
  ]);
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_field_assistance_contacts', 'ajax_scjpc_export_field_assistance_contacts');
add_action('wp_ajax_nopriv_scjpc_export_field_assistance_contacts', 'ajax_scjpc_export_field_assistance_contacts');

function ajax_scjpc_export_field_assistance_contacts() {
  [$fields, $jpa_contacts] = scjpc_get_field_assistance_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'field-assistance', true);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'field-assistance', true);
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}

add_action('acf/include_fields', function () {
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }
  acf_add_local_field_group(array(
    'key' => 'group_664639ed6409d',
    'title' => 'FIELD ASSISTANCE/ JOINT MEET',
    'fields' => array(
      array(
        'key' => 'field_664639ed86bac',
        'label' => 'Last Updated',
        'name' => 'last_updated',
        'aria-label' => '',
        'type' => 'date_picker',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'display_format' => 'm/d/Y',
        'return_format' => 'd/m/Y',
        'first_day' => 1,
      ),
      array(
        'key' => 'field_66463a5e86bad',
        'label' => 'Contact',
        'name' => 'contact',
        'aria-label' => '',
        'type' => 'wysiwyg',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 1,
        'delay' => 0,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'member',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
  ));
});