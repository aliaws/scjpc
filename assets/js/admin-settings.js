jQuery(document).ready(function($) {
    const showMessage = (message, type) => {
        const messageDiv = $('#message');
        messageDiv.removeClass().addClass(`alert alert-${type}`).text(message).show();
        setTimeout(() => messageDiv.fadeOut(), 4000);
    };

    $(document).on('click', '.create-setting', function() {
        $('#form-method').val('POST');
        $('#setting-key').prop('disabled', false).val('');
        let $settingValue = $('#setting-value');
        $settingValue.siblings('.all-mail, .enter-mail-id').remove();
        $settingValue.show().val('');
        $settingValue.prop('required', true);
    });

    $(document).on('click', '.edit-setting', function() {
        let $settingValue = $('#setting-value');
        let existingEmails = $(this).data('value') || '';
        $('#form-method').val('PUT');
        $('#setting-key').val($(this).data('key')).prop('disabled', true);
        $settingValue.siblings('.all-mail, .enter-mail-id').remove();
        $settingValue.show().email_multiple({ data: existingEmails });
        $settingValue.prop('required', false);
    });

    $(document).on('click', '.delete-setting', function() {
        const key = $(this).data('key');
        if (confirm('Are you sure you want to delete this setting?')) {
            $.post(SCJPC_SETTINGS.AJAX_URL, {
                action: 'delete_setting',
                api_url: `${SCJPC_SETTINGS.API_URL_SETTING}?key=${key}`
            }, function(response) {
                if (response.success) {
                    $(`tr[data-key="${key}"]`).remove();
                    showMessage('Setting deleted successfully!', 'success');
                } else {
                    showMessage(response.data.message || 'An error occurred.', 'danger');
                }
            }).fail(xhr => {
                showMessage(`Error: ${xhr.responseText}`, 'danger');
            });
        }
    });

    jQuery(document).on('click', '.delete-tag>.remove-email-icon', function(e) {
        e.stopPropagation();
        const parent = jQuery(this).parent();
        const key = parent.data('key');
        const email = parent.data('email');

        if (confirm(`Delete "${email}" from "${key}"?`)) {
            const api_url = `${SCJPC_SETTINGS.API_URL_SETTING}/delete-tag?key=${key}&value=${email}`;

            $.post(SCJPC_SETTINGS.AJAX_URL, {
                action: 'delete_email_tag',
                api_url,
            }, function(response) {
                if (response.success) {
                    parent.remove();
                    showMessage(response.data.message || 'Email is deleted', 'success');
                    let updatedEmails = [];
                    $(`.email-tag[data-key="${key}"]`).each(function() {
                        updatedEmails.push($(this).data('email'));
                    });

                    $(`.edit-setting[data-key="${key}"]`).data('value', updatedEmails.join(','));

                    if ($('#setting-key').val() === key) {
                        $('#setting-value').val(updatedEmails.join(','));
                    }

                } else {
                    showMessage(response.data.message || 'Failed to delete.', 'danger');
                }
            }).fail(xhr => {
                showMessage(`Error: ${xhr.responseText}`, 'danger');
            });
        }
    });

    $('#settings-form').submit(function(e) {
        e.preventDefault();
        const method = $('#form-method').val();
        const key = $('#setting-key').val().trim();
        const value = $('#setting-value').val().trim();

        if (method === 'POST' && !value) {
            showMessage('The value field is required!', 'danger');
            return;
        }
        if (method === 'PUT' && !value) {
            showMessage('At least one email is required!', 'danger');
            return;
        }

        const action = method === 'POST' ? 'create_setting' : 'update_setting';
        const api_url = method === 'POST'
            ? SCJPC_SETTINGS.API_URL_SETTING
            : `${SCJPC_SETTINGS.API_URL_SETTING}?key=${key}`;

        $.post(SCJPC_SETTINGS.AJAX_URL, { action, body: JSON.stringify({ key, value }), api_url })
            .done(function(response) {
                if (!response.success) {
                    showMessage(response.data.message || 'An error occurred.', 'danger');
                    return;
                }

                showMessage(`Setting ${method === 'POST' ? 'created' : 'updated'} successfully!`, 'success');

                $.post(SCJPC_SETTINGS.AJAX_URL, { action: 'render_setting_row', key, value })
                    .done(function(resp) {
                        if (resp.success) {
                            if (method === 'PUT') {
                                $(`tr[data-key="${key}"]`).replaceWith(resp.data);
                            } else {
                                $('#settings-table tbody').append(resp.data);
                            }
                            $('#settings-form')[0].reset();
                            $('#setting-key').prop('disabled', false);
                            $('#setting-value').siblings('.all-mail, .enter-mail-id').remove();
                        } else {
                            showMessage(resp.data || 'Failed to render row', 'danger');
                        }
                    })
                    .fail(function(xhr) {
                        showMessage(xhr.responseText || 'Failed to render row', 'danger');
                    });

            })
            .fail(function(xhr) {
                showMessage(xhr.responseText || 'An unknown error occurred.', 'danger');
            });
    });
});
