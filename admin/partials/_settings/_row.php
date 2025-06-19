<tr data-key="<?= esc_attr($key) ?>">
    <td><?= esc_html($key) ?></td>
    <td>
        <?php $values = array_filter(explode(',', trim($value))); ?>
        <div class="tags-container">
            <?php foreach ($values as $value): ?>
                <span class="tag email-tag delete-tag" data-key="<?= esc_attr($key) ?>" data-email="<?= esc_attr(trim($value)) ?>">
                    <?= esc_html(trim($value)) ?>
                    <span class="remove-email-icon" title="Remove" style="cursor:pointer; margin-left:6px;">&times;</span>
                </span>
            <?php endforeach; ?>
        </div>
    </td>
    <td>
        <button class="btn btn-warning btn-sm edit-setting" data-key="<?= esc_attr($key) ?>" data-value="<?= esc_attr($value) ?>">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm delete-setting" data-key="<?= esc_attr($key) ?>">
            <i class="fas fa-trash-alt"></i>
        </button>
    </td>
</tr>
