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
            <?php foreach ($settings as $setting): ?>
                <tr data-key="<?= $setting['key'] ?>">
                    <td><?= $setting['key'] ?></td>
                    <td>
                        <?php $values = array_filter(explode(',', trim($setting['value']))); ?>
                        <div class="tags-container">
                            <?php foreach ($values as $value): ?>
                                <span class="tag email-tag delete-tag" data-key="<?= esc_attr($setting['key']) ?>" data-email="<?= esc_attr(trim($value)) ?>">
                                    <?= trim($value) ?>
                                    <span class="remove-email-icon" title="Remove" style="cursor:pointer; margin-left:6px;">&times;</span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </td>
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