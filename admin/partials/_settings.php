<?php
$api_url_settings = trim(get_option('scjpc_es_host'), '/') . "/settings";
$settings_data = make_search_api_call($api_url_settings);
$settings = $settings_data['message'] ?? [];
$api_url_setting = trim(get_option('scjpc_es_host'), '/') . "/setting";

wp_localize_script('scjpc-settings', 'scjpc_data', [
    'api_url_setting' => $api_url_setting,
    'ajax_url' => admin_url('admin-ajax.php')
]);

if (!empty($settings)) { ?>
    <h6>Settings Data</h6>
    <div class="text-end mb-3">
        <button type="button" class="btn-primary create-setting btn" data-method='POST'>Create New Setting</button>
    </div>
    <div id="message" style="display:none;" class="alert"></div> 
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts" id="settings-table">
        <thead>
            <tr>
                <th scope="col" class="text-capitalize">Key</th>
                <th scope="col" class="text-capitalize">Value</th>
                <th scope="col" class="text-capitalize">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($settings as $setting): ?>
                <tr data-key="<?= $setting['key'] ?>">
                    <td><?= $setting['key'] ?></td>
                    <td><?= $setting['value'] ?></td>
                    <td>
                        <button type="button" class="btn-warning btn-sm edit-setting btn" data-key="<?= $setting['key'] ?>" data-value="<?= $setting['value'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn-danger btn-sm delete-setting btn" data-key="<?= $setting['key'] ?>">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php } else { ?>
    <div id="message" style="display:none;" class="alert"></div>
    <p>No settings found. You can create a new one below.</p>
<?php } ?>

<form id="settings-form" class="mt-4" style="max-width: 400px;">
    <input type="hidden" id="form-method" name="method" value="POST">
    <div class="mb-3">
        <label for="setting-key" class="form-label">Key</label>
        <input type="text" class="form-control" id="setting-key" name="key" required>
    </div>
    <div class="mb-3">
        <label for="setting-value" class="form-label">Value</label>
        <input type="text" class="form-control" id="setting-value" name="value" required>
    </div>
    <?php submit_button(); ?>
</form>