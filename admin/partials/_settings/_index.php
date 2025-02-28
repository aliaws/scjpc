<?php
wp_enqueue_script('admin_settings_js', SCJPC_ASSETS_URL . 'js/admin-settings.js', ['jquery'], '1.6', true);
wp_enqueue_style('admin_settings_css', SCJPC_ASSETS_URL . 'css/admin-settings.css', [], time(), 'all');

$api_url_settings = trim(get_option('scjpc_es_host'), '/') . "/settings";
$settings_data = make_search_api_call($api_url_settings);
$settings = $settings_data['message'] ?? [];
$api_url_setting = trim(get_option('scjpc_es_host'), '/') . "/setting";

wp_localize_script('admin_settings_js', 'scjpc_data', [
    'api_url_setting' => $api_url_setting,
    'ajax_url' => admin_url('admin-ajax.php')
]);
?>

<div class="box-wrapper">
    <?php include '_list.php'; ?>
    <?php include '_form.php'; ?>
</div>