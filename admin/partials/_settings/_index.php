<?php
$base_url = get_option('scjpc_es_host');
wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
wp_enqueue_style('admin-settings', SCJPC_ASSETS_URL . 'css/admin-settings.css', [], time(), 'all');
wp_enqueue_script('admin-settings', SCJPC_ASSETS_URL . 'js/admin-settings.js', ['jquery'], '4.1', true);
wp_enqueue_style('admin-email', SCJPC_ASSETS_URL . 'css/email-multiple.css', [], time(), 'all');
wp_enqueue_script('admin-email', SCJPC_ASSETS_URL . 'js/email-multiple.js', ['jquery'], '1.6', true);
$api_url_setting = trim($base_url, '/') . "/setting";
wp_localize_script('admin-settings', 'SCJPC_SETTINGS', [
    'API_URL_SETTING' => $api_url_setting,
    'AJAX_URL' => admin_url('admin-ajax.php')
]);
$api_url_settings = trim($base_url, '/') . "/settings";
$settings_data = make_search_api_call($api_url_settings);
$settings = $settings_data['message'] ?? [];


?>

<div class="box-wrapper">
    <?php include '_list.php'; ?>
    <?php include '_form.php'; ?>
</div>