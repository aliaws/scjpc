<?php

add_shortcode('scjpc_pole_inspection_contacts', 'scjpc_pole_inspection_contacts_callback');
/**
 * this method renders the HTML table for pole inspection contacts
 * @return void
 */
function scjpc_pole_inspection_contacts_callback(): void {
  [$fields, $pole_contacts] = scjpc_get_pole_inspection_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $pole_contacts, 'pole');
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/contacts.php';
  echo ob_get_clean();
}

add_shortcode('scjpc_buddy_pole_contacts', 'scjpc_buddy_pole_contacts_callback');
function scjpc_buddy_pole_contacts_callback(): void {
  [$fields, $jpa_contacts] = scjpc_get_buddy_pole_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'buddy-pole');
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/contacts.php';
  echo ob_get_clean();
}

add_shortcode('scjpc_graffiti_removal_contacts', 'scjpc_graffiti_removal_contacts_callback');
function scjpc_graffiti_removal_contacts_callback(): void {
  [$fields, $jpa_contacts] = scjpc_get_graffiti_removal_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'graffiti-removal');
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/contacts.php';
  echo ob_get_clean();
}

add_shortcode('scjpc_emergency_claim_contacts', 'scjpc_emergency_claim_contacts_callback');
function scjpc_emergency_claim_contacts_callback(): void {
  [$fields, $jpa_contacts] = scjpc_get_emergency_claim_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'emergency');
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/contacts.php';
  echo ob_get_clean();
}

add_shortcode('scjpc_jpa_contacts', 'scjpc_jpa_contacts_callback');
function scjpc_jpa_contacts_callback(): void {
//  [$fields, $jpa_contacts] = scjpc_get_jpa_contacts();
  [$fields, $jpa_contacts] = scjpc_get_jpa_contacts_all_fields();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'jpa', false, false);
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/contacts.php';
  echo ob_get_clean();
}

add_shortcode('scjpc_field_assistance_contacts', 'scjpc_field_assistance_contacts_callback');
function scjpc_field_assistance_contacts_callback(): void {
  [$fields, $jpa_contacts] = scjpc_get_field_assistance_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'field-assistance');
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/contacts.php';
  echo ob_get_clean();
}

add_shortcode('scjpc_cable_tags_pole_markings', 'scjpc_cable_tags_pole_markings_callback');
function scjpc_cable_tags_pole_markings_callback(): void {
  [$fields, $jpa_contacts] = scjpc_get_cable_marking_contacts();
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'cable-tags', false, false);
  ob_start();
  require_once SCJPC_PLUGIN_FRONTEND_BASE . 'table/contacts.php';
  echo ob_get_clean();
}


add_shortcode('scjpc_jpa_search', 'scjpc_jpa_search');
function scjpc_jpa_search(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/jpa_search.php";
  return ob_get_clean();
}


add_shortcode('scjpc_multiple_jpa_search', 'scjpc_multiple_jpa_search');
function scjpc_multiple_jpa_search(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/multiple_jpa_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_quick_pole_search', 'scjpc_quick_pole_search');
function scjpc_quick_pole_search(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/quick_pole_search.php";
  return ob_get_clean();
}


add_shortcode('scjpc_advanced_pole_search', 'scjpc_advanced_pole_search');
function scjpc_advanced_pole_search(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/advanced_pole_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_multiple_pole_search', 'scjpc_multiple_pole_search');
function scjpc_multiple_pole_search(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/multiple_pole_search.php";
  return ob_get_clean();
}


add_shortcode('scjpc_pole_search', 'scjpc_pole_search');
function scjpc_pole_search(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/jpa_detail_search.php";
  return ob_get_clean();
}


add_shortcode('scjpc_pole_detail', 'scjpc_pole_detail');
function scjpc_pole_detail(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/pole_detail.php";
  return ob_get_clean();
}


add_shortcode('scjpc_download_export', 'scjpc_download_export');
function scjpc_download_export(): bool|string {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE . "pages/download_export.php";
  return ob_get_clean();
}
