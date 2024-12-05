const registerExportJPAMembersClickEvent = () => {
  jQuery('a#export-jpa-members').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    jQuery.ajax({
      url: `${scjpc_ajax.ajax_url}`,
      type: 'GET',
      data: {
        action: 'scjpc_export_jpa_members',
        export_file_name: extractExportFileName()
      },
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
    jQuery.ajax({
      url: `${scjpc_ajax.ajax_url}`,
      type: 'GET',
      data: {
        action: 'scjpc_export_pole_members',
        export_file_name: extractExportFileName()
      },
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
const registerExportBuddyPoleMembersClickEvent = () => {
  jQuery('a#export-buddy-poles').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    jQuery.ajax({
      url: `${scjpc_ajax.ajax_url}`,
      type: 'GET',
      data: {
        action: 'scjpc_export_buddy_pole_members',
        export_file_name: extractExportFileName()
      },
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

const registerExportGraffitiRemovalMembersClickEvent = () => {
  jQuery('a#export-graffiti-removal-contacts').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    jQuery.ajax({
      url: `${scjpc_ajax.ajax_url}`,
      type: 'GET',
      data: {
        action: 'scjpc_export_graffiti_removal_members',
        export_file_name:  extractExportFileName()
      },
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
    jQuery.ajax({
      url: `${scjpc_ajax.ajax_url}`,
      type: 'GET',
      data: {
        action: 'scjpc_export_emergency_contacts',
        export_file_name:  extractExportFileName()
      },
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

const registerExportPoleMarkingsClickEvent = () => {
  jQuery('a#export-pole-cable-markings').on('click', (event) => {
    event.preventDefault();
    jQuery(this).prop('href', '');
    jQuery.ajax({
      url: `${scjpc_ajax.ajax_url}`,
      type: 'GET',
      data: {
        action: 'scjpc_export_pole_cable_markings',
        export_file_name: extractExportFileName()
      },
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
}

const registerExportFieldAssistanceClickEvent = () => {
  jQuery('a#export-field-assistance').on('click', (event) => {
    event.preventDefault()
    jQuery(this).prop('href', '');
    jQuery.ajax({
      url: `${scjpc_ajax.ajax_url}`,
      type: 'GET',
      data: {
        action: 'scjpc_export_field_assistance_contacts',
        export_file_name:  extractExportFileName()
      },
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

const registerRevealContactInformationEvent = () => {
  jQuery('.reveal-contact').on('click', function () {
    const $this = jQuery(this);
    const contactInfo = $this.data('contact');

    if ($this.text() === contactInfo) {
      const placeholder = `Click to Reveal ${contactInfo.includes('@') ? 'Email' : 'Phone Number'}`;
      $this.text(placeholder);
    } else {
      $this.text(contactInfo);
      navigator.clipboard.writeText(contactInfo).then(() => {
        showTooltip($this, 'Copied!');
      }).catch((err) => {
        console.error('Failed to copy contact information to clipboard', err);
      });
    }
  });
}

const showTooltip = ($element, message) => {
  $element.find('.tooltip').remove();
  const $tooltip = jQuery(`<span class='tooltip'>${message}</span>`);
  $element.append($tooltip);
  $tooltip.css({
    backgroundColor: '#333',
    color: '#fff',
    padding: '5px',
    borderRadius: '5px',
    marginLeft: '5px',
    whiteSpace: 'nowrap',
    position: 'relative',
    zIndex: 1000,
    display: 'inline-block',
  });

  $tooltip.fadeIn(300);
  setTimeout(() => {
    $tooltip.fadeOut(300, () => $tooltip.remove());
  }, 2000);
}

jQuery(document).ready(() => {
  registerExportJPAMembersClickEvent();
  registerExportJPAMemberClickEvent();
  registerExportPoleMembersClickEvent();
  registerExportEmergencyContactsClickEvent();
  registerExportBuddyPoleMembersClickEvent();
  registerExportGraffitiRemovalMembersClickEvent();
  registerExportFieldAssistanceClickEvent();
  registerExportPoleMarkingsClickEvent();
  registerRevealContactInformationEvent();
})

function extractExportFileName() {
  const ob = jQuery('.elementor-page-title .elementor-heading-title')[0]
  const pageHeading = jQuery(ob).text();
  return toSnakeCase(pageHeading);
}

/**
 * Converts a given string to snake case.
 * @param {string} str - The string to convert.
 * @returns {string} - The snake case version of the string.
 */
function toSnakeCase(str) {
  return str
      .toLowerCase() 
      .replace(/[\s\/–]+/g, '_')
      .replace(/[^\w_]/g, '');
}
