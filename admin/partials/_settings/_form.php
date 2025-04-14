<form id="settings-form" class="mt-4" style="max-width: 400px;">
    <input type="hidden" id="form-method" name="method" value="POST">
    <div class="mb-3">
        <label for="setting-key" class="form-label">Key</label>
        <input type="text" class="form-control" id="setting-key" name="key" required>
    </div>
    <div class="mb-3">
        <label for="setting-value" class="form-label">Value</label>
        <input type="text" class="form-control" id="setting-value" name="value">
    </div>
    <?php submit_button(); ?>
</form>