$(document).ready(function() 
{
	$('.activity-text').blur(function(){
		$(this).closest("form").submit();
	});
});