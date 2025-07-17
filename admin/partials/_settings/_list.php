<?php if (!empty($settings)) { ?>
    <h6>Settings Data</h6>
    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary create-setting" data-method='POST'>Create</button>
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
            <?php foreach ($settings as $key => $setting): ?>
                <?php include '_row.php'; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php } else { ?>
    <div id="message" style="display:none;" class="alert"></div>
    <p>No settings found. You can create a new one below.</p>
<?php } ?>