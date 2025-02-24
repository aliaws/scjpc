<?php
add_action("elementor/frontend/after_enqueue_styles", "scjpc_enqueue_pole_members_script");
function scjpc_enqueue_pole_members_script(): void {
  wp_enqueue_style('poles-contacts', SCJPC_ASSETS_URL . 'css/pole-contacts.css', false, '1.60');
  wp_enqueue_style('scjpc-global', SCJPC_ASSETS_URL . 'css/global.css', [], '1.02');

  wp_enqueue_script('poles-contacts', SCJPC_ASSETS_URL . 'js/pole-contacts.js', ['jquery'], '1.58');
  wp_enqueue_style('print', SCJPC_ASSETS_URL . 'css/print.css', [], '8.10', 'print');
}

function scjpc_get_pole_inspection_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_662e732c50241"); // Server
  $fields = acf_get_fields($fields_group);
  if (!empty($query["jpa_contact_id"])) {
    $jpa_contacts = [get_post($query["jpa_contact_id"])];
  } else {
    $jpa_contacts = scjpc_get_jpa_contacts_posts();
//    $jpa_contacts = get_posts([
//      "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//      "posts_per_page" => -1,
//      "order" => "ASC"
//    ]);
  }
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_pole_members', 'ajax_scjpc_export_pole_members');
add_action('wp_ajax_nopriv_scjpc_export_pole_members', 'ajax_scjpc_export_pole_members');


function ajax_scjpc_export_pole_members() {
  [$fields, $jpa_contacts] = scjpc_get_pole_inspection_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'pole', true);
//  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'pole');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}

add_action('wp_ajax_scjpc_export_pole_cable_markings', 'ajax_scjpc_export_pole_cable_markings');
add_action('wp_ajax_nopriv_scjpc_export_pole_cable_markings', 'ajax_scjpc_export_pole_cable_markings');


function ajax_scjpc_export_pole_cable_markings() {
  [$fields, $jpa_contacts] = scjpc_get_cable_marking_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'cable-tags', true, false);
//  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'pole');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}


function scjpc_get_buddy_pole_contacts($query = []): array {
//  $fields_group = acf_get_field_group("group_662e732c50241"); // Server
//  $fields[] = ;  
//  $fields = [acf_get_field('dual_poles_in_field_buddy_poles')];
//  $fields[] = acf_get_field('contact_jpa');
  $fields = [acf_get_field('dual_poles_in_field_buddy_poles')];
  $jpa_contacts = scjpc_get_jpa_contacts_posts();

//  $jpa_contacts = get_posts([
//    "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//    "posts_per_page" => -1,
//    "order" => "ASC"
//  ]);
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_buddy_pole_members', 'ajax_scjpc_export_buddy_pole_members');
add_action('wp_ajax_nopriv_scjpc_export_buddy_pole_members', 'ajax_scjpc_export_buddy_pole_members');


function ajax_scjpc_export_buddy_pole_members() {
  [$fields, $jpa_contacts] = scjpc_get_buddy_pole_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'buddy-pole', true);
//  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'buddy-pole');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}


function scjpc_get_cable_marking_contacts($query = []): array {
//  $fields_group = acf_get_field_group("group_662e732c50241"); // Server  
//  $fields = [acf_get_field('single_point_of_contact_graffiti_removal')];
//  $fields[] = acf_get_field('contact_jpa');
  $fields = [acf_get_field('cable_tags')];
  $jpa_contacts = scjpc_get_jpa_contacts_posts();
//  $jpa_contacts = get_posts([
//    "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//    "posts_per_page" => -1,
//    "order" => "ASC"
//  ]);
  return [$fields, $jpa_contacts];
}

function scjpc_get_graffiti_removal_contacts($query = []): array {
//  $fields_group = acf_get_field_group("group_662e732c50241"); // Server
//  $fields = [acf_get_field('single_point_of_contact_graffiti_removal')];
//  $fields[] = acf_get_field('contact_jpa');
  $fields = [acf_get_field('single_point_of_contact_graffiti_removal')];
  $jpa_contacts = scjpc_get_jpa_contacts_posts();
//  $jpa_contacts = get_posts([
//    "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//    "posts_per_page" => -1,
//    "order" => "ASC"
//  ]);
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_graffiti_removal_members', 'ajax_scjpc_export_graffiti_removal_members');
add_action('wp_ajax_nopriv_scjpc_export_graffiti_removal_members', 'ajax_scjpc_export_graffiti_removal_members');


function ajax_scjpc_export_graffiti_removal_members() {
  [$fields, $jpa_contacts] = scjpc_get_graffiti_removal_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'graffiti-removal', true);
//  array_unshift($response, $field_labels);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'graffiti-removal');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}


function scjpc_get_emergency_claim_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_66464f0e51512"); // Server
  $fields = acf_get_fields($fields_group);
  $jpa_contacts = scjpc_get_jpa_contacts_posts();

//  $jpa_contacts = get_posts([
//    "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//    "posts_per_page" => -1,
//    "order" => "ASC"
//  ]);
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_emergency_contacts', 'ajax_scjpc_export_emergency_contacts');
add_action('wp_ajax_nopriv_scjpc_export_emergency_contacts', 'ajax_scjpc_export_emergency_contacts');


function ajax_scjpc_export_emergency_contacts() {
  [$fields, $jpa_contacts] = scjpc_get_emergency_claim_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'emergency', true);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'emergency');
//  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'emergency', true);
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}


function scjpc_get_field_assistance_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_664639ed6409d"); // Server
  $fields = acf_get_fields($fields_group);
  $db_fields = acf_get_db_fields($fields_group['key']);

  foreach ($fields as $key => $field) {
    if ($field['key'] == 'field_664639ed86bac') {
      unset($fields[$key]);
    }
    if ($field['key'] == 'field_66463a5e86bad' && isset($db_fields['field_66463a5e86bad'])) {
      $fields[$key]['label'] = $db_fields['field_66463a5e86bad']->post_title;
    }
  }
  $jpa_contacts = scjpc_get_jpa_contacts_posts();
  return [$fields, $jpa_contacts];
}

add_action('wp_ajax_scjpc_export_field_assistance_contacts', 'ajax_scjpc_export_field_assistance_contacts');
add_action('wp_ajax_nopriv_scjpc_export_field_assistance_contacts', 'ajax_scjpc_export_field_assistance_contacts');

function ajax_scjpc_export_field_assistance_contacts() {
  [$fields, $jpa_contacts] = scjpc_get_field_assistance_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'field-assistance', true);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'field-assistance');
//  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'field-assistance', true);
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