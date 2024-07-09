jQuery(document).ready(function () {
    const jpaModal = jQuery('#jpaModal'); // Use jQuery to get the modal element
    jpaModal.on('show.bs.modal', function (e) {
        // Button that triggered the modal
        const button = jQuery(e.relatedTarget); // Wrap button in jQuery
        const unique_id = button.data('edit-id');
        const jpa_number = button.data('edit-jpa-number');
        const pdf_path = button.data('edit-pdf');
        const aws_cdn = button.data('aws-cdn');
        const path = new URL(pdf_path).pathname;
        const custom_s3_key = (path !== "/") ? path.slice(1) : `pdf/revised/${jpa_number}${unique_id}.pdf`;
        jQuery('.edit_unique_id').text(unique_id);
        jQuery('.edit_jpa_number').text(jpa_number);
        jQuery('#form_unique_id').attr('value', unique_id);
        jQuery('#form_jpa_number').attr('value', jpa_number);
        jQuery('#update_file').attr('value', pdf_path);
        jQuery('#s3_key').attr('value', custom_s3_key);
        jQuery('#aws_cdn_url').attr('value', aws_cdn);

    });
    jQuery('#update_jpa_search').on('submit', function (event) {
        event.preventDefault()
        jQuery('#update_submit').text('Updating').attr('disabled', true);
        const form = event.target;
        const form_data = new FormData(form);
        jQuery(this).find('input').each(function () {
            const input_name = jQuery(this).attr('name');
            form_data[input_name] = jQuery(this).val();
        });
        jQuery.ajax({
            url: admin_ajax_url,
            method: 'post', // Use 'type' instead of 'method'
            data: form_data,
            processData: false,
            contentType: false,
            success: (response) => {
                const {jpa_unique_id, aws_cdn_url, s3_key} = form_data;
                jQuery(`#edit-icon-${jpa_unique_id}`).before("" +
                    "<a class='text-decoration-none pdf-icon-wrapper' href='"+aws_cdn_url+"/"+s3_key+"'>\n" +
                    "  <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor'\n" +
                    "        class='bi bi-file-pdf text-danger' viewBox='0 0 16 16'>\n" +
                    "    <path\n" +
                    "      d='M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1'/>\n" +
                    "    <path\n" +
                    "      d='M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z'/>\n" +
                    "  </svg>\n" +
                    "</a>");
                jQuery('#update_submit').text('Update').attr('disabled', false);
                jQuery('#update_file').val('');
                jQuery('#jpaModal').modal('hide');
            },
            error: (error) => {
                jQuery('#update_submit').text('Update').attr('disabled', false);
                console.log('error==', error);
            }
        });

    });

    ['clear-export', 'clear-pdf', 'clear-redis', 're-index', 'es_settings'].forEach(button => {
        jQuery(`button#${button}`).on('click', () => {
            const clear_cache_button = jQuery(`button#${button}`);
            clear_cache_button.prop('disabled', true);
            execute_clear_cache(clear_cache_button)
        })
    })
    function execute_clear_cache(button) {
        const body = button.data();
        body['action'] = 'flush_cache';
        console.log(body);
        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: body,
            success: function(response) {
                button.removeAttr('disabled');
                showSuccessNotification(response);
            },
            error: function(error) {
                console.log('error==', error);
            }
        });
    }
    function showSuccessNotification(message) {
        console.log("message",message);
        jQuery('.custom-alert').text(message.replace(/"/g, ''));
        jQuery('.custom-alert-wrapper').removeClass('d-none');

        setTimeout(function() {
            jQuery('.custom-alert-wrapper').addClass('d-none');
        }, 3000);
    }
});
