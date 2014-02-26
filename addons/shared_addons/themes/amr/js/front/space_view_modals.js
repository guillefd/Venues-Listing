
var datetimeArr = new Array();
var index = 0;

var TIMERANGEDIFF = 2;

//DateTime global object
function dtF()
{
	this.idx = 0;
	this.datetype = ''; //calendar, week, qty
	this.datestart = '';
	this.dateend = '';
	this.dateslist = new Array();
	this.timestart = '';
	this.timeend = '';
	this.timerangehours = 0;
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
		get_datetime_fields_values('DT1-1');
	});


//CONTROLLERS - EVENTS ----------------------------------
	function get_datetime_fields_values(option)
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
							datetime.dateslist = get_dates_list(datetime.datestart, datetime.dateend);							
							datetime.timestart = $('#DT1-1-time1').val();	
							datetime.timeend = $('#DT1-1-time2').val();
							datetime.timerangehours = get_timeDiff(datetime.timestart, datetime.timeend);
							datetime.incsaturday = $('#DT1-1-sat').hasClass('active') ? 1 : -1;
							datetime.incsunday = $('#DT1-1-sun').hasClass('active') ? 1 : -1;
							datetime.subtdays = get_subtotal_days(datetime);
							datetime.subthours = get_subtotal_hours(datetime);
							break;
			
			default: 		
							alert('none');
		}
		//validate
		if(datetime_fields_values_validation(option, datetime))
		{
			save_datetime_to_Arr(datetime);
			insert_table_row(option, datetime);
			reset_datetime_fields(option);
		}
// DEBUG ----------------------------------------------------------------------		
//alert( JSON.stringify(datetime) );	
	} 


// CONTROLLERS - AUXILIARS

	function save_datetime_to_Arr(datetime)
	{
		datetimeArr[index] = datetime;
		index++;
	}

	function reset_datetime_fields(option)
	{
		switch(option)
		{
			/*calendar-range*/
			case 'DT1-1': 		
							$('.input-daterange.f400-optDate-range-date').datepicker('update', null );
							$('#DT1-1-date1').val('');	
							$('#DT1-1-date2').val('');
							$('#DT1-1-time1').val('');
							$('#DT1-1-time2').val('');
							if( $('#DT1-1-sat').hasClass('active') == false) $('#DT1-1-sat').addClass('active'); 	
							if( $('#DT1-1-sun').hasClass('active') == false) $('#DT1-1-sun').addClass('active');
							break;
		}					
	}

	function get_dates_list(d1, d2)
	{
		var list = new Array();
		if(d1!='' && d2!='')
		{
		    var date1 = get_formated_date(d1);
		    var date2 = get_formated_date(d2);
		    var date = date1;
		    while(date <= date2)
		    {
		    	list.push(date);
		    	date = add_days_to_date(date, 1);
		    }
		}
	    return list;
	}

	function add_days_to_date(d, daysToAdd)
	{
		var newdate = new Date(d.getFullYear(), d.getMonth(), d.getDate() + daysToAdd, 0,0,0,0);
		return newdate; 
	}

	function get_subtotal_hours(datetime)
	{
		return datetime.timerangehours * datetime.subtdays;
	}

	function get_timeDiff(t1, t2)
	{
		var diff = 0;
		var time1 = get_formated_time(t1);
		var time2 = get_formated_time(t2);
		if( time1 > time2 )
		{
			time2 = new Date(time2.getFullYear(), time2.getMonth(), time2.getDate() + 1, time2.getHours(), time2.getMinutes(),0,0);	
		}
		diff = (time2 - time1) / 1000 / 3600; // get hours
		return diff; 
	}

	function get_subtotal_days(datetime)
	{
		var diff = get_daysDiff(datetime.datestart, datetime.dateend);
		var substract = 0;
		if(datetime.incsaturday == -1)
		{
			//saturday = 6
			substract+= count_daysOfweek(datetime.dateslist, 6);
		}
		if(datetime.incsunday == -1)
		{
			//sunday = 0
			substract+= count_daysOfweek(datetime.dateslist, 0);
		}
		return diff - substract;
	}	

	//count dates which are equal to weekDayNum (0-6)
	function count_daysOfweek(datesArr, weekDayNum)
	{
		var count = 0;
		for (var i=0; i < datesArr.length; i++)
		{ 
			if(datesArr[i].getDay() == weekDayNum)
			{
				count++;
			}
		}
		return count;
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
		if( get_timeDiff( t1, idt2.val() ) < TIMERANGEDIFF )
		{
			adjust_input_endtime(t1, idt2);
		}		
	}

	function adjust_input_endtime(t1, idt2)
	{
		var time = get_formated_time(t1);
		var hours =  time.getHours() + TIMERANGEDIFF;
		hours = hours < 10 ?  '0' + hours : hours;			
		hours = hours == 24 ? '00' : hours; 
		hours = hours == 25 ? '01' : hours; 						
		var minutes = time.getMinutes() < 10 ? '0' + time.getMinutes() : time.getMinutes();
		idt2.val( hours + ':' + minutes );
	}	

	function check_endtime_diff(t2, idt1)
	{
		if( get_timeDiff( idt1.val(), t2 ) < TIMERANGEDIFF )
		{
			adjust_input_starttime(t2, idt1);
		}		
	}

	function adjust_input_starttime(t2, idt1)
	{
		var time = get_formated_time(t2);
		var hours =  time.getHours() - TIMERANGEDIFF;
		hours = (hours < 10 && hours >= 0) ?  '0' + hours : hours;			
		hours = hours == -1 ? '23' : hours; 
		hours = hours == -2 ? '22' : hours; 						
		var minutes = time.getMinutes() < 10 ? '0' + time.getMinutes() : time.getMinutes();
		idt1.val( hours + ':' + minutes );
	}	

// CONTROLLERS - VALIDATIONS -----------------------------------
	function datetime_fields_values_validation(option, datetime)
	{
		switch(option)
		{
			case 'DT1-1': 
							if(	datetime.dateslist.length > 0 
								&& datetime.timerangehours >= TIMERANGEDIFF 
								&& datetime.subtdays > 0 )
							{
								return true;
							}
							else
								{
									alert('El rango esta incompleto o no es correcto, verifique por favor.');
								}
							break;	

			default: 
							return false;				
		}
	}

// CONTROLLERS - DRAW TABLE ROW

	function insert_table_row(option, datetime)
	{
		switch(option)
		{
			case 'DT1-1': 
							//JSON.stringify(datetime)
							var incSat = datetime.incsaturday == 1 ? 'incluye sábados,<br>' : 'no incluye sábados,<br>';
							var incSun = datetime.incsunday == 1 ? 'incluye domingos' : 'no incluye domingos';
							var tableRow = '<tr>'
											+'<td>'+ datetime.datestart + '<br>a ' + datetime.dateend +'</td>'
											+'<td>'+ datetime.timestart + '<br>a ' + datetime.timeend + '</td>'
											+'<td>'+ datetime.subtdays +'</td>'
											+'<td>'+ datetime.subthours +'</td>'
											+'<td>'+ incSat + incSun +'</td>'
											+'<td><button name="btn_deleteDTrow" id="" class="btn btn-xs">borrar</button></td>'
											+'</tr>';
							$('#datetimeTablebody').append(tableRow);
							break;
		}
	}	


//CONTROLLERS - INITS (calendar) -----------------------------------
	function init_optDates()
	{
		$('#optDates').hide();
		$('#btnDT1').removeClass('active');			
		hideDT1_1();
		hideDT1_2();					
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

	function hideDT1_1()
	{
		$('#optDates-range').hide();
		$('#btnDT1-1').removeClass('active');
	}

	function hideDT1_2()
	{
		$('#optDates-multi').hide();
		$('#btnDT1-2').removeClass('active');
	}

//CONTROLLERS - INITS (week) -----------------------------------
	function showDT2()
	{
		$('#optDays-multi').show();	
		init_optDates();	
	}

	function hideDT2()
	{
		$('#optDays-multi').hide();	
		$('#btnDT2').removeClass('active');					
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
	//hide
	$('#btnDT1-1-hide').click(function () {
		hideDT1_1();
	});
	$('#btnDT1-2-hide').click(function () {
		hideDT1_2();
	});

	/*-----  DAYS  -----*/
	$('#btnDT2').click(function () {
		showDT2();
	});
	//hide
	$('#btnDT2-hide').click(function () {
		hideDT2();
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
	    language: 'es',
	    clearBtn: true
	});

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
