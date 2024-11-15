jQuery(document).ready(function ($) {
  const $priceTabs = $('div.eael-tabs-nav li');
  const $priceTabsContent = $('div.eael-tabs-content div.eael-tab-content-item');
  const $priceTabsContentParent = $('div.eael-tabs-content');

  // Function to scroll to top when the button is clicked
  function scrollToTop() {
    console.log('button clicked');
    $("html, body").animate({scrollTop: 0}, "fast"); // Correct scrollTop property
  }

  // Adjust the flex direction of the tab container
  $priceTabsContentParent.css("flex-direction", "column");

  // Handle tab clicks
  $priceTabs.on('click', function (e) {
    e.preventDefault(); // Prevent default tab switching behavior
    $("html, body").animate({scrollTop: $(`div#${$(this).attr('id')}-tab`).offset().top}, "slow");
  });

  // Append 'Back to Top' button inside tab content
  $priceTabsContent.append('<div style="padding-bottom: 15px;"><button id="scjpc-back-to-top">Top</button></div>');

  // Set up the event listener for the 'Back to Top' button
  const $backToTopButton = $('button#scjpc-back-to-top');
  $backToTopButton.on('click', scrollToTop);
});
