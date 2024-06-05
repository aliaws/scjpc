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

  jQuery('input#id_choices_all').click((event) => {
    if (!jQuery('input#id_choices_all').prop('checked')) {
      jQuery('input[name="choices[]"]').prop('checked', false);
    } else {
      jQuery('input[name="choices[]"]').prop('checked', true);
    }
  })

  jQuery('button[type=submit]').on('click', () => {
    jQuery('input[id=page_number]').val(1);
  })
  registerSearchFormSubmissionHandler();
});
const registerSearchFormSubmissionHandler = () => {
  const form = jQuery('.needs-validation')[0];
  if (form !== undefined) {
    registerFormSubmissionHandler(form)
    submitFormIfNotEmpty(form)
  }
}

const submitFormIfNotEmpty = (form) => {
  jQuery('input[type="text"]').each((index, value) => {
    console.log(jQuery(this), index, value.value, value.value !== '')
    if (value.value !== '') {
      console.log('submitting form...')
      form.submit()
    }
  });

}

function registerFormSubmissionHandler(form) {
  jQuery(form).on('submit', function (event) {
    jQuery('.custom-spinner-wrapper').removeClass('d-none');
    jQuery('.clearBtn, button[type=submit]').attr('disabled', 'disabled');
    jQuery('#response-overlay').addClass('response-overlay');
    event.preventDefault()
    const form = event.target;
    const formData = new FormData(form);
    jQuery.ajax(admin_ajax_url, {
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      headers: {"Accept": "application/json"},
      success: (response) => {
        jQuery('.custom-spinner-wrapper').addClass('d-none')
        jQuery('.clearBtn, button[type=submit]').removeAttr('disabled');
        jQuery('#response-overlay').removeClass('response-overlay');
        jQuery('div.response-table').html(response);
        registerExportButtonCalls();
        registerPaginationButtonAndSortHeaderClicks();
      },
      error: (error) => {
        jQuery('.custom-spinner-wrapper').addClass('d-none')
        jQuery('.clearBtn, button[type=submit]').removeAttr('disabled');
        jQuery('#response-overlay').removeClass('response-overlay');

        console.log('error==', error);
      }
    })
    jQuery(this).addClass('was-validated');
  });

  //submit in case in jpa detail
  if (['jpa_detail_search', 'pole_detail'].includes(form.id)) {
    jQuery(`form#${form.id}`).submit()
  }
}

const registerPaginationButtonAndSortHeaderClicks = () => {
  //Register Pagination events
  registerPageNavigationClicks()
  // Register Per Page Event
  registerPaginationLimitClicks()

  // Register Header
  registerTableSortClicks()
}
const registerPageNavigationClicks = () => {
  jQuery('.pagination-bar li a').click((event) => {
    jQuery('#response-overlay').addClass('response-overlay');
    scroll_to_top();
    console.log('pageNumber', event, jQuery(this))
    console.log('pageNumber', event.currentTarget.dataset, event.currentTarget.dataset['page'])
    const pageNumber = event.currentTarget.dataset['page'];
    jQuery(this).addClass("active");

    const action = jQuery('#action').val();
    jQuery('input#page_number').val(pageNumber);
    jQuery(`#${action}`).submit();
  });
}
const registerPaginationLimitClicks = () => {
  jQuery('.page-list li a').click((event) => {
    jQuery('#response-overlay').addClass('response-overlay');
    scroll_to_top();
    const perPage = event.currentTarget.dataset['page'];
    jQuery(this).addClass("active");
    jQuery('input#per_page').val(perPage);
    jQuery('input#page_number').val(1);
    const action = jQuery('#action').val();
    jQuery(`#${action}`).submit();
  });
}
const registerTableSortClicks = () => {
  jQuery('table.table-sortable th.has_sort').click((event) => {
    const sortOrder = event.currentTarget.dataset['sortOrder'];
    jQuery('input#sort_order').val(sortOrder);

    const sortKey = event.currentTarget.dataset['sortKey'];
    jQuery('input#sort_key').val(sortKey)

    const form = jQuery('.needs-validation')[0];
    jQuery(`form#${form.id}`).submit()
  })
}

function registerExportButtonCalls() {
  ['export_as_excel', 'export_as_csv'].forEach(button => {
    jQuery(`button#${button}`).on('click', () => {
      const export_button = jQuery(`button#${button}`);
      export_button.prop('disabled', true);
      make_export_api_call(export_button)
    })
  })
  jQuery('button#print_window').on('click', () => {
    jQuery('a').removeAttr('href');
    window.print();
  })
}


function make_export_api_call(button) {
  const body = button.data();
  body['action'] = 'make_export_data_call';
  jQuery.ajax({
    url: admin_ajax_url,
    type: 'post',
    data: body,
    dataType: 'json',
    success: function (response) {
      const {file_path, export_format} = response;
      window.location.href = `/download-export?file_path=${file_path}&format=${export_format}`;
    },
    error: function (error) {
      console.log('error==', error)
      button.prop('disabled', false);
    }
  })
}

function scroll_to_top(){
  jQuery('html, body').animate({
    scrollTop: jQuery(".custom-spinner-wrapper-up").offset().top
  }, 100);
}
