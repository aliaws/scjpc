const registerExportJPAMembersClickEvent = () => {
  jQuery('a#export-jpa-members').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    jQuery.ajax(`${scjpc_ajax.ajax_url}?action=scjpc_export_jpa_members`, {
      type: 'GET',
      success: (success) => {
        if (success.data !== "undefined" && typeof success.data.export_file_path !== "undefined") {
          window.location.href = success.data.export_file_path
        }
      },
      error: (error) => {
        console.log('error====', error)
      }
    })
  })
};

const registerExportPoleMembersClickEvent = () => {
  jQuery('a#export-pole-members').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    jQuery.ajax(`${scjpc_ajax.ajax_url}?action=scjpc_export_pole_members`, {
      type: 'GET',
      success: (success) => {
        if (success.data !== "undefined" && typeof success.data.export_file_path !== "undefined") {
          window.location.href = success.data.export_file_path
        }
      },
      error: (error) => {
        console.log('error====', error)
      }
    })
  })
};
const registerExportEmergencyContactsClickEvent = () => {
  jQuery('a#export-emergency-contacts').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    jQuery.ajax(`${scjpc_ajax.ajax_url}?action=scjpc_export_emergency_contacts`, {
      type: 'GET',
      success: (success) => {
        if (success.data !== "undefined" && typeof success.data.export_file_path !== "undefined") {
          window.location.href = success.data.export_file_path
        }
      },
      error: (error) => {
        console.log('error====', error)
      }
    })
  })
};

const registerExportJPAMemberClickEvent = () => {
  jQuery('a#export-jpa-member').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    const jpaContactId = jQuery(`div.scjpc-export-jpa-member`)[0].id
    jQuery.ajax(`${scjpc_ajax.ajax_url}?action=scjpc_export_jpa_members&jpa_contact_id=${jpaContactId}`, {
      type: 'GET',
      success: (success) => {
        if (success.data !== "undefined" && typeof success.data.export_file_path !== "undefined") {
          window.location.href = success.data.export_file_path
        }
      },
      error: (error) => {
        console.log('error====', error)
      }
    })
  })
};
jQuery(document).ready(() => {
  registerExportJPAMembersClickEvent()
  registerExportJPAMemberClickEvent()
  registerExportPoleMembersClickEvent()
  registerExportEmergencyContactsClickEvent()
})