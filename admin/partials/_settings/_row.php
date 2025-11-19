<?php
if (!isset($key) || !isset($setting)) return;

$rawValue = $setting['value'] ?? '';
$values = array_map('trim', explode(',', $rawValue));
?>
<tr data-key="<?= esc_attr($key) ?>">
    <td><?= esc_html($key) ?></td>
    <td>
        <div class="tags-container">
            <?php foreach ($values as $val): ?>
                <?php if ($val !== '' && !empty($setting['update_able'])): ?>
                    <span class="tag email-tag delete-tag"
                          data-key="<?= esc_attr($key) ?>"
                          data-email="<?= esc_attr($val) ?>">
                        <?= esc_html($val) ?>
                        <span class="remove-email-icon"
                              title="Remove"
                              style="cursor:pointer; margin-left:6px;">&times;</span>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </td>
    <td>
        <?php if (!empty($setting['update_able'])): ?>
            <button class="btn btn-warning btn-sm edit-setting"
                    data-key="<?= esc_attr($key) ?>"
                    data-value="<?= esc_attr($rawValue) ?>">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-danger btn-sm delete-setting"
                    data-key="<?= esc_attr($key) ?>">
                <i class="fas fa-trash-alt"></i>
            </button>
        <?php endif; ?>
    </td>
</tr>
