//Opening Paypal donate buttons in new tabs

$(function(){
  $('[action*="paypal"]').attr('target', "_blank");
});
