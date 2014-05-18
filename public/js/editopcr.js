$(document).ready(function() {
	$('.save-button').hide();
	$('#selected-office').text($('#office-select option:selected').text());
	$('#office-select').change(function() {
		$('#selected-office').text($('#office-select option:selected').text());
		$('.save-button').show();
	});

	$('#period-select').keyup(function() {
		$('#selected-period').text($('#period-select').val());
		$('.save-button').show();

	});
	
	var A_count = 0;
	var B_count = 0;
	var C_count = 0;
	var D_count = 0;
	
	function addIndicator() {

		var form = $(this).closest(".opcr-add");
		var tbody = form.find('.add-button').data('tbody');

		if( ! tbody) {
			tbody = "B-"+form.find('.input1').val();
			id = B_count++;
			$("#form").append($("<input type='hidden' name='"+id+"-B-1' value='"+form.find('.input1').val()+"'>"));
		}
		else {
			var id;
			switch(tbody.charAt(0))
			{
				case 'A': id = A_count++; break;
				case 'C': id = C_count++; break;
				case 'D': id = D_count++; break;
			}
		}

		var newIndicator = $("<tr><td></td><td><input name='indicator' type='text' class='form-control input2' value="+form.find('.input2').val()+"></td><td><input type='text' class='form-control input3' value="+form.find('.input3').val()+"></td><td><input type='text' class='form-control input4' value="+form.find('.input4').val()+"></td><td><button class = 'form-control delete-button' data-tbody = 'A-BM' name='delete' >Delete</button></td></tr>");
		newIndicator.find(".delete-button").click(minusIndicator);

		$('#'+tbody).find('.empty').remove();
		$("#"+tbody).append(newIndicator);

		if(tbody.charAt(0) == 'A') {
			if(tbody.charAt(2) == 'B') {
				$("#form").append($("<input type='hidden' name='"+id+"-A-1' value='BG'>"));
			}
			else {
				$("#form").append($("<input type='hidden' name='"+id+"-A-1' value='LM'>"));
			}
			tbody = 'A';
		}
		else if(tbody.charAt(0) == 'B')
			tbody = 'B';

		$("#form").append($("<input type='hidden' name='"+id+"-"+tbody+"-2' value='"+form.find('.input2').val()+"'>"));
		$("#form").append($("<input type='hidden' name='"+id+"-"+tbody+"-3' value='"+form.find('.input3').val()+"'>"));
		$("#form").append($("<input type='hidden' name='"+id+"-"+tbody+"-4' value='"+form.find('.input4').val()+"'>"));

		form.find('.input2').val('').focus();
		form.find('.input3').val('');
		form.find('.input4').val('');
	}

	function pressEnter(event) {
		if(event.which == 13) {
			event.preventDefault();
			$(this).closest(".opcr-add").find('.add-button').click();
		}
	}
	function minusIndicator()
	{
		$(this).closest('tr').remove();
	}

	$('.input1').keypress(pressEnter);
	$('.input2').keypress(pressEnter);
	$('.input3').keypress(pressEnter);
	$('.input4').keypress(pressEnter);

	$('.add-button').click(addIndicator);
	$('.delete-button').click(minusIndicator);
});