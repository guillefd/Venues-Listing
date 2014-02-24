
var datetimeArr = new Array();
var index = 0;

//DateTime global object
function dtF()
{
	this.idx = 0;
	this.datetype = ''; //calendar, week, qty
	this.datestart = '';
	this.dateend = '';
	this.timestart = '';
	this.timeend = '';
	this.incsaturday = -1;
	this.incsunday = -1;
	this.repeats = 1;
	this.subtdays = 0;
	this.subthours = 0;	
}



$(document).ready(function(){ 

// INITS ----------------------------------
	init_optDates();
	init_optDays();

// EVENTS ----------------------------------
	
	$('#btnaddDT1-1').click(function () {
		add_datetime('DT1-1');
	});


//CONTROLLERS - EVENTS ----------------------------------
	function add_datetime(option)
	{
		datetime = new dtF();
		switch(option)
		{
			/*calendar-range*/
			case 'DT1-1': 
							datetime.idx = index;
							datetime.datetype = 'calendar';
							datetime.datestart = $('#DT1-1-date1').val();
							datetime.dateend = $('#DT1-1-date2').val();							
							datetime.timestart = $('#DT1-1-time1').val();	
							datetime.timeend = $('#DT1-1-time2').val();
							datetime.incsaturday = $('#DT1-1-sat').hasClass('active') ? 1 : -1;
							datetime.incsunday = $('#DT1-1-sun').hasClass('active') ? 1 : -1;
							datetime.subtdays = get_daysDiff(datetime.datestart, datetime.dateend);
							datetime.subthours = get_timeDiff(datetime.timestart, datetime.timeend);
							//debug
							alert( JSON.stringify(datetime) );
							break;
			
			default: 		
							alert('none');

		}
	} 

// CONTROLLERS - AUXILIARS
	function get_timeDiff(t1,t2)
	{
		var time1 = get_formated_time(t1);
		var time2 = get_formated_time(t2);
		return ((time2 - time1) / 1000)/3600; // return in hours
	}	

	function get_daysDiff(d1, d2)
	{
		var date1 = get_formated_date(d1);
		var date2 = get_formated_date(d2);
		var timeDiff = Math.abs(date2.getTime() - date1.getTime());
		return Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; 		
	}

	function get_formated_time(t)
	{
		return new Date(1970, 1, 1, t.substring(0,2), t.substring(3,5), 0, 0);
	}

	function get_formated_date(d)
	{
		return new Date(parseInt(d.substring(6,10)), parseInt(d.substring(3,5)-1), parseInt(d.substring(0,2)) );
	}

	function check_starttime_diff(t1, idt2)
	{
		if( get_timeDiff( t1, idt2.val() )<2 )
		{
			adjust_input_endtime(t1, idt2);
		}		
	}

	function adjust_input_endtime(t1, idt2)
	{
		var time = get_formated_time(t1);
		var hours =  time.getHours() + 2;
		hours = hours < 10 ?  '0' + hours : hours;			
		hours = hours == 24 ? '00' : hours; 
		hours = hours == 25 ? '01' : hours; 						
		var minutes = time.getMinutes() < 10 ? '0' + time.getMinutes() : time.getMinutes();
		idt2.val( hours + ':' + minutes );
	}	

	function check_endtime_diff(t2, idt1)
	{
		if( get_timeDiff( idt1.val(), t2 )<2 )
		{
			adjust_input_starttime(t2, idt1);
		}		
	}

	function adjust_input_starttime(t2, idt1)
	{
		var time = get_formated_time(t2);
		var hours =  time.getHours() - 2;
		hours = (hours < 10 && hours >= 0) ?  '0' + hours : hours;			
		hours = hours == -1 ? '23' : hours; 
		hours = hours == -2 ? '22' : hours; 						
		var minutes = time.getMinutes() < 10 ? '0' + time.getMinutes() : time.getMinutes();
		idt1.val( hours + ':' + minutes );
	}	


//CONTROLLERS - INITS (calendar) -----------------------------------
	function init_optDates()
	{
		$('#optDates').hide();
		$('#optDates-range').hide();		
		$('#optDates-multi').hide();
		$('#btnDT1').removeClass('active');	
		$('#btnDT1-1').removeClass('active');	
		$('#btnDT1-2').removeClass('active');					
	}

	function init_optDays()
	{
		$('#optDays-multi').hide();	
	}

	function showDT1()
	{
		$('#optDays-multi').hide();	
		$('#optDates').show();	
	}
	function showDT1_1()
	{
		$('#optDates-range').show();		
		$('#optDates-multi').hide();	
	}	
	function showDT1_2()
	{
		$('#optDates-range').hide();
		$('#optDates-multi').show();		
	}

// CONTROLLERS - VALIDATIONS -----------------------------------
	function validate_DT1_range()
	{
		var rules = new Array();
		rules[0] = '';
	}			

//CONTROLLERS - INITS (week) -----------------------------------
	function showDT2()
	{
		$('#optDays-multi').show();	
		init_optDates();	
	}

// UI EVENTS (form) ----------------------------
	//BTN IDS
	/*
		btnDT1
		btnDT1-1
		btnDT1-2

		btnDT2
	 */
	/*-----  DATES  -----*/
	$('#btnDT1').click(function () {
		showDT1();
	});
	$('#btnDT1-1').click(function () {
		showDT1_1();
	});
	$('#btnDT1-2').click(function () {
		showDT1_2();
	});	

	/*-----  DAYS  -----*/
	$('#btnDT2').click(function () {
		showDT2();
	});




/* Modal Form Fields */
/* Form400 ----------------------------------------------*/
/* DateTime Fields */

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
    $('.dt11-time1').click(function () { 
    	$('.f400-optDate-range-time1').timepicker('showWidget');
	});   

    $('.f400-optDate-range-time2').timepicker({
    	minuteStep: 30,
    	defaultTime: '11:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });
    $('.dt11-time2').click(function () { 
    	$('.f400-optDate-range-time2').timepicker('showWidget');
	});       

	//auto adjust endtime
	$('.f400-optDate-range-time1').timepicker().on('changeTime.timepicker', function(e) {
		check_starttime_diff(e.time.value, $('#DT1-1-time2'));
	});
	//auto adjust starttime
	$('.f400-optDate-range-time2').timepicker().on('changeTime.timepicker', function(e) {
		check_endtime_diff(e.time.value, $('#DT1-1-time1'));
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
    $('.dt12-time1').click(function () { 
    	$('.f400-optDate-multi-time1').timepicker('showWidget');
	});       

    $('.f400-optDate-multi-time2').timepicker({
    	minuteStep: 30,
    	defaultTime: '11:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });
    $('.dt12-time2').click(function () { 
    	$('.f400-optDate-multi-time2').timepicker('showWidget');
	});      

	// Option: DAYS - multi -------------------------------
    $('.f400-optDays-multi-time1').timepicker({
    	minuteStep: 30,
    	defaultTime: '09:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });
    $('.dt21-time1').click(function () { 
    	$('.f400-optDays-multi-time1').timepicker('showWidget');
	});   

    $('.f400-optDays-multi-time2').timepicker({
    	minuteStep: 30,
    	defaultTime: '11:00',
    	showMeridian: false,
    	disableFocus: false,
    	modalBackdrop: true
    });
    $('.dt21-time2').click(function () { 
    	$('.f400-optDays-multi-time2').timepicker('showWidget');
	});  


// -------------------------------------------------------------------------------------

	//PROCESS - SEND

	// MESSAGES
	/* BTN SEND MESSAGE */
		$("#amrbtnsendspace300query").on("click", function(e) { 
			var link = BASE_URL + 'alquiler-de-salas/messaging';
	    	var form_data = $("#amrform300query :input, #amrform300query :hidden").serialize();			
			doAjaxQuery(link, '300query', form_data);
		});	
		$("#amrbtnsendspace400quote").on("click", function(e) { 
			var link = BASE_URL + 'alquiler-de-salas/messaging';
	    	var form_data = $("#amrform400quote :input, #amrform400quote :hidden").serialize();				
			doAjaxQuery(link, '400quote', form_data);
		});			


	function doAjaxQuery(link, form, form_data)
	{
		disable_sumbit_button(form);
	    clean_msgbox_class_and_html(form);  		
	    $.ajax({
	        type: "POST",
	        url: link,
	        data: form_data,
	        dataType: 'json',
	        success: function(result)
	        {
	        	enable_submit_button(form);
	        	if(result.response === true)
	        	{
	        		if(result.Error === true)
	        		{
	        			$('#msgbox' + form).addClass('alert alert-danger');
	        			$('#msgbox' + form).html(result.message);
	        		}
	        		if(result.Error === false)
	        		{       			
	        			$('#msgbox' + form).addClass('alert alert-success');	        	        
	        			$('#msgbox' + form).html(result.message);
	        			$('textarea[name="message"]').val('');

	        		}
	        	}
	        	else
		        	{	        		
	        			$('#msgbox' + form).addClass('alert alert-danger');
	        			$('#msgbox' + form).html('<p>Hubo un error al enviar el mensaje, vuelva a intentarlo.</p>');
		        	}
	        },
	        error: function()
	        {
            	enable_submit_button(form);          	
	        	$('#msgbox' + form).addClass('alert alert-warning');            	
	        	$('#msgbox' + form).html('<p>Error de comunicación, vuelva a intentarlo.</p>');            	
	        }
	    });         
	}


	function disable_sumbit_button(form)
	{
		$("#amrbtnsendspace" + form).attr('disabled', 'disabled');
	}

	function enable_submit_button(form)
	{
		$("#amrbtnsendspace" + form).removeAttr('disabled');
	}

	function clean_msgbox_class_and_html(form)
	{
		$('#msgbox' + form).removeClass('alert alert-danger alert-warning');
		$('#msgbox' + form).html('');
	}

	$('#amrformmessage300query').on('hidden.bs.modal', function (e) {
	    clean_msgbox_class_and_html('300query');  
	})

	$('#amrformmessage400quote').on('hidden.bs.modal', function (e) {
	    clean_msgbox_class_and_html('400quote');
	    init_optDates();
	    init_optDays();
	})



});
