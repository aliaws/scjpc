const admin_ajax_url = jQuery("#admin_ajax_url").val();

const toggleSearchHistoryNavigationButtons = () => {

  const $goBackBtn = jQuery('a#go-back');
  const $goForwardBtn = jQuery('a#go-forward');

  if ('navigation' in window) {
    if (!window.navigation.canGoBack) {
      $goBackBtn.hide();
    } else {
      $goBackBtn.show();
    }
    if (!window.navigation.canGoForward) {
      $goForwardBtn.hide();
    } else {
      $goForwardBtn.show();
    }
  } else {
    // Fallback for browsers without Navigation API
    if (window.history.length <= 1) {
      $goBackBtn.hide();
    } else {
      $goBackBtn.show();
    }
    // Forward detection fallback (unreliable, so always hide)
    $goForwardBtn.hide();
  }

};

jQuery(window).on('pageshow', function (event) {
  toggleSearchHistoryNavigationButtons();
  clearSearchInputFields();

});


jQuery(document).ready(function () {
  if (location.search !== '') {
    registerExportButtonCalls();
    registerPaginationButtonAndSortHeaderClicks();
  }
  jQuery(".clearBtn").click(function () {
    const parentForm = jQuery(this).closest('form');
    const selector = jQuery('form select option:nth-child(1)');
    parentForm.find('input[type="text"], input[type="file"]').val('');
    parentForm.find('input[type="checkbox"]').prop('checked', false);
    parentForm.find('input[id="page_number"]').val(1);
    parentForm.find('input[id="per_page"]').val(50);
    parentForm.find('input[id="last_id"]').val('');
    selector.attr('selected', 'selected');
    selector.removeAttr('selected');
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
  makeInputFieldsSimilar();
  toggleSearchHistoryNavigationButtons();
  clearSearchInputFields();

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
    if (value.value !== '') {
      const stored_previous_data = localStorage.getItem('previous_data') ? localStorage.getItem('previous_data') : "";
      if (stored_previous_data.length > 0) {
        // jQuery('div.response-table').html(stored_previous_data);
        registerExportButtonCalls();
        registerPaginationButtonAndSortHeaderClicks();
      }
      // form.submit()
    }
  });

}

const uploadSearchFileToS3 = async ( formData ) => {
  formData.set('action', 'upload_file_to_s3')
  formData.delete('s3_key')

  return jQuery.ajax( admin_ajax_url, {
    type: 'post',
    data: formData,
    processData: false,
    contentType: false,
    headers: { "Accept": "application/json" },
    success: ( response ) => {
      return response;
    },
    error: ( error ) => {
      return '';
    }
  } );
};

function registerFormSubmissionHandler(form) {
  jQuery(form).on('submit', async function (event) {
    event.preventDefault();
    const form = event.target;
    const formId = jQuery(this).attr('id');
    const formData = new FormData(form);

    if (!['jpa_detail_search', 'pole_detail'].includes(form.id)) {
      formData.delete('go_back');
    }

    const validate = validateForm(formId, formData);

    if (validate) {
      add_actions_change();

      // Convert FormData to URLSearchParams
      const params = new URLSearchParams();
      for (const [key, value] of formData.entries()) {
        params.append(key, value);
      }

      // Redirect to a new page with the query string

      if ( ['multiple_jpa_search', 'multiple_pole_search'].includes(formId) ) {
        const hasFile = Array.from(form.querySelectorAll('input[type="file"]')).some(input => input.files.length > 0);
        if ( hasFile ) {
          const s3_upload_response = await uploadSearchFileToS3( formData );
          if ( s3_upload_response && typeof s3_upload_response.data.s3_key !== 'undefined') {
            params.append('s3_key', s3_upload_response.data.s3_key);
          }
        } else {
          params.delete('uploaded_file')
        }
      }
      const targetUrl = form.getAttribute('action') || window.location.pathname;

      remove_actions_change();
      window.location.href = `${ targetUrl }?${ params.toString() }`;
    }

    clearSearchInputFields();
  });

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
    const pageNumber = event.currentTarget.dataset['page'];
    jQuery(this).addClass("active");

    const action = jQuery('#action').val();
    jQuery('input#page_number').val(pageNumber);
    jQuery('input#go_back').prop('disabled', 'disabled')

    jQuery(`#${action}`).submit();
  });
}
const registerPaginationLimitClicks = () => {
  jQuery('.page-list li a').click((event) => {
    jQuery('#response-overlay').addClass('response-overlay');
    const perPage = event.currentTarget.dataset['page'];
    jQuery(this).addClass("active");
    jQuery('input#per_page').val(perPage);
    jQuery('input#page_number').val(1);

    jQuery('input#go_back').prop('disabled', 'disabled')

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

    jQuery('input#go_back').prop('disabled', 'disabled')

    const form = jQuery('.needs-validation')[0];
    jQuery(`form#${form.id}`).submit()
  })
}

function registerExportButtonCalls() {
  ['export_as_excel', 'export_as_csv', 'restart'].forEach(button_key => {
    const button = jQuery(`button#${button_key}`);
    button.on('click', () => {
      const clicked_button = jQuery(`button#${button_key}`);
      clicked_button.prop('disabled', true);
      make_export_api_call(clicked_button)
    })
  })
  jQuery('button#print_window').on('click', () => {
    jQuery('div.response-table a').removeAttr('href');
    window.print();
  })
}


function make_export_api_call(button, execute_actions = true) {
  const body = button.data();
  body['action'] = 'make_export_data_call';
  add_actions_change(execute_actions);
  // console.log(body);
  jQuery.ajax({
    url: admin_ajax_url,
    type: 'post',
    data: body,
    dataType: 'json',
    success: function (response) {
      const {file_path, export_format} = response;
      redirect_to_download_export(file_path, export_format, execute_actions )
    },
    error: function (error) {
      remove_disabled_prop(execute_actions);
    }
  })
}

/**
 *  when it needed we can call
 **/
function trigger_exports_on_search() {
  ['export_as_excel', 'export_as_csv'].forEach(button_key => {
    const button = jQuery(`button#${button_key}`);
    if (button.length > 0) {
      make_export_api_call(button, false);
    }
  })
}

function redirect_to_download_export(file_path, export_format, execute_actions = true) {
  if (execute_actions) {
    window.location.href = `/download-export?file_path=${file_path}&format=${export_format}`;
    setTimeout(function () {
      remove_actions_change();
    }, 1000);
  }
}

function remove_disabled_prop(execute_actions = true) {
  if (execute_actions) {
    remove_actions_change();
    button.prop('disabled', false);
  }
}

function add_actions_change(execute = true) {
  if (execute) {
    jQuery('.custom-spinner-wrapper').removeClass('d-none');
    jQuery('.clearBtn, button[type=submit]').attr('disabled', 'disabled');
    jQuery('#response-overlay').addClass('response-overlay');
  }
}

function remove_actions_change() {
  jQuery('.custom-spinner-wrapper').addClass('d-none')
  jQuery('.clearBtn, button[type=submit]').removeAttr('disabled');
  jQuery('#response-overlay').removeClass('response-overlay');

}

function fetch_export_status() {
  jQuery.ajax({
    method: 'GET',
    url: admin_ajax_url,
    data: {
      action: 'get_export_status',
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

function validateForm(formId, formData) {
  let isValid = true, inputField, selectInput, fileS3Key, fileInput;
  switch (formId) {
    case 'jpa_search':
      inputField = jQuery('#jpa_number');
      if (inputField.val() === '') {
        isValid = false;
      }
      // return isValid;
      break;
    case 'multiple_pole_search':
      fileInput = jQuery('input[type="file"]');
      selectInput = jQuery('select');
      fileS3Key = jQuery('input#s3_key');
      isValid = false;
      if (fileInput.val() !== '' || selectInput.val() !== '' || fileS3Key.val() !== '') {
        isValid = true;
        jQuery('select, input[type="file"]').removeAttr('required');
      } else {
        jQuery('select, input[type="file"]').attr('required', 'required');
      }
      // return isValid;
      break;
    case 'quick_pole_search':
      inputField = jQuery('#pole_number');
      if (inputField.val() === '') {
        isValid = false;
      }
      // return isValid;
      break;
    case 'multiple_jpa_search':
      inputField = jQuery('input[type="file"]');
      fileS3Key = jQuery('input#s3_key');
      if (inputField.val() === '' && fileS3Key.val() === '') {
        isValid = false;
      }
      // return isValid;
      break;
    case 'advanced_pole_search' :
      const location = jQuery("#location");
      const latitude = jQuery("#id_latitude");
      const longitude = jQuery("#id_longitude");
      if (location.length > 0 && jQuery.trim(location.val()).length > 0) {
        if (jQuery.trim(location.val()).length < 2) {
          location.addClass('is-invalid input-danger-border')
          isValid = false;
        } else {
          location.removeClass('is-invalid input-danger-border')
          isValid = true;
        }
        formData.append('location_encoded', Base64.encode(jQuery.trim(location.val())));
      }
      if (jQuery.trim(latitude.val()).length > 0) {
        if (!validateLatitudeLongitude(latitude.val())) {
          latitude.addClass('is-invalid input-danger-border')
          isValid = false;
        } else {
          latitude.removeClass('is-invalid input-danger-border')
          isValid = true;
        }
      }
      if (jQuery.trim(longitude.val()).length > 0) {
        if (!validateLatitudeLongitude(longitude.val())) {
          longitude.addClass('is-invalid input-danger-border')
          isValid = false;
        } else {
          longitude.removeClass('is-invalid input-danger-border')
          isValid = true;
        }
      }

      // return isValid;
      break;
    default:
      isValid = true;
    // return isValid;
  }
  return isValid
}


const validateLatitudeLongitude = (input) => {
  const decimalPattern = /^-?\d+\.\d{3,}$/;
  return decimalPattern.test(input);
}
const clearSearchInputFields = () => {
  const elements = ['jpa_number_visible', 'pole_number_visible'];
  elements.forEach(element => {
    const visibleElement = jQuery(`#${element}`)
    visibleElement.focus({preventScroll: true});
    visibleElement.val('')
  })

}

const makeInputFieldsSimilar = () => {
  const elements = ['jpa_number', 'pole_number'];
  elements.forEach(element => {
    const hiddenElement = jQuery(`#${element}`)
    const visibleElement = jQuery(`#${element}_visible`)
    visibleElement.on('input', () => {
      hiddenElement.val(visibleElement.val())
    })
  })
}