const $priceTabs = jQuery('div.eael-tabs-nav li');
const $priceTabsContent = jQuery('div.eael-tabs-content div.eael-tab-content-item');
const $priceTabsContentParent = jQuery('div.eael-tabs-content');

function markAllTabsActive() {
  $priceTabs.removeClass('inactive').addClass('active');
  $priceTabsContent.removeClass('inactive').addClass('active');
}

function scrollToTop() {
  console.log('button clicked')
  jQuery("html, body").animate({scrollToTop: 0}, "slow");
}

$priceTabsContentParent.css("flex-direction", "column");
$priceTabs.on('click', markAllTabsActive);
$priceTabsContent.append('<button id="scjpc-back-to-top">Top</button>');
const $backToTopButton = jQuery('button#scjpc-back-to-top');
$backToTopButton.on('click', scrollToTop);