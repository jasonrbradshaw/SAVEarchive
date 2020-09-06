// Textarea Counter
// https://jsfiddle.net/jamesnotjim/k9fzoytp/
$(document).ready(function() {
		// set the IDs for the text areas and counters
		// handy for aliasing long, CMS-generated IDs

		// Parent Statment Questions
		var maxChar = 'maxChar';
		var maxCounter = 'maxCounter';

		
    	$('#' + maxChar).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#maxChar').text(remain);
    		}
			$('#' + maxCounter).text('Characters remaining (' + remainingChars + ')');
    	});

 	}); 

