jQuery(document).ready(function($) {
    const showMessage = (message, type) => {
        const messageDiv = $('#message');
        messageDiv.removeClass().addClass(`alert alert-${type}`).text(message).show();
        setTimeout(() => messageDiv.hide(), 5000);
    };

    jQuery(document).on('click', '.create-setting', function() {
        $('#form-method').val('POST');
        $('#setting-key').prop('disabled', false).val('');
        let $settingValue = $('#setting-value');
        $settingValue.siblings('.all-mail, .enter-mail-id').remove();
        $settingValue.show().val('');
    });

    jQuery(document).on('click', '.edit-setting', function() {
        let $settingValue = $('#setting-value');
        let existingEmails = $(this).data('value') || '';
        console.log('Existing Emails:', existingEmails);
        $('#form-method').val('PUT');
        $('#setting-key').val($(this).data('key')).prop('disabled', true);
        $settingValue.siblings('.all-mail, .enter-mail-id').remove();
        $settingValue.show().email_multiple({ data: existingEmails });
    });

    jQuery(document).on('click', '.delete-setting', function() {
        const key = $(this).data('key');
        if (confirm('Are you sure you want to delete this setting?')) {
            $.post(ajaxurl, { action: 'delete_setting', api_url: `${SCJPC_SETTINGS.API_URL_SETTING}?key=${key}` }, (response) => {
                if (response.success) {
                    showMessage('Setting deleted successfully!', 'success');
                    location.reload();
                } else {
                    showMessage(response.data.message || 'An error occurred.', 'danger');
                }
            }).fail(xhr => showMessage(`Error: ${xhr.responseText}`, 'danger'));
        }
    });

    jQuery('#settings-form').submit(function(e) {
        e.preventDefault();
        const method = $('#form-method').val();
        const key = $('#setting-key').val().trim();
        const value = $('#setting-value').val().trim();
        const action = method === 'POST' ? 'create_setting' : 'update_setting';
        const api_url = method === 'POST' ? SCJPC_SETTINGS.API_URL_SETTING : `${SCJPC_SETTINGS.API_URL_SETTING}?key=${key}`;

        jQuery.post(SCJPC_SETTINGS.AJAX_URL, { action, body: JSON.stringify({ key, value }), api_url }, (response) => {
            if (response.success) {
                showMessage(`Setting ${method === 'POST' ? 'created' : 'updated'} successfully!`, 'success');
                location.reload();
            } else {
                showMessage(response.data.message || 'An unexpected error occurred.', 'danger');
            }
        }).fail(xhr => {
            alert(xhr.responseText || 'An unknown error occurred.');
        });
    });
});
