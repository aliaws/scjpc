const admin_ajax_url = jQuery("#admin_ajax_url").val();

jQuery(document).ready(function () {
  jQuery(".clearBtn").click(function () {
    const parentForm = jQuery(this).closest('form');
    parentForm.find('input[type="text"], input[type="file"]').val('');
    parentForm.find('input[type="checkbox"]').prop('checked', false);
    parentForm.find('input[id="page_number"]').val(1);
    parentForm.find('input[id="per_page"]').val(50);
    parentForm.find('input[id="last_id"]').val('');
  });
  const forms = jQuery('.needs-validation');
  // Loop over them and prevent submission
  forms.each(function () {
    jQuery(this).on('submit', function (event) {
      event.preventDefault()
      const form = new FormData(event.target)
      const formData = Object.fromEntries(form.entries())
      console.log('formData', formData)
      jQuery.ajax({
        url: `${admin_ajax_url}?action=${formData.action}`,
        type: 'post',
        processData: false,
        // dataType: "json",
        // contentType: "multipart/form-data",
        data: formData,
        success: function (response) {
          jQuery('div.response-table').html(response)
        },
        error: function (error) {
          console.log('error==', error)
        }
      })
      alert('form')
      // if (!this.checkValidity()) {
      //   event.preventDefault();
      //   event.stopPropagation();
      // }
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
  jQuery('button#print_window').on('click', () => {
    window.print()
  })
  jQuery('button#download_export_file').on('click', () => {
    window.location.href = jQuery('input#download_url').val();
  })
}


function make_export_api_call(body) {
  jQuery.ajax({
    url: admin_ajax_url + "?action=make_export_data_call",
    type: 'post',
    data: body,
    dataType: 'json',
    success: function (response) {
      const redirectURL = `/download-export?file_path=${response.file_path}&format=${response.export_format}`
      window.location.replace(redirectURL);
    },
    error: function (error) {
      console.log('error==', error)
    }
  })
}
