function toggleTableVisibility(event, selector) {
  let current_table = jQuery(`div#${selector}`)
  if (current_table.hasClass('display-none')) {
    current_table.removeClass('display-none')
  } else {
    current_table.addClass('display-none')
  }
}