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

jQuery(document).ready(() => {
  fetchEsProgress();
});

const fetchEsProgress = () => {
  jQuery.ajax({
    method: 'GET',
    url: ajaxurl,
    data: { action: 'progress' },
    success: ({ success, data }) => {
      if (success && typeof data.progress !== 'undefined') {
        updateEsProgress(data.progress);
      } else {
        console.error('Progress response invalid');
      }
    },
    error: () => {
      console.error('AJAX request failed');
    }
  });
};

const updateEsProgress = (progress) => {
  if (progress > 0) {
    jQuery('.es_progress_bar').attr('value', progress);
    jQuery('.es_progress_text').text(progress);
    jQuery('#custom_progress').closest('.progress-wrapper').show();
  } else {
    jQuery('#custom_progress').closest('.progress-wrapper').hide();
  }
};
