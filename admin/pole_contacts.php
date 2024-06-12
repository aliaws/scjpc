<?php
//add_action("elementor/frontend/after_enqueue_styles", "scjpc_enqueue_export_members_script");


add_shortcode('scjpc_pole_inspection_contacts', 'scjpc_pole_inspection_contacts_callback');
function scjpc_pole_inspection_contacts_callback() {
  [$fields, $jpa_contacts] = scjpc_get_pole_inspection_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, true);
  echo "<pre>" . print_r($response, true) . "</pre>";
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
