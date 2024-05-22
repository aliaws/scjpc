const admin_ajax_url = jQuery("#admin_ajax_url").val();

jQuery(document).ready(function () {
  console.log('frontend.js loaded')
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
  console.log('registering the form submission handlers')
  registerFormSubmissionHandler();


  // register_export_button_calls()
});

const registerFormSubmissionHandler = () => {
  const form = jQuery('.needs-validation')[0];
  // console.log('forms to apply check', forms[0].id)
  // Loop over them and prevent submission

  jQuery(form).on('submit', function (event) {
    console.log('i am here')
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
        registerPaginationButtonAndSortHeaderClicks();
      },
      error: (error) => {
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
  jQuery('input[value=all]').click(() => {
    if (!jQuery(this).prop('checked')) {
      jQuery('input[name="choices[]"]').prop('checked', false);
    } else {
      jQuery('input[name="choices[]"]').prop('checked', true);
    }
  })
  // Register Header
  registerTableSortClicks()
}
const registerPageNavigationClicks = () => {
  jQuery('.pagination-bar li a').click((event) => {
    console.log('pageNumber', event, jQuery(this))
    console.log('pageNumber', event.currentTarget.dataset, event.currentTarget.dataset['page'])
    // const pageNumber = jQuery(this).data('page');
    const pageNumber = event.currentTarget.dataset['page'];
    jQuery(this).addClass("active");

    const action = jQuery('#action').val();
    jQuery('input#page_number').val(pageNumber);
    jQuery(`#${action}`).submit();
  });
}
const registerPaginationLimitClicks = () => {
  jQuery('.page-list li a').click((event) => {
    // const perPage = jQuery(this).data('page');
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
    // const sort_order = jQuery(this).data('sort-order');
    // const sort_key = jQuery(this).data('sort-key');
    // console.log('click event', event)
    const sortOrder = event.currentTarget.dataset['sortOrder'];
    jQuery('input#sort_order').val(sortOrder);

    const sortKey = event.currentTarget.dataset['sortKey'];
    jQuery('input#sort_key').val(sortKey)
    // const sortKeyInput = jQuery('input#sort_key')
    // const sortOrderInput = jQuery('input#sort_order');
    // console.log('sortOrder', sortOrder, 'sort_key', sortKey, 'sortKeyInput', sortKeyInput.val(), 'sortOrderInput', sortOrderInput.val())
    // if (sortKeyInput === sortKey) {
    //   const val = sortOrder === 'asc' ? 'desc' : 'asc'
    //   sortOrderInput.val(val)
    // } else {
    //   sortOrderInput.val(sortOrder)
    //
    // }
    // sortOrderInput.val(sortOrder)
    // sortKeyInput.val(sortKey)
    const form = jQuery('.needs-validation')[0];
    jQuery(`form#${form.id}`).submit()
  })
}

function registerExportButtonCalls() {
  ['export_as_excel', 'export_as_csv'].forEach(button => {
    console.log('button ', button)
    jQuery(`button#${button}`).on('click', () => {
      console.log('button clicked');
      const export_button = jQuery(`button#${button}`);
      export_button.prop('disabled', true);
      make_export_api_call(export_button)
    })
  })
  jQuery('button#print_window').on('click', () => {
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
