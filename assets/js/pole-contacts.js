function scrollToElement(event, selector) {jQuery([document.documentElement, document.body]).animate({scrollTop: jQuery(`div#${selector}`).offset().top}, 500);}

jQuery(document).ready(function ($) {
  const $elementorWebSearch = $('#scjpc-web-search input');
  if ($elementorWebSearch.length > 0) {
    $elementorWebSearch.focus();
    $elementorWebSearch.click();
    $elementorWebSearch.addClass('elementor-search-form--focus');
    $elementorWebSearch.focusin();
  }

  const $loginForm = $('div.um-login form');
  const $loginFormCheckbox = $('div.um-login .um-field-checkbox-option');
  if ($loginFormCheckbox.length > 0 && $loginForm.length > 0) {
    $loginFormCheckbox.text('Remember me');

    $loginForm.append("<p><span>Contact your representative for username and password.</span></p>")
  }
});
