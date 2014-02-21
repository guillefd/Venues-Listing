

$(document).ready(function(){ 

/* Modal Form Fields */

/* Form400 ----------------------------------------------*/
/* DateTime Fields */

//Option: DATE
		
	$('.f400-optDate-single-date1').datepicker({
		startDate: "+0d",
		endDate: "+1y",
	    format: 'dd-mm-yyyy',
	    orientation: "auto left",
	    autoclose: true,
	    todayHighlight: true,
	    weekStart: 1,
	    language: 'es'
	})


    $('.f400-optDate-single-time1').timepicker({
    	minuteStep: 30,
    	defaultTime: '09:00',
    	showMeridian: false
    });

});
