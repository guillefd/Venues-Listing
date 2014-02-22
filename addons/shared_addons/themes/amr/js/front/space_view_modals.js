

$(document).ready(function(){ 

/* Modal Form Fields */

/* Form400 ----------------------------------------------*/
/* DateTime Fields */

//Option: DATE - one date
		
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
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });


    $('.f400-optDate-single-time2').timepicker({
    	minuteStep: 30,
    	defaultTime: '11:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });

//Option: DATE - range ----------------------------

	$('.input-daterange.f400-optDate-range-date').datepicker({
		startDate: "+0d",
		endDate: "+1y",
	    format: 'dd-mm-yyyy',
	    orientation: "auto left",
	    autoclose: true,
	    todayHighlight: true,
	    weekStart: 1,
	    language: 'es'
	})

    $('.f400-optDate-range-time1').timepicker({
    	minuteStep: 30,
    	defaultTime: '09:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });

    $('.f400-optDate-range-time2').timepicker({
    	minuteStep: 30,
    	defaultTime: '11:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });

    //Option: DATE - multidate ----------------------------

	$('.f400-optDate-multi-date').datepicker({
		startDate: "+0d",
		endDate: "+1y",
	    format: 'dd-mm-yyyy',
	    orientation: "auto left",
	    autoclose: false,
	    todayHighlight: true,
	    weekStart: 1,
	    language: 'es',
	    multidate: true,
	    multidateSeparator: ","	    
	})

    $('.f400-optDate-multi-time1').timepicker({
    	minuteStep: 30,
    	defaultTime: '09:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });

    $('.f400-optDate-multi-time2').timepicker({
    	minuteStep: 30,
    	defaultTime: '11:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });

// ----------------------------



});
