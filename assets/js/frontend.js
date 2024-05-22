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
  jQuery('button[type=submit]').on('click', () => {
    jQuery('input[id=page_number]').val(1);
  })
  const forms = jQuery('.needs-validation');
  // Loop over them and prevent submission
  forms.each(() => {
    jQuery(this).on('submit', function (event) {
      event.preventDefault()
      const form = event.target;
      const formData = new FormData(form);
      // jQuery.ajax(`${admin_ajax_url}?action=${formData.get('action')}`, {
      jQuery.ajax(admin_ajax_url, {
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        headers: {"Accept": "application/json"},
        success: (response) => {
          jQuery('div.response-table').html(response);
          registerExportButtonCalls();
          registerPaginationButtonClicks();
        },
        error: (error) => {
          console.log('error==', error);
        }
      })
      // alert('form')
      // if (!this.checkValidity()) {
      //   event.preventDefault();
      //   event.stopPropagation();
      // }
      jQuery(this).addClass('was-validated');
    });
  });

  // register_export_button_calls()
});
const registerPaginationButtonClicks = () => {
  jQuery('.pagination-bar li a').click(function () {
    const pageNumber = jQuery(this).data('page');
    jQuery(this).addClass("active");
    jQuery('input#page_number').val(pageNumber);
    const action = jQuery('#action').val();
    jQuery(`#${action}`).submit();
  });
  jQuery('.page-list li a').click(function () {
    const pageNumber = jQuery(this).data('page');
    jQuery(this).addClass("active");
    jQuery('input#per_page').val(pageNumber);
    jQuery('input#page_number').val(1);
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
}

function registerExportButtonCalls() {
  ['export_as_excel', 'export_as_csv'].forEach(button => {
    console.log('button ', button)
    jQuery(`button#${button}`).on('click', () => {
      console.log('button clicked')
      make_export_api_call(jQuery(`button#${button}`).data())
    })
  })
  jQuery('button#print_window').on('click', () => {
    window.print()
  })
}


function make_export_api_call(body) {
  body['action'] = 'make_export_data_call'
  jQuery.ajax({
    url: admin_ajax_url,
    type: 'post',
    data: body,
    dataType: 'json',
    success: function (response) {
      window.location.href = `/download-export?file_path=${response.file_path}&format=${response.export_format}`;
    },
    error: function (error) {
      console.log('error==', error)
    }
  })
}
