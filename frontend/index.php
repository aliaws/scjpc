<?php
include_once(SCJPC_PLUGIN_FRONTEND_BASE . 'functions.php');
function scjpc_jpg_search() {
    ob_start();
      include_once SCJPC_PLUGIN_FRONTEND_BASE."pages/jpa_search.php";
    return ob_get_clean();
}

add_shortcode('scjpc_jpg_search', 'scjpc_jpg_search');

function scjpc_multiple_jpa_search() {
    ob_start();
      include_once SCJPC_PLUGIN_FRONTEND_BASE."pages/multiple_jpa_search.php";
    return ob_get_clean();
}

add_shortcode('scjpc_multiple_jpa_search', 'scjpc_multiple_jpa_search');

function scjpc_quick_pole_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE."pages/quick_pole_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_quick_pole_search', 'scjpc_quick_pole_search');

function scjpc_advanced_pole_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE."pages/advanced_pole_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_advanced_pole_search', 'scjpc_advanced_pole_search');
function scjpc_multiple_pole_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE."pages/multiple_pole_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_multiple_pole_search', 'scjpc_multiple_pole_search');
function scjpc_website_doc_search() {
  ob_start();
  include_once SCJPC_PLUGIN_FRONTEND_BASE."pages/website_doc_search.php";
  return ob_get_clean();
}

add_shortcode('scjpc_website_doc_search', 'scjpc_website_doc_search');

