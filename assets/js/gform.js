jQuery(document).ready(function () {
  registerMemberCodeInputChangeEvent();
  registerAddressChangeEvent();
})
const mailingAddressField = ['Full Name', 'Mailing Address', 'City, State, Zip Code', 'Telephone Number', 'Mobile Number (if applicable)', 'Fax Number (if applicable)', 'Email Address'];
const billingAddressField = ['Full Name', 'Billing Address', 'City, State, Zip Code', 'Telephone Number', 'Mobile Number (if applicable)', 'Fax Number (if applicable)', 'Email Address'];
const paperMailingField = ['Full Name', 'Mailing Address', 'City, State, Zip Code', 'Telephone Number', 'Mobile Number (if applicable)', 'Fax Number (if applicable)'];
const buddyPoleContactField = ['Full Name', 'Telephone Number(s)', 'Email Address'];
const nameField = ['Full Name', 'Email Address'];
const contactField = ['Telephone Number', 'Email Address'];


// Full Name-<br>
//   Mailing Address-<br>
//   City, State, Zip Code-<br>
//   Telephone Number-<br>
//   Mobile Number (if applicable)-<br>
//   Fax Number (if applicable)-<br>
//   Email Address

// Full Name-
// BILLING ADDRESS-
// City, State, Zip Code-
// Telephone Number-
// Mobile Number (if applicable)-
//   Fax Number (if applicable)-
//   Email Address

const registerAddressChangeEvent = () => {
  // const addressFields = ['textarea#input_1_8', 'textarea#input_1_9', 'textarea#input_1_10', 'textarea#input_1_18', 'textarea#input_1_37', 'textarea#input_1_22', 'textarea#input_1_23', 'textarea#input_1_26']
  const addressFields = {'8': '39', '9': '42', '10': '41', '18': '35', '22': '45', '23': '46', '26': '49', '37': '38'}
  jQuery.each(addressFields, (input, view) => {
    const addressField = jQuery(`textarea#input_1_${input}`);
    addressField.on('input', (event) => {
      const inputValueArr = event.target.value.split("\n");
      const viewField = jQuery(`div#field_1_${view}`);
      let viewHtml = '';
      mailingAddressField.forEach((value, index) => {
        viewHtml += `<strong>${value}</strong> - ${inputValueArr[index] ?? ''} <br>`
      })
      viewHtml += getUnusedValues(inputValueArr, mailingAddressField.length)
      viewField.html(viewHtml)
    })
  });


  const addressFields2 = {'11': '40'}
  jQuery.each(addressFields2, (input, view) => {
    const addressField = jQuery(`textarea#input_1_${input}`);
    addressField.on('input', (event) => {
      const inputValueArr = event.target.value.split("\n");
      const viewField = jQuery(`div#field_1_${view}`);
      let viewHtml = '';
      billingAddressField.forEach((value, index) => {
        viewHtml += `<strong>${value}</strong> - ${inputValueArr[index] ?? ''} <br>`
      })
      viewHtml += getUnusedValues(inputValueArr, billingAddressField.length)
      viewField.html(viewHtml)
    })
  });

  const nameFieldIds = {'12': '43'}
  jQuery.each(nameFieldIds, (input, view) => {
    const addressField = jQuery(`textarea#input_1_${input}`);
    addressField.on('input', (event) => {
      const inputValueArr = event.target.value.split("\n");
      const viewField = jQuery(`div#field_1_${view}`);
      let viewHtml = '';
      nameField.forEach((value, index) => {
        viewHtml += `<strong>${value}</strong> - ${inputValueArr[index] ?? ''} <br>`
      })
      viewHtml += getUnusedValues(inputValueArr, nameField.length)
      viewField.html(viewHtml)
    })
  });


  const contactFieldIds = {'51': '47'}
  jQuery.each(contactFieldIds, (input, view) => {
    const addressField = jQuery(`textarea#input_1_${input}`);
    addressField.on('input', (event) => {
      const inputValueArr = event.target.value.split("\n");
      const viewField = jQuery(`div#field_1_${view}`);
      let viewHtml = '';
      contactField.forEach((value, index) => {
        viewHtml += `<strong>${value}</strong> - ${inputValueArr[index] ?? ''} <br>`
      })
      viewHtml += getUnusedValues(inputValueArr, contactField.length)
      viewField.html(viewHtml)
    })
  });

  const paperMailing = {'17': '44'}
  jQuery.each(paperMailing, (input, view) => {
    const addressField = jQuery(`textarea#input_1_${input}`);
    addressField.on('input', (event) => {
      const inputValueArr = event.target.value.split("\n");
      const viewField = jQuery(`div#field_1_${view}`);
      let viewHtml = '';
      paperMailingField.forEach((value, index) => {
        viewHtml += `<strong>${value}</strong> - ${inputValueArr[index] ?? ''} <br>`
      })
      viewHtml += getUnusedValues(inputValueArr, paperMailingField.length)
      viewField.html(viewHtml)
    })
  });

  const buddyPoleFields = {'28': '48', '29': '50'}
  jQuery.each(buddyPoleFields, (input, view) => {
    const addressField = jQuery(`textarea#input_1_${input}`);
    addressField.on('input', (event) => {
      const inputValueArr = event.target.value.split("\n");
      const viewField = jQuery(`div#field_1_${view}`);
      let viewHtml = '';
      buddyPoleContactField.forEach((value, index) => {
        viewHtml += `<strong>${value}</strong> - ${inputValueArr[index] ?? ''} <br>`
      })
      viewHtml += getUnusedValues(inputValueArr, buddyPoleContactField.length)
      viewField.html(viewHtml)
    })
  });

}


const getUnusedValues = (arr, lastIndexUsed) => {
  if (arr.length <= lastIndexUsed) {return '';}
  const unusedValues = arr.slice(lastIndexUsed);
  return unusedValues.join('<br>');
}
const checkMemberCodeExistence = (memberCodeValue) => {
  return jQuery.ajax(scjpc_ajax.ajax_url, {
    type: 'post',
    data: {member_code: memberCodeValue, action: 'scjpc_validate_member_code'},
    success: (response) => {
      return response.data;
    },
    error: (error) => {
      console.log('error======', error);
      return {error: true, code_exists: false};
    }
  });
};
const makeMemberCodeValidation = async (memberCode, memberCodeValue, requestTypeValue) => {
  jQuery(`span.member_code_validation`).remove()
  const ajaxResponse = await checkMemberCodeExistence(memberCodeValue)
  if (ajaxResponse.success) {
    let errorText = '', error = false;
    if (requestTypeValue === 'new_member' && ajaxResponse.data.code_exists === true) {
      jQuery(`span.member_code_validation`).remove()
      errorText = 'Member code exists! Please enter a different code';
      error = true;
    } else if (requestTypeValue === 'update_member' && ajaxResponse.data.code_exists === false) {
      jQuery(`span.member_code_validation`).remove()
      errorText = "Member doesn't exist! Please enter a correct code";
      error = true;
    }
    if (error) {
      const statusDiv = jQuery('<span>', {
        id: 'member_code_validation',
        class: 'gfield_description member_code_validation',
        text: errorText,
        style: 'color: red'
      });
      jQuery(`span.member_code_validation`).remove()
      memberCode.after(statusDiv);
      memberCode.focus()
      jQuery('input#gform_submit_button_1').attr('disabled', 'disabled')
    } else {
      jQuery('input#gform_submit_button_1').removeAttr('disabled')
    }

  }
}
const registerMemberCodeInputChangeEvent = () => {
  let timeout;
  const memberCode = jQuery(`#input_1_6`);
  memberCode.on('input', (event) => {
    const memberCodeValue = event.target.value;
    const requestTypeValue = jQuery('input[name="input_31"]:checked').val()
    if (memberCodeValue !== '') {
      clearTimeout(timeout)
      timeout = setTimeout(async () => {
        await makeMemberCodeValidation(memberCode, memberCodeValue, requestTypeValue)
      }, 1000)
    }
  });

  const requestType = jQuery('input[name="input_31"]');
  requestType.on('change', (event) => {
    const memberCodeValue = memberCode.val();
    const requestTypeValue = event.target.value
    if (memberCodeValue !== '') {
      clearTimeout(timeout)
      timeout = setTimeout(async () => {
        await makeMemberCodeValidation(memberCode, memberCodeValue, requestTypeValue)
      }, 1000)
    }
  })
}


