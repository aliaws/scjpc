<?php 
function load_bootstrap_assets() {

    wp_enqueue_script('bootstrap_js', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js");

    wp_enqueue_style('bootstrap_css', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
    wp_enqueue_script('jquery');

    wp_enqueue_script('frontend_js', SCJPC_ASSETS_URL . 'js/frontend.js', false, '2.0', true);
  }
  
?>