jQuery(document).ready(function () {
    jQuery(".clearBtn").click(function(){
        var parentForm = jQuery(this).closest('form');
        parentForm.find('input[type="text"], input[type="file"]').val('');
        parentForm.find('input[type="checkbox"]').prop('checked', false);
    });
});