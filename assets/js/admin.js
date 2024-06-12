jQuery(document).ready(function () {
    const jpaModal = jQuery('#jpaModal'); // Use jQuery to get the modal element
    jpaModal.on('show.bs.modal', function (e) {
        // Button that triggered the modal
        const button = jQuery(e.relatedTarget); // Wrap button in jQuery
        const unique_id = button.data('edit-id');
        const jpa_number = button.data('edit-jpa-number');
        const pdf_path = button.data('edit-pdf');
        const path = new URL(pdf_path).pathname;
        const custom_s3_key = (path !== "/") ? path.slice(1) : `pdf/revised/${jpa_number}${unique_id}.pdf`;
        jQuery('.edit_unique_id').text(unique_id);
        jQuery('.edit_jpa_number').text(jpa_number);
        jQuery('#form_unique_id').attr('value', unique_id);
        jQuery('#form_jpa_number').attr('value', jpa_number);
        jQuery('#update_file').attr('value', pdf_path);
        jQuery('#s3_key').attr('value', custom_s3_key);

    });
    jQuery('#update_jpa_search').on('submit', function (event) {
        event.preventDefault()
        jQuery('#update_submit').text('Updating').attr('disabled', true);
        const form = event.target;
        const formData = new FormData(form);
        jQuery(this).find('input').each(function () {
            var inputName = jQuery(this).attr('name');
            var inputValue = jQuery(this).val();
            formData[inputName] = inputValue;
        });
        console.log(formData);
        jQuery.ajax({
            url: admin_ajax_url,
            method: 'post', // Use 'type' instead of 'method'
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
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
});
