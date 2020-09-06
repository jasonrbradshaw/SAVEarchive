// Textarea Counter
// https://jsfiddle.net/jamesnotjim/k9fzoytp/
$(document).ready(function() {
		// set the IDs for the text areas and counters
		// handy for aliasing long, CMS-generated IDs

		// Parent Statment Questions
		var pq1 = 'pq1';
		var counter = 'counter';
		var pq2 = 'pq2';
		var counter2 = 'counter2';
		var pq3 = 'pq3';
		var counter3 = 'counter3';
		var pq4 = 'pq4';
		var counter4 = 'counter4';
		var pq5 = 'pq5';
		var counter5 = 'counter5';
		var pq6 = 'pq6';
		var counter6 = 'counter6';
		var pq7 = 'pq7';
		var counter7 = 'counter7';
		var pq8 = 'pq8';
		var counter8 = 'counter8';
		var pq9 = 'pq9';
		var counter9 = 'counter9';
		
    	$('#' + pq1).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq1').text(remain);
    		}
			$('#' + counter).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq2).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq2').text(remain);
    		}
			$('#' + counter2).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq3).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq3').text(remain);
    		}
			$('#' + counter3).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq4).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq4').text(remain);
    		}
			$('#' + counter4).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq5).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq5').text(remain);
    		}
			$('#' + counter5).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq6).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq6').text(remain);
    		}
			$('#' + counter6).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq7).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq7').text(remain);
    		}
			$('#' + counter7).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq8).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq8').text(remain);
    		}
			$('#' + counter8).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + pq9).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#pq9').text(remain);
    		}
			$('#' + counter9).text('Characters remaining (' + remainingChars + ')');
    	});


    	// Candidate Statment Questions
		var sq1 = 'sq1';
		var scounter = 'scounter';
		var sq2 = 'sq2';
		var scounter2 = 'scounter2';
		var sq3 = 'sq3';
		var scounter3 = 'scounter3';
		var sq4 = 'sq4';
		var scounter4 = 'scounter4';
		var sq5 = 'sq5';
		var scounter5 = 'scounter5';
		var sq6 = 'sq6';
		var scounter6 = 'scounter6';
		var sq7 = 'sq7';
		var scounter7 = 'scounter7';
		var sq8 = 'sq8';
		var scounter8 = 'scounter8';
		var sq9 = 'sq9';
		var scounter9 = 'scounter9';
		var sq10 = 'sq10';
		var scounter10 = 'scounter10';

		$('#' + sq1).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq1').text(remain);
    		}
			$('#' + scounter).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + sq2).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq2').text(remain);
    		}
			$('#' + scounter2).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + sq3).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq3').text(remain);
    		}
			$('#' + scounter3).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + sq4).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq4').text(remain);
    		}
			$('#' + scounter4).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + sq5).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq5').text(remain);
    		}
			$('#' + scounter5).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + sq6).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq6').text(remain);
    		}
			$('#' + scounter6).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + sq7).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq7').text(remain);
    		}
			$('#' + scounter7).text('Characters remaining (' + remainingChars + ')');
    	});
    	 $('#' + sq8).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq8').text(remain);
    		}
			$('#' + scounter8).text('Characters remaining (' + remainingChars + ')');
    	});
    	 $('#' + sq9).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq9').text(remain);
    		}
			$('#' + scounter9).text('Characters remaining (' + remainingChars + ')');
    	});
    	 $('#' + sq10).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#sq10').text(remain);
    		}
			$('#' + scounter10).text('Characters remaining (' + remainingChars + ')');
    	});










		// Teacher Referral Questions
		var tq1 = 'tq1';
		var tcounter = 'tcounter';
		var tq2 = 'tq2';
		var tcounter2 = 'tcounter2';
		var tq3 = 'tq3';
		var tcounter3 = 'tcounter3';
		var tq4 = 'tq4';
		var tcounter4 = 'tcounter4';
		var tq5 = 'tq5';
		var tcounter5 = 'tcounter5';

		$('#' + tq1).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#tq1').text(remain);
    		}
			$('#' + tcounter).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + tq2).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#tq2').text(remain);
    		}
			$('#' + tcounter2).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + tq3).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#tq3').text(remain);
    		}
			$('#' + tcounter3).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + tq4).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#tq4').text(remain);
    		}
			$('#' + tcounter4).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + tq5).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#tq5').text(remain);
    		}
			$('#' + tcounter5).text('Characters remaining (' + remainingChars + ')');
    	});

		// Community Referral Questions
		var cq1 = 'cq1';
		var ccounter = 'ccounter';
		var cq2 = 'cq2';
		var ccounter2 = 'ccounter2';
		var cq3 = 'cq3';
		var ccounter3 = 'ccounter3';

		$('#' + cq1).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#cq1').text(remain);
    		}
			$('#' + ccounter).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + cq2).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#cq2').text(remain);
    		}
			$('#' + ccounter2).text('Characters remaining (' + remainingChars + ')');
    	});
    	$('#' + cq3).keyup(function () {
	    	var charLimit = 2000; 
	    	var remainingChars = charLimit - $(this).val().length;
			if (remainingChars < 0) {
				// trim input, if necessary
				var tlength = $(this).val().length;
				$(this).val($(this).val().substring(0, charLimit));
            	var tlength = $(this).val().length;
            	remain = parseInt(tlength);
            	$('#cq3').text(remain);
    		}
			$('#' + ccounter3).text('Characters remaining (' + remainingChars + ')');
    	});
	}); 

