// go back to the previous page when the back button is clicked
function goBack() {
  history.back()
}

$('.datepicker').datepicker();

//show and then hide alert notification
$(document).ready(function(){
  $(".notif").hide(0).delay(300).fadeIn(600).delay(2000).fadeOut(300);

  $('.sub-nav2').delay(300).animate({ top: '31' }, 400, 'swing');
});