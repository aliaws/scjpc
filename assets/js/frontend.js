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
    jQuery('.pagination-bar li a').click(function(){
        var pageNumber = jQuery(this).data('page');
        jQuery(this).addClass("active");
        jQuery('#page_number').val(pageNumber);
        const action = jQuery('#action').val();
        jQuery(`#${action}`).submit();
    });
    jQuery('.page-list li a').click(function(){
        var pageNumber = jQuery(this).data('page');
        jQuery(this).addClass("active");
        jQuery('#per_page').val(pageNumber);
        const action = jQuery('#action').val();
        jQuery(`#${action}`).submit();
    });
    jQuery('input[value=all]').click(function (){
        if (!jQuery(this).prop('checked')) {
            jQuery('input[name="choices[]"]').prop('checked', false);
        }else{
            jQuery('input[name="choices[]"]').prop('checked', true);
        }
    })
});