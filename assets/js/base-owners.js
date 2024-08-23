jQuery(document).ready(function ($) {
  $('.toggle-status').click(function () {
    const button = $(this);
    const baseOwnerCode = button.data('code');
    $.ajax({
      url: scjpc_ajax.ajax_url,
      type: 'POST',
      data: {
        action: 'scjpc_toggle_status',
        base_owner_code: baseOwnerCode
      },
      success: function (response) {
        if (response.success) {
          const newStatus = response.data.new_status;
          const buttonText = newStatus === 'active' ? 'Active' : 'Inactive';
          const buttonColor = newStatus === 'active' ? 'green' : 'red';
          button.text(buttonText).css({'background-color': buttonColor});
        } else {
          console.log('Failed to update status');
        }
      }
    });
  });
});
