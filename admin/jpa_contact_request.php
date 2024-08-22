<?php


add_action('init', 'scjpc_register_post_type_jpa_contact_request');
/**
 * This method registers the custom post type jpa contacts request to upsert JPA Contact
 * by default the post is saved as draft and when it is published the request is executed i.e a contact is created or updated
 * @return void
 */
function scjpc_register_post_type_jpa_contact_request(): void {
  $supports = [
    'title', // post title
    'editor', // post content
    'author', // post author
//    'thumbnail', // featured images
//    'excerpt', // post excerpt
    'custom-fields', // custom fields
//    'comments', // post comments
    'revisions', // post revisions
//    'post-formats', // post formats
  ];
  $labels = array(
    'name' => _x('JPA Contact Requests', 'plural', 'scjpc'),
    'singular_name' => _x('JPA Contact Request', 'singular', 'scjpc'),
    'menu_name' => _x('JPA Contact Request', 'admin menu', 'scjpc'),
    'name_admin_bar' => _x('JPA Contact Request', 'admin bar', 'scjpc'),
//    'add_new' => _x('Add New', 'add new'),
//    'add_new_item' => __('Add New news'),
//    'new_item' => __('New news', 'scjpc'),
//    'edit_item' => __('Edit news'),
    'view_item' => __('View JPA Contact Request', 'scjpc'),
    'all_items' => __('All JPA Contact Request', 'scjpc'),
    'search_items' => __('Search JPA Contact Request', 'scjpc'),
    'not_found' => __('No JPA Contact Request found.', 'scjpc'),
  );
  $args = array(
    'supports' => $supports,
    'labels' => $labels,
    'public' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'jpa-contact-request'),
    'has_archive' => false,
    'hierarchical' => false,
//    'show_in_menu' => 'scjpc', // Add this line to place under the "Scjpc" menu
  );
  register_post_type('jpa_contact_request', $args);
}

add_filter('gform_pre_render_1', 'scjpc_enqueue_gform_scripts');
//add_filter('gform_pre_validation_1', 'scjpc_enqueue_gform_scripts');
add_filter('gform_admin_pre_render_1', 'scjpc_enqueue_gform_scripts');
//add_filter('gform_pre_submission_filter_1', 'scjpc_enqueue_gform_scripts');

/**
 * this method enqueues a custom js script when the contact upsert request form is loaded
 * @param $form
 * @return mixed
 */
function scjpc_enqueue_gform_scripts($form): mixed {
  wp_enqueue_script("scjpc-gform", SCJPC_ASSETS_URL . "js/gform.js", array("jquery"), '1.0336', true);
  return $form;
}

add_action('gform_after_submission_1', 'scjpc_process_jpa_contact_request', 10, 2);
/**
 * this method creates a draft post of jpa_contact_request type when the upsert contact form is submitted.
 * @param $entry
 * @param $form
 * @return void
 */
function scjpc_process_jpa_contact_request($entry, $form): void {
  $current_user = wp_get_current_user();
  $fields_mapping = $field_values = [];
  foreach ($form['fields'] as $field) {
    [$fields_mapping, $field_values] = scjpc_extract_field_label($field, $fields_mapping, $field_values);
  }
  foreach ($fields_mapping as $label => $id) {
    $field_values[$label] = $entry[$id] ?? '';
  }
  $request_type = ucwords(str_replace('_', ' ', $field_values['request_type']));
  $post_id = wp_insert_post([
    'post_type' => 'jpa_contact_request',
    'post_status' => 'draft',
    'post_title' => "{$field_values['company']} - {$field_values['member_code']} - $request_type",
    'post_author' => $current_user->ID,
  ]);

  $unwanted_meta_fields = ['action'];
  if (!empty($field_values['cable_tags'])) {
    $cable_tags = json_decode($field_values['cable_tags']);
    foreach ($cable_tags as $index => $url) {
      $cable_tags[$index] = upload_image_to_media_library($url);

    }
    $field_values['cable_tags'] = $cable_tags;
  }
  $field_values = scjpc_append_acf_field_groups($field_values);
  $field_values['gform_entry_id'] = "/wp-admin/admin.php?page=gf_entries&view=entry&id=1&lid={$entry['id']}";
  foreach ($field_values as $label => $value) {
    if (!in_array($label, $unwanted_meta_fields)) {
      update_post_meta($post_id, $label, $value);
    }
  }

}


function upload_image_to_media_library($image_url): WP_Error|int {
  $upload_dir = wp_upload_dir();
  $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);

  if (!file_exists($image_path)) {
    return new WP_Error('file_not_found', 'File not found.');
  }

  $filetype = wp_check_filetype(basename($image_path), null);
  $attachment = array(
    'guid' => $upload_dir['url'] . '/' . basename($image_path),
    'post_mime_type' => $filetype['type'],
    'post_title' => sanitize_file_name(basename($image_path)),
    'post_content' => '',
    'post_status' => 'inherit'
  );
  $attach_id = wp_insert_attachment($attachment, $image_path);

  require_once(ABSPATH . 'wp-admin/includes/image.php');
  $attach_data = wp_generate_attachment_metadata($attach_id, $image_path);
  wp_update_attachment_metadata($attach_id, $attach_data);
  return $attach_id;
}

add_action('wp_ajax_scjpc_validate_member_code', 'scjpc_validate_member_code_callback');
add_action('wp_ajax_nopriv_scjpc_validate_member_code', 'scjpc_validate_member_code_callback');
function scjpc_validate_member_code_callback(): void {
  try {
    $result = scjpc_validate_member_code_existence($_POST['member_code']);
    wp_send_json_success(['code_exists' => !empty($result), 'error' => false]);
  } catch (Exception $e) {
    wp_send_json_error(['message' => $e->getMessage(), 'error' => true]);
  }
  wp_die();
}

function scjpc_validate_member_code_existence($member_code = ''): array|null {
  $sql = "SELECT wp_postmeta.meta_value, wp_postmeta.post_id FROM wp_postmeta WHERE 
            wp_postmeta.meta_value = '$member_code' 
            AND wp_postmeta.meta_key = 'member_code' 
            AND post_id IN (SELECT id FROM wp_posts WHERE post_type = 'member' and post_status = 'publish');";
  global $wpdb;
  return $wpdb->get_row($sql, ARRAY_A);
}


function scjpc_extract_field_label($field, $fields_mapping, $field_values): array {
  if ($field->adminLabel != '') {
    if (is_array($field->inputs)) {
      foreach ($field->inputs as $key => $input) {
        if ($field->type == 'checkbox') {
          $field_values[$field->choices[$key]['value']] = $field->choices[$key]['isSelected'];
          $fields_mapping[$field->choices[$key]['value']] = $input['id'];
        } else {
          if (!empty($input['customLabel'])) {
            $fields_mapping[$input['customLabel']] = $input['id'];
          }
        }
      }
    } else {
      $fields_mapping[$field->adminLabel] = $field->id;
    }
  }
  return [$fields_mapping, $field_values];
}

add_action('transition_post_status', 'scjpc_upsert_contact_on_publishing_request', 10, 3);
function scjpc_upsert_contact_on_publishing_request($new_status, $old_status, $post): void {
  if ($new_status === 'publish' && $old_status !== 'publish' && $post->post_type === 'jpa_contact_request') {
    $request_meta = scjpc_get_post_meta($post->ID);
    if ($request_meta['request_type'] == 'update_member') {
      $result = scjpc_validate_member_code_existence($request_meta['member_code']);
      $member_id = $result['post_id'] ?? 0;
      wp_update_post(['ID' => $member_id, 'post_title' => "{$request_meta['company']}"]);
    } else {
      $current_user = wp_get_current_user();
      $member_id = wp_insert_post([
        'post_type' => 'member',
        'post_status' => 'publish',
        'post_title' => "{$request_meta['company']}",
        'post_author' => $current_user->ID,
      ]);
    }
    if ($member_id != 0) {
      foreach ($request_meta as $key => $value) {
        update_post_meta($member_id, $key, $value);
      }
    }
  }
}

function scjpc_get_post_meta($post_id): array {
  $post_meta = get_post_meta($post_id) ?? [];
  foreach ($post_meta as $key => $value) {
    $post_meta[$key] = $key == 'cable_tags' ? unserialize($value[0]) : $value[0];
  }
  return $post_meta;
}

function scjpc_append_acf_field_groups(mixed $field_values) {
  $field_values['_cable_tags'] = 'field_663e59388793a';
  $field_values['_contact_jpa'] = 'field_66464f0e2545b';
  $field_values['_claims_contact'] = 'field_66465007f76c5';
  $field_values['_member_code'] = 'field_662d02563dbb0';
  $field_values['_company'] = 'field_662d26756a0a3';
  $field_values['_primary_representative'] = 'field_666c61f482c7d';
  $field_values['_representative_alternate'] = 'field_662d02d73dbb2';
  $field_values['_authorized_signature__bill_of_sale_form_44'] = 'field_666c62b1248ca';
  $field_values['_billing_address'] = 'field_662d03383dbb5';
  $field_values['_form_submission_electronic'] = 'field_662d03693dbb6';
  $field_values['_form_submission_mailing'] = 'field_662d03ab3dbb7';
  $field_values['_additional_form_mailing_address'] = 'field_662d04103dbb8d';
  $field_values['_request_to_expedite_jpa'] = 'field_662d04323dbb9';
  $field_values['_additional_information'] = 'field_662d044139ef6';

  $field_values['_last_updated'] = 'field_664639ed86bac';
  $field_values['_claim_contact_updated'] = 'field_6646501af76c6';
  $field_values['_representative_primary_last_updated_at'] = 'field_6667289bc34bb';
  $field_values['_representative_alternate_last_updated_at'] = 'field_66672be82ab0a';
  $field_values['_authorized_signature__bill_of_sale__form_44_last_updated_at'] = 'field_66672c195b777';
  $field_values['_billing_address_last_updated_at'] = 'field_66672c4c35b2d';
  $field_values['_form_submission_electronic_last_updated_at'] = 'field_66672c7678aa5';
  $field_values['_form_submission_mailing_last_updated_at'] = 'field_66672ca309cc6';
  $field_values['_additional_form_mailing_address_last_updated_at'] = 'field_66672cfa068f9';
  $field_values['_request_to_expedite_jpa_last_updated_at'] = 'field_66672d10068fa';
  $field_values['_additional_information_last_updated_at'] = 'field_66672df774d61';
  $field_values['_priority_poles_definitions_last_updated_at'] = 'field_66672e23d4005';
  $field_values['_approved_contractors_last_updated_at'] = 'field_66672e57db724';
  $field_values['_authorized_signature_bill_of_sale_form_44_last_updated_at'] = 'field_66672e57db724';
  $field_values['_pi_last_updated'] = 'field_6671920e4198b';
  $field_values['_contact_last_updated_at'] = 'field_66464f6f2545c';
  $field_values['_claim_contact_last_updated_at'] = 'field_6646501af76c6';
  $field_values['_ii_last_updated'] = 'field_6667411894757';

  if ($field_values['request_type'] == 'new_member') {
    $current_date = date('Ymd');
    $field_values['last_updated'] = $current_date;
    $field_values['claim_contact_updated'] = $current_date;
    $field_values['representative_primary_last_updated_at'] = $current_date;
    $field_values['representative_alternate_last_updated_at'] = $current_date;
    $field_values['authorized_signature__bill_of_sale__form_44_last_updated_at'] = $current_date;
    $field_values['billing_address_last_updated_at'] = $current_date;
    $field_values['form_submission_electronic_last_updated_at'] = $current_date;
    $field_values['form_submission_mailing_last_updated_at'] = $current_date;
    $field_values['additional_form_mailing_address_last_updated_at'] = $current_date;
    $field_values['request_to_expedite_jpa_last_updated_at'] = $current_date;
    $field_values['additional_information_last_updated_at'] = $current_date;
    $field_values['priority_poles_definitions_last_updated_at'] = $current_date;
    $field_values['approved_contractors_last_updated_at'] = $current_date;
    $field_values['authorized_signature_bill_of_sale_form_44_last_updated_at'] = $current_date;
    $field_values['pi_last_updated'] = $current_date;
    $field_values['contact_last_updated_at'] = $current_date;
    $field_values['claim_contact_last_updated_at'] = $current_date;
    $field_values['ii_last_updated'] = $current_date;
  }
  return $field_values;
}
