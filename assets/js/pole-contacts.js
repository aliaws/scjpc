function scrollToElement(event, selector) {jQuery([document.documentElement, document.body]).animate({scrollTop: jQuery(`div#${selector}`).offset().top}, 500);}

jQuery(document).ready(function ($) {
  const $elementorWebSearch = $('#scjpc-web-search input');
  if ($elementorWebSearch.length > 0) {
    $elementorWebSearch.focus();
    $elementorWebSearch.click();
    $elementorWebSearch.addClass('elementor-search-form--focus');
    $elementorWebSearch.focusin();
  }
});
