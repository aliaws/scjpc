var Base64 = {
  _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
    let t = "", n, r, i, s, o, u, a, f = 0;
    e = Base64._utf8_encode(e);
    while (f < e.length) {
      n = e.charCodeAt(f++);
      r = e.charCodeAt(f++);
      i = e.charCodeAt(f++);
      s = n >> 2;
      o = (n & 3) << 4 | r >> 4;
      u = (r & 15) << 2 | i >> 6;
      a = i & 63;
      if (isNaN(r)) {u = a = 64} else if (isNaN(i)) {a = 64}
      t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
    }
    return t
  }, decode: function (e) {
    let t = "", n, r, i, s, o, u, a, f = 0;
    e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
    while (f < e.length) {
      s = this._keyStr.indexOf(e.charAt(f++));
      o = this._keyStr.indexOf(e.charAt(f++));
      u = this._keyStr.indexOf(e.charAt(f++));
      a = this._keyStr.indexOf(e.charAt(f++));
      n = s << 2 | o >> 4;
      r = (o & 15) << 4 | u >> 2;
      i = (u & 3) << 6 | a;
      t = t + String.fromCharCode(n);
      if (u != 64) {t = t + String.fromCharCode(r)}
      if (a != 64) {t = t + String.fromCharCode(i)}
    }
    t = Base64._utf8_decode(t);
    return t
  }, _utf8_encode: function (e) {
    e = e.replace(/\r\n/g, "\n");
    let t = "";
    for (let n = 0; n < e.length; n++) {
      let r = e.charCodeAt(n);
      if (r < 128) {t += String.fromCharCode(r)} else if (r > 127 && r < 2048) {
        t += String.fromCharCode(r >> 6 | 192);
        t += String.fromCharCode(r & 63 | 128)
      } else {
        t += String.fromCharCode(r >> 12 | 224);
        t += String.fromCharCode(r >> 6 & 63 | 128);
        t += String.fromCharCode(r & 63 | 128)
      }
    }
    return t
  }, _utf8_decode: function (e) {
    let t = "", n = 0, r = 0, c1 = 0, c2 = 0, c3 = 0;
    while (n < e.length) {
      r = e.charCodeAt(n);
      if (r < 128) {
        t += String.fromCharCode(r);
        n++
      } else if (r > 191 && r < 224) {
        c2 = e.charCodeAt(n + 1);
        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
        n += 2
      } else {
        c2 = e.charCodeAt(n + 1);
        c3 = e.charCodeAt(n + 2);
        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
        n += 3
      }
    }
    return t
  }
}

const admin_ajax_url = jQuery("#admin_ajax_url").val();

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
  makeInputFieldsSimilar()
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
        jQuery('div.response-table').html(stored_previous_data);
        registerExportButtonCalls();
        registerPaginationButtonAndSortHeaderClicks();
      }
      // form.submit()
    }
  });

}

function registerFormSubmissionHandler(form) {
  jQuery(form).on('submit', function (event) {


    event.preventDefault()
    const form = event.target;
    const formId = jQuery(this).attr('id');
    const formData = new FormData(form);
    const validate = validateForm(formId, formData);


    if (validate) {
      add_actions_change();
      jQuery.ajax(admin_ajax_url, {
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        headers: {"Accept": "application/json"},
        success: (response) => {
          remove_actions_change();
          if (!['jpa_detail_search', 'pole_detail'].includes(form.id)) {
            localStorage.removeItem('previous_data');
            localStorage.setItem('previous_data', response);
          }
          jQuery('div.response-table').html(response);
          registerExportButtonCalls();
          registerPaginationButtonAndSortHeaderClicks();
          trigger_exports_on_search();
        },
        error: (error) => {
          remove_actions_change();
        }
      });
      clearSearchInputFields();
    }
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
  ['export_as_excel', 'export_as_csv', 'restart'].forEach(button_key => {
    const button = jQuery(`button#${button_key}`);
    button.on('click', () => {
      const clicked_button = jQuery(`button#${button_key}`);
      clicked_button.prop('disabled', true);
      make_export_api_call(clicked_button)
    })
  })
  jQuery('button#print_window').on('click', () => {
    jQuery('a').removeAttr('href');
    window.print();
  })
}


function make_export_api_call(button, execute_actions = true) {
  const body = button.data();
  body['action'] = 'make_export_data_call';
  add_actions_change(execute_actions);
  jQuery.ajax({
    url: admin_ajax_url,
    type: 'post',
    data: body,
    dataType: 'json',
    success: function (response) {
      const {file_path, export_format} = response;
      redirect_to_download_export(file_path, export_format, execute_actions)
    },
    error: function (error) {
      remove_disabled_prop(execute_actions);
    }
  })
}

function trigger_exports_on_search() {
  ['export_as_excel', 'export_as_csv'].forEach(button_key => {
    const button = jQuery(`button#${button_key}`);
    if (button.length >0) {
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
      if (jQuery.trim(latitude.val()).length > 0 ) {
        if(!validateLatitudeLongitude(latitude.val())) {
          latitude.addClass('is-invalid input-danger-border')
          isValid = false;
        }
        else {
          latitude.removeClass('is-invalid input-danger-border')
          isValid = true;
        }
      }
      if (jQuery.trim(longitude.val()).length > 0 ) {
        if(!validateLatitudeLongitude(longitude.val())) {
          longitude.addClass('is-invalid input-danger-border')
          isValid = false;
        }
        else {
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