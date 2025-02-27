<?php
load_bootstrap_assets();
load_admin_assets();

$api_url_es_db_counts = trim(get_option('scjpc_es_host'), '/') . "/es-db-counts";
$api_url_deleted_poles = trim(get_option('scjpc_es_host'), '/') . "/deleted-records?table=deleted_poles&order=desc";
$api_url_deleted_jpas = trim(get_option('scjpc_es_host'), '/') . "/deleted-records?table=deleted_jpas&order=desc";
$api_url_settings = trim(get_option('scjpc_es_host'), '/') . "/settings";
$api_url_setting = trim(get_option('scjpc_es_host'), '/') . "/setting";

$db_db_counts = make_search_api_call($api_url_es_db_counts);
$deleted_poles = make_search_api_call($api_url_deleted_poles);
$deleted_jpas = make_search_api_call($api_url_deleted_jpas);
$settings_data = make_search_api_call($api_url_settings);
if (!empty($db_db_counts)) {
    ?>
    <div class="alert custom-alert-wrapper d-none my-3 alert-success " role="alert">
        <h4 class="alert-heading custom-alert m-2 fs-5 ">CDN cache has been successfully cleared</h4>
    </div>
    <div class="export-container overflow-auto">
        
        <div class=" my-2 float-end" role="group" aria-label="Basic example">
            <button data-api-action = "clear-cdn-cache"  data-key = "/exports/*" id="clear-export" type="button" class="btn btn-primary clear-cache">Clear Export Cache</button>
            <button data-api-action = "clear-cdn-cache" data-key = "/pdf/*" id="clear-pdf" type="button" class="btn btn-primary clear-cache">Clear PDF Cache</button>
            <button data-api-action = "flush-redis-cache" id="clear-redis" type="button" class="btn btn-primary clear-cache">Clear Redis Cache</button>
            <button data-method="POST" data-api-action = "elastic-search-re-index" id="re-index"  data-elastic_search_re_index="yes" type="button" class="btn btn-primary clear-cache">Re-Index Elastic Search</button>
            <button data-method="POST" data-api-action = "remove-deleted-data" id="remove-deleted-data"  data-remove_deleted_data="yes" type="button" class="btn btn-primary clear-cache">Remove Deleted Data</button>
        </div>
        <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
            <thead>
            <tr>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    Entity
                </th>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    DB Count
                </th>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    ES Count
                </th>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    Difference
                </th>
                <th title="Deleted Difference" scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    Difference From Client DB
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="align-middle" scope="row">
                    Poles
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['poles_db_count']; ?>
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['poles_es_count']; ?>
                </td>
                <td class="align-middle <?php echo $db_db_counts['poles_db_es_diff'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $db_db_counts['poles_db_es_diff']; ?>
                </td>
                <td class="align-middle <?php echo $deleted_poles['total'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $deleted_poles['total']; ?>
                </td>
            </tr>
            <tr>
                <td class="align-middle" scope="row">
                    JPAs
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['jpas_db_count']; ?>
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['jpas_es_count']; ?>
                </td>
                <td class="align-middle <?php echo $db_db_counts['jpas_db_es_diff'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $db_db_counts['jpas_db_es_diff']; ?>
                </td>
                <td class="align-middle <?php echo $deleted_jpas['total'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $deleted_jpas['total']; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="card p-4"><p> No Requests found!</p></div>
<?php } ?>
<?php
$deleted_ids = ["Poles" => $deleted_poles["deleted_ids"], "Jpas" => $deleted_jpas["deleted_ids"]];
foreach($deleted_ids as $heading => $ids): ?>
    <h6> Deleted <?php echo $heading; ?></h6>
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">

    <?php
        foreach($ids as $id):  ?>
            <tr>
                <td class="align-middle" scope="row">
                    <?php echo $id; ?>
                </td>
            </tr>
    <?php endforeach; ?>
    </table>
<?php endforeach; ?>

<?php if (!empty($settings_data)) { ?>
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
            <?php foreach ($settings_data as $setting): ?>
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

<script>
jQuery(document).ready(function($) {
    const showMessage = (message, type) => {
        const messageDiv = $('#message');
        messageDiv.removeClass().addClass(`alert alert-${type}`).text(message).show();
        setTimeout(() => messageDiv.hide(), 5000);
    };

    $('.create-setting').click(() => {
        $('#form-method').val('POST');
        $('#setting-key').prop('disabled', false).val('');
        $('#setting-value').val('');
    });

    $(document).on('click', '.edit-setting', function() {
        $('#form-method').val('PUT');
        $('#setting-key').val($(this).data('key')).prop('disabled', true);
        $('#setting-value').val($(this).data('value'));
    });

    $(document).on('click', '.delete-setting', function() {
    const key = $(this).data('key');
    if (confirm('Are you sure you want to delete this setting?')) {
        $.post(ajaxurl, { action: 'delete_setting', api_url: `<?php echo $api_url_setting ?>?key=${key}` }, (response) => {
            if (response.success) {
                showMessage('Setting deleted successfully!', 'success');
                location.reload();
            } else {
                showMessage(response.data.message || 'An error occurred.', 'danger');
            }
        }).fail(xhr => showMessage(`Error: ${xhr.responseText}`, 'danger'));
    }
});

   $('#settings-form').submit(function(e) {
    e.preventDefault();
    const method = $('#form-method').val();
    const key = $('#setting-key').val();
    const value = $('#setting-value').val();
    const action = method === 'POST' ? 'create_setting' : 'update_setting';
    const api_url = method === 'POST' ? '<?php echo $api_url_setting ?>' : `<?php echo $api_url_setting ?>?key=${key}`;

    $.post(ajaxurl, { action, body: JSON.stringify({ key, value }), api_url }, (response) => {
        if (response.success) {
            showMessage(`Setting ${method === 'POST' ? 'created' : 'updated'} successfully!`, 'success');
            location.reload();
        } else {
            showMessage(response.data.message || 'An unexpected error occurred.', 'danger');
        }
    }).fail(xhr => {
    try {
        const errorResponse = JSON.parse(xhr.responseText);
        showMessage(errorResponse.data?.message || errorResponse.message || 'An unexpected error occurred.', 'danger');
    } catch (e) {
        showMessage(xhr.responseText || 'An unknown error occurred.', 'danger');
    }
});
});


});
</script>

<style>
    .small-width-column {
        width: 3.5em;
    }
    .bold-red {
        color:red !important;
        font-weight: bold;
    }
    button.edit-setting.btn {
    background-color: #ffc107 !important;
    color: #fff !important;
}

    button.delete-setting.btn {
    background-color: #dc3545 !important;
    color: #fff !important;
}
    button.create-setting.btn { 
        background-color: #007bff !important;
        color: #fff;
}
button.edit-setting, button.delete-setting {
    padding: 2px 5px !important; /* Smaller padding */
    font-size: 12px !important; /* Smaller text */
    width: auto !important; /* Prevent forced width */
    height: auto !important; /* Prevent forced height */
}

</style>
