jQuery(document).ready(function () {
    [
        'clear-export', 'clear-pdf', 'clear-redis', 're-index',
        'es_settings', 'remove-deleted-data', 'clean-es-orphans',
        'remove_all_processed_exports', 'remove_pending_exports', 'remove_processing_exports'
    ].forEach(button => {
        jQuery(`button#${button}`).on('click', () => {
            if (confirm('Are you sure you want to do this ?')) {
                const clear_cache_button = jQuery(`button#${button}`);
                clear_cache_button.prop('disabled', true);
                executeClearCache(clear_cache_button)
            }

        })
    })
})

function showSuccessNotification(message) {
    console.log("message", message);
    jQuery('.custom-alert').text(message.replace(/"/g, ''));
    jQuery('.custom-alert-wrapper').removeClass('d-none');

    setTimeout(function () {
        jQuery('.custom-alert-wrapper').addClass('d-none');
    }, 3000);
}


function executeClearCache(button) {
    const body = button.data();
    body['action'] = 'flush_cache';
    console.log(body);
    jQuery.ajax({
        url: ajaxurl,
        method: 'post',
        data: body,
        success: function (response) {
            button.removeAttr('disabled');
            showSuccessNotification(response);
        },
        error: function (error) {
            console.log('error==', error);
        }
    });
}

function fetch_es_status() {
    jQuery.ajax({
      method: 'GET',
      url: admin_ajax_url,
      data: {
        action: 'get_es_status',
        file_path: jQuery("#file_path").val(),
        format: jQuery("#format").val(),
      },
      success: function (response) {
        if (response.success) {
  
          const {data} = response;
          const {export_progress, btn_icon, no_of_seconds_interval, btn_disabled, status, download_url} = data;
          jQuery('#status').val(status);
          const download_btn = jQuery('.download_btn');
          download_btn.html(btn_icon);
          jQuery('#download_url').val(download_url);
          jQuery('.export_progress_bar').attr('value', export_progress);
          jQuery('.export_progress_text').text(Math.ceil(export_progress));
  
          if (!btn_disabled) {
            download_btn.removeAttr('disabled');
          }
          //Recursion
          if (status !== 'Processed') {
            setTimeout(() => { fetch_export_status() }, no_of_seconds_interval * 1000)
          }
  
        } else {
          window.location.reload();
        }
      },
      error: function (xhr, status, error) {
        window.location.reload();
      }
    });
  }