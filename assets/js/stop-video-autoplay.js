document.addEventListener('DOMContentLoaded', function () {
  // Select all the div elements inside div.e-tabs-items
  const tabItems = document.querySelectorAll('div.e-tabs-items div');

  tabItems.forEach(function (tab) {
    tab.addEventListener('click', function (event) {
      event.preventDefault();

      // Use setTimeout to delay the execution
      setTimeout(function () {
        // Get the value of the aria-controls attribute
        const ariaControls = tab.getAttribute('aria-controls');
        const $contentTabVideo = document.querySelector(`#${ariaControls} video`);

        // Check if the video element exists
        if ($contentTabVideo) {
          // Add the canplay event listener to the video element
          $contentTabVideo.addEventListener('canplay', function () {
            $contentTabVideo.pause();
          });
        }
      }, 500);
    });
  });
});
