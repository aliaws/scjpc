jQuery(document).ready(function ($) {
  $('div.e-tabs-items div').on('click', function (event) {
    const $contentTabVideo = document.querySelector(`#${$(this).attr('aria-controls')} video`);
    $contentTabVideo.addEventListener('canplay', function () {
      $contentTabVideo.pause();
    })
  })
})