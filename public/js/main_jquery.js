$(document).ready(function() 
{
	$('.click-activity').click(function(event) {
		$(this).hide();
		var activityText = $(this).closest('.activity').find('.activity-text');
		activityText.show().focus();
		activityText.val(activityText.val());
	});
	$('.activity-text').keyup(function(event) {
		var clickActivity = $(this).closest('.activity').find('.click-activity');
		clickActivity.find(".panel-body").text($(this).val());
	});
	$('.activity-text').blur(function(){
		$(this).hide();
		var clickActivity = $(this).closest('.activity').find('.click-activity');
		clickActivity.attr("ng-click", "edit = '" + clickActivity.text() + "'");
		clickActivity.show();
	});
});