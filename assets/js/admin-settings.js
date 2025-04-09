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
    });

    $(document).on('click', '.edit-setting', function() {
        let $settingValue = $('#setting-value');
        let existingEmails = $(this).data('value') || '';
        $('#form-method').val('PUT');
        $('#setting-key').val($(this).data('key')).prop('disabled', true);
        $settingValue.siblings('.all-mail, .enter-mail-id').remove();
        $settingValue.show().email_multiple({ data: existingEmails });
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
        const parent = jQuery(jQuery(this).parent()[0]);
        const key = parent.data('key');
        const email = parent.data('email');

        if (confirm(`Delete "${email}" from "${key}"?`)) {
            const api_url = `${SCJPC_SETTINGS.API_URL_SETTING}/delete-tag?key=${key}&value=${email}`;
            console.log(api_url);
            $.post(SCJPC_SETTINGS.AJAX_URL, {
                action: 'delete_email_tag',
                api_url,
            }, function(response) {
                if (response.success) {
                    parent.remove();
                    showMessage(response.data.message || 'Email is deleted', 'success');
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
        const action = method === 'POST' ? 'create_setting' : 'update_setting';
        const api_url = method === 'POST'
            ? SCJPC_SETTINGS.API_URL_SETTING
            : `${SCJPC_SETTINGS.API_URL_SETTING}?key=${key}`;

        $.post(SCJPC_SETTINGS.AJAX_URL, {
            action,
            body: JSON.stringify({ key, value }),
            api_url
        }, function(response) {
            if (response.success) {
                showMessage(`Setting ${method === 'POST' ? 'created' : 'updated'} successfully!`, 'success');
                $('#settings-form')[0].reset();
                $('#setting-key').prop('disabled', false);
                $('#setting-value').siblings('.all-mail, .enter-mail-id').remove();

                const newRow = `
                <tr data-key="${key}">
                    <td>${key}</td>
                    <td>
                        <div class="tags-container">
                            ${value.split(',').map(email => `
                                <span class="tag email-tag delete-tag" data-key="${key}" data-email="${email}">
                                    ${email}
                                    <span class="remove-email-icon" title="Remove" style="cursor:pointer; margin-left:6px;">&times;</span>
                                    </span>
                            `).join('')}
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-setting" data-key="${key}" data-value="${value}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-setting" data-key="${key}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            
                if (method === 'PUT') {
                    $(`tr[data-key="${key}"]`).replaceWith(newRow);
                } else {
                    $('#settings-table tbody').append(newRow);
                }
            } else {
                showMessage(response.data.message || 'An error occurred.', 'danger');
            }
        }).fail(xhr => {
            showMessage(xhr.responseText || 'An unknown error occurred.', 'danger');
        });
    });
});
