jQuery(document).ready(function () {
    jQuery(".clearBtn").click(function(){
        var parentForm = jQuery(this).closest('form');
        parentForm.find('input[type="text"], input[type="file"]').val('');
        parentForm.find('input[type="checkbox"]').prop('checked', false);
    });
    const forms = jQuery('.needs-validation');

    // Loop over them and prevent submission
    forms.each(function() {
        jQuery(this).on('submit', function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            jQuery(this).addClass('was-validated');
        });
    });
});