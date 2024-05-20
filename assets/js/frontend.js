const admin_ajax_url = jQuery("#admin_ajax_url").val();

jQuery(document).ready(function () {
  jQuery(".clearBtn").click(function () {
    const parentForm = jQuery(this).closest('form');
    parentForm.find('input[type="text"], input[type="file"]').val('');
    parentForm.find('input[type="checkbox"]').prop('checked', false);
  });
  const forms = jQuery('.needs-validation');
  // Loop over them and prevent submission
  forms.each(function () {
    jQuery(this).on('submit', function (event) {
      if (!this.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      jQuery(this).addClass('was-validated');
    });
  });
  jQuery('.pagination-bar li a').click(function () {
    const pageNumber = jQuery(this).data('page');
    jQuery(this).addClass("active");
    jQuery('#page_number').val(pageNumber);
    const action = jQuery('#action').val();
    jQuery(`#${action}`).submit();
  });
  jQuery('.page-list li a').click(function () {
    const pageNumber = jQuery(this).data('page');
    jQuery(this).addClass("active");
    jQuery('#per_page').val(pageNumber);
    const action = jQuery('#action').val();
    jQuery(`#${action}`).submit();
  });
  jQuery('input[value=all]').click(function () {
    if (!jQuery(this).prop('checked')) {
      jQuery('input[name="choices[]"]').prop('checked', false);
    } else {
      jQuery('input[name="choices[]"]').prop('checked', true);
    }
  })
  register_export_button_calls()
});

function register_export_button_calls() {
  ['export_as_excel', 'export_as_csv'].forEach(button => {
    jQuery(`button#${button}`).on('click', () => {
      make_export_api_call(jQuery(`button#${button}`).data())
    })
  })
}


function make_export_api_call(body) {
  jQuery.ajax({
    url: admin_ajax_url + "?action=make_export_data_call",
    type: 'post',
    data: body,
    dataType: 'json',
    success: function (response) {
      const redirectURL = `${window.location.host}/download-export?file=${response.file_path}`
      window.location.replace(redirectURL);
    },
    error: function (error) {
      console.log('error==', error)
    }
  })
}