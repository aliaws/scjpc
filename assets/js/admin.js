jQuery(document).ready(function () {
    jQuery('.js-example-basic-multiple').select2();

    jQuery("._school").change(function () {
        // Check if all checkboxes with class "_school" are checked
        var allChecked = jQuery("._school:checked").length === jQuery("._school").length;

        // Update the "Select All" checkbox based on the "allChecked" status
        jQuery("#selectAll").prop("checked", allChecked);
    });

    // When the "Select All" checkbox is clicked
    jQuery("#selectAll").click(function () {
        // Check all checkboxes with the class "_school"
        jQuery("._school").prop("checked", this.checked);
    });
    if (jQuery('.page-title-action').css('display') === 'none') {
        jQuery('.wrap .wp-heading-inline').html('Serial Number');
        jQuery('#titlewrap #title').prop('readonly', true);
    }
    jQuery(".copyImage").click(function () {
        var ariaLabel = jQuery(this).attr("aria-label");
        var textToCopy = jQuery("[aria-label='" + ariaLabel + "']");
        var range = document.createRange();
        range.selectNode(textToCopy[0]);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
    });

});


