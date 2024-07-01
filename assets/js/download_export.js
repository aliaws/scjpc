jQuery(document).ready(function () {
    fetch_export_status();
    registerExportButtonCalls();
    jQuery('button#download_export_file').on('click', () => {
        window.location.href = jQuery('input#download_url').val();
    })
})