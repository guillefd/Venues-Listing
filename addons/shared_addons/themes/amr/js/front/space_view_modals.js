
//layouts inputs
var layoutsArr = new Array();
//features inputs
var featuresArr = new Array();

//datetime
var datetimeArr = new Array();
var index = 0;

//CONST
var TIMERANGEDIFF = 1; // in hours
var DT_TABLETBODY_ID = 'datetimeTablebody';
var DT_TABLEROW_ID = 'datetimeTablebodyRow_';
var DT_TABLEROWDELBTN_NAME = 'btn_deleteDTrow';
var DT_TABLEFOOTROW_ID = 'datetimeTablefoot';
var WEEKDAY = new Array('dom','lun','mar','mie','jue','vie','sab');
//DT2
var DT2DAY_BTNPREFIX_ID = 'btn-DT2-1-day-';


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
	this.saturdaycount = -1;
	this.sundaycount = -1;
	this.incsaturday = -1;
	this.incsunday = -1;
	this.repeats = 0;
	this.subtdays = 0;
	this.subthours = 0;
	this.deleted = -1;	
}



$(document).ready(function(){ 

// INITS ----------------------------------
	init_optDates();
	init_optDays();
	//static
    init_feature_btn_tooltip();

// EVENTS ----------------------------------

/* calendar */	
	/* datetime calendar range selected */
	$('#btnaddDT1-1').click(function () {
		process_DT_fields('DT1-1');
	});

	/* datetime calendar multi selected */
	$('#btnaddDT1-2').click(function () {
		process_DT_fields('DT1-2');
	});

/* week days */
	/* datetime week multi selected */
	$('#btnaddDT2-1').click(function () {
		process_DT_fields('DT2-1');
	});		


/* TABLE */
	//btn BORRAR (x) item de lista de dias/horarios
    $(document).on('click', 'button[name="' + DT_TABLEROWDELBTN_NAME + '"]', function(){
		var id = $(this).attr("id");
		process_remove_DT_item(id);
	});


//CONTROLLERS - EVENTS ----------------------------------

	function process_DT_fields(option)
	{
		var datetime = get_datetime_fields_values(option);		
		//validate
		if(datetime_fields_values_validation(option, datetime))
		{
			save_datetime_to_Arr(datetime);
			insert_table_row(option, datetime);
			update_table_totals();
			clear_inputs(option);
		}
	}

	function process_remove_DT_item(id)
	{
		delete_DT_table_row(id);
		delete_datetime_element_DT_Arr(id);
		update_table_totals();		
	}


// CONTROLLERS - AUXILIARS

	function delete_datetime_element_DT_Arr(id)
	{
		datetimeArr[id].deleted = 1;
	}

	function reset_datetime_Arr()
	{
		datetimeArr = new Array();
	}

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
							datetime.dateslist = get_range_dates_list(datetime.datestart, datetime.dateend);							
							datetime.timestart = $('#DT1-1-time1').val();	
							datetime.timeend = $('#DT1-1-time2').val();
							datetime.timerangehours = get_timeDiff(datetime.timestart, datetime.timeend);
							datetime.saturdaycount = count_daysOfweek(datetime.dateslist, 6);
							datetime.sundaycount = count_daysOfweek(datetime.dateslist, 0);
							datetime.incsaturday = $('#DT1-1-sat').hasClass('active') ? 1 : -1;
							datetime.incsunday = $('#DT1-1-sun').hasClass('active') ? 1 : -1;
							datetime.subtdays = get_range_subtotal_days(datetime);
							datetime.subthours = get_subtotal_hours(datetime);
							break;

			/*calendar-multi*/
			case 'DT1-2':
							datetime.idx = index;
							datetime.datetype = 'calendar';
							datetime.dateslist = get_multi_dates_list($('#DT1-2-date').val());							
							datetime.timestart = $('#DT1-2-time1').val();	
							datetime.timeend = $('#DT1-2-time2').val();
							datetime.timerangehours = get_timeDiff(datetime.timestart, datetime.timeend);
							datetime.subtdays = datetime.dateslist.length;
							datetime.subthours = get_subtotal_hours(datetime);
							break;

			/*week*/
			case 'DT2-1':
							datetime.idx = index;
							datetime.datetype = 'week';
							datetime.dateslist = get_week_days_list(DT2DAY_BTNPREFIX_ID);							
							datetime.timestart = $('#DT2-1-time1').val();	
							datetime.timeend = $('#DT2-1-time2').val();
							datetime.timerangehours = get_timeDiff(datetime.timestart, datetime.timeend);
							datetime.repeats = get_week_selection_repeat_value($('#DT2-1-repeat').hasClass('active'), $('#DT2-1-repeattimes').val());
							datetime.subtdays = datetime.dateslist.length * (datetime.repeats + 1);
							datetime.subthours = get_subtotal_hours(datetime);
							break;

			default: 		
							alert('none');
		}
		// DEBUG ----------------------------------------------------------------------		
		//alert( JSON.stringify(datetime) );	
		return datetime;
	} 

	function save_datetime_to_Arr(datetime)
	{
		datetimeArr[index] = datetime;
		index++;
	}

	function get_range_dates_list(d1, d2)
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

	function get_multi_dates_list(string)
	{			
		var aux = string.split(",");
		var list = new Array();
		for (var i = aux.length - 1; i >= 0; i--) 
		{
			if(aux[i]!='')
			{
				list.push(aux[i]);
			}
		}
		return list; 
	}

	/* btn prefix of form week days */
	function get_week_days_list(prefix)
	{
		var list = new Array();
		for (var i = 6; i >= 0; i--) 
		{
			if( $('#' + prefix + i).hasClass('active') )
			{
				list.push(i);
			}				
		}
		return list;
	}

	/*
	check: checkbox input value
	times: select input value
	 */
	function get_week_selection_repeat_value(check, times)
	{
		var repeat = 0;
		if(check)
		{
			repeat+= parseInt(times);
		}
		return repeat;
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

	function get_range_subtotal_days(datetime)
	{
		var diff = get_daysDiff(datetime.datestart, datetime.dateend);
		var substract = 0;
		if(datetime.incsaturday == -1)
		{
			//saturday = 6
			substract+= datetime.saturdaycount;
		}
		if(datetime.incsunday == -1)
		{
			//sunday = 0
			substract+= datetime.sundaycount;
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
									alert('El rango esta incompleto o no es correcto.\n\n'
										  +'Verifica por favor:\n'
										  +'  - fecha desde y fecha hasta\n'
										  +'  - hora inicio y hora finaliza\n'
										  +'  - si seleccionó una fecha en un sábado y/o domingo, marque los botones sábados y/o domingos.');
								}
							break;	

			case 'DT1-2': 
							if(	datetime.dateslist.length > 0 
								&& datetime.timerangehours >= TIMERANGEDIFF 
								&& datetime.subtdays > 0 )
							{
								return true;
							}
							else
								{
									alert('Debe seleccionar al menos 1 fecha.');
								}							
							break;

			case 'DT2-1': 
							if(	datetime.dateslist.length > 0 
								&& datetime.timerangehours >= TIMERANGEDIFF 
								&& datetime.subtdays > 0 )
							{
								return true;
							}
							else
								{
									alert('Debe seleccionar al menos 1 día de la semana.');
								}							
							break;

			default: 
							return false;				
		}
	}

// CONTROLLERS - DRAW TABLE ROW

	function insert_table_row(option, datetime)
	{
		var f1 = '';
		var f2 = '';
		var f3 = '';
		var f4 = '';
		var f5 = '';							
		switch(option)
		{
			case 'DT1-1': 
							var incSat = '';
							var incSun = '';
							if(datetime.saturdaycount>0)
							{
								incSat = (datetime.incsaturday == 1) ? 'sábados (sí),<br>' : 'sábados (no),<br>';
							}
							if(datetime.sundaycount>0)
							{
								incSun = (datetime.incsunday == 1) ? 'domingos (sí)' : 'domingos (no)';
							}
							f1 = 'del '+ datetime.datestart + '<br>al &nbsp;' + datetime.dateend;
							f2 = 'de '+ datetime.timestart + '<br>a &nbsp;' + datetime.timeend;
							f3 = datetime.subtdays + ' d';
							f4 = datetime.subthours + ' hs';
							f5 = incSat + incSun;
							break;

			case 'DT1-2': 
							var list_txt = '';
							for (var i = datetime.dateslist.length - 1; i >= 0; i--) 
							{
								list_txt+= datetime.dateslist[i];
								if(i>0)
								{
									list_txt+=', ';
								}
							}
							var listcount_txt = datetime.dateslist.length>1 ? 'fechas' : 'fecha';
							var datesbtn = '<button type="button" class="btn DTtooltip" data-toggle="tooltip" data-placement="top" title="' + list_txt + '">Ver ' + listcount_txt + '</button>';
							f1 = datesbtn;
							f2 = 'de '+ datetime.timestart + '<br>a &nbsp;' + datetime.timeend;
							f3 = datetime.subtdays + ' d';
							f4 = datetime.subthours + ' hs';
							break;

			case 'DT2-1': 
							var list_txt = '';
							var count = 1;
							for (var i = datetime.dateslist.length - 1; i >= 0; i--) 
							{
								list_txt+= WEEKDAY[datetime.dateslist[i]];
								if(i>0)
								{
									list_txt+=', ';
									if(count==3) list_txt+='<br>';
									count++;	
								}
							}
							f1 = list_txt;
							f2 = 'de '+ datetime.timestart + '<br>a &nbsp;' + datetime.timeend;
							f3 = datetime.subtdays + ' d';
							f4 = datetime.subthours + ' hs';
							if(datetime.repeats > 0)
							{
								f5 = datetime.repeats == 1 ? 'replica ' + datetime.repeats + ' semana' : 'replica ' + datetime.repeats + ' semanas';
							}
							break;
		}
		// append Rw
		var tableRow = '<tr id="' + DT_TABLEROW_ID + datetime.idx + '">'
						+'<td>' + f1 + '</td>'
						+'<td>' + f2 + '</td>'
						+'<td>' + f3 + '</td>'
						+'<td>' + f4 + '</td>'
						+'<td>' + f5 + '</td>'
						+'<td><button type="button" name="' + DT_TABLEROWDELBTN_NAME + '" id="' + datetime.idx + '" class="btn btn-xs">borrar</button></td>'
						+'</tr>';
		$('#' + DT_TABLETBODY_ID).append(tableRow);
		// tooltip
		$('.DTtooltip').tooltip();		
	}	


	function update_table_totals()
	{
		var tableFootRow = '<tr>'
						+'<td colspan="2"><strong>Dias y horas totalizadas:</strong></td>'
						+'<td><strong>'+ get_datetimeArr_total_days() +' días</strong></td>'
						+'<td><strong>'+ get_datetimeArr_total_hours() +' horas</strong></td>'
						+'<td></td>'
						+'<td></td>'
						+'</tr>';

		$('#' + DT_TABLEFOOTROW_ID).html(tableFootRow);		
	}

	function delete_DT_table_row(id)
	{
	    var name = "socialItem" + id;
    	$('#' + DT_TABLEROW_ID + id).fadeOut(300,function(){ 
        	$('#' + DT_TABLEROW_ID + id).remove();		
    	});
	}	

	function reset_DT_table()
	{
		for (var i = datetimeArr.length - 1; i >= 0; i--) 
		{
			if(datetimeArr[i].deleted < 1)
			{
				delete_DT_table_row(i);
			}
		}
		reset_datetime_Arr();
		update_table_totals();
	}

	function get_datetimeArr_total_days()
	{
		var total = 0;
		for (var i = datetimeArr.length - 1; i >= 0; i--) 
		{
			if(datetimeArr[i].deleted != 1)
			{	
				total+= datetimeArr[i].subtdays;
			}
		}
		return total;
	}

	function get_datetimeArr_total_hours()
	{
		var total = 0;
		for (var i = datetimeArr.length - 1; i >= 0; i--) 
		{
			if(datetimeArr[i].deleted != 1)			
			{
				total+= datetimeArr[i].subthours;
			}	
		}
		return total;
	}


//CONTROLLERS - INITS (calendar) -----------------------------------
	function init_optDates()
	{
		hideDT1();				
	}

	function init_optDays()
	{
		hideDT2_1();	
	}

	function showDT1()
	{
		hideDT2_1();
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

	function hideDT1()
	{
		$('#optDates').hide();		
		$('#btnDT1').removeClass('active');
		hideDT1_1();
		hideDT1_2();	
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
	function showDT2_1()
	{
		$('#optDays-multi').show();	
		init_optDates();	
	}

	function hideDT2_1()
	{
		$('#optDays-multi').hide();	
		$('#btnDT2-1').removeClass('active');				
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
	$('#btnDT1-hide').click(function () {
		hideDT1();
		clear_inputs('DT1-1');
		hideDT1_1();
	});
	$('#btnDT1-1-hide').click(function () {
		clear_inputs('DT1-1');
		hideDT1_1();
	});
	$('#btnDT1-2-hide').click(function () {
		hideDT1_2();
	});

	/*-----  DAYS  -----*/
	$('#btnDT2-1').click(function () {
		showDT2_1();
	});
	//hide
	$('#btnDT2-1-hide').click(function () {
		hideDT2_1();
	});

	/* datetime week repeat btn */
	$('#DT2-1-repeat').click(function () {
		toggle_DT2_select_repeattimes( $(this).hasClass('active') );
	});	

	function toggle_DT2_select_repeattimes(disableselect)
	{
		if( disableselect )
		{
			$('#DT2-1-repeattimes').val('1');
			$('#DT2-1-repeattimes').attr('disabled','disabled');	
		}
		else
			{
				$('#DT2-1-repeattimes').removeAttr('disabled');			
			}
	}


    //CLEAR - INPUTS ----------------------------
    
    function clear_inputs(option)
    {
		switch(option)
		{
			/*calendar-range*/
			case 'DT1-1': 
							clear_datepicker(option);
							clear_timepicker(option);
							if( $('#DT1-1-sat').hasClass('active') == false) $('#DT1-1-sat').addClass('active'); 	
							if( $('#DT1-1-sun').hasClass('active') == false) $('#DT1-1-sun').addClass('active');
							break;

			/*calendar-multi*/
			case 'DT1-2': 
							clear_datepicker(option);
							clear_timepicker(option);
							break;

			/*calendar-multi*/
			case 'DT2-1': 
							clear_datepicker(option);
							clear_timepicker(option);
							$('#DT2-1-repeat').removeClass('active');
							toggle_DT2_select_repeattimes(true);
							break;
    	}
    }

    function clear_datepicker(option)
    {
		switch(option)
		{
			/*calendar-range*/
			case 'DT1-1': 
							$('.input-daterange input').datepicker('update', null);
  							$('.input-daterange').datepicker('updateDates');
							break;

			/*calendar-multi*/
			case 'DT1-2': 
							$('.f400-optDate-multi-date').datepicker('update', null);
							break;

			/*calendar-multi*/
			case 'DT2-1': 
							for (var i = 6; i >= 0; i--) 
							{
								$('#' + DT2DAY_BTNPREFIX_ID + i).removeClass('active');	
							}
							break;
		}					

    }

    function clear_timepicker(option)
    {
		switch(option)
		{
			/*calendar-range*/
			case 'DT1-1': 
							$('.f400-optDate-range-time1').timepicker('setTime', '09:00');		
							$('.f400-optDate-range-time2').timepicker('setTime', '11:00');	
							break;

			/*calendar-multi*/
			case 'DT1-2': 
							$('.f400-optDate-multi-time1').timepicker('setTime', '09:00');		
							$('.f400-optDate-multi-time2').timepicker('setTime', '11:00');	
							break;

			/*calendar-multi*/
			case 'DT2-1': 
							$('.f400-optDays-multi-time1').timepicker('setTime', '09:00');		
							$('.f400-optDays-multi-time2').timepicker('setTime', '11:00');	
							break;
    	}
    }



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

// DATEPICKER - INPUTS -------------------------------------------

    //INIT - Option: DATE - multidate ----------------------------

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
	    multidateSeparator: ",",
	    clearBtn: true	    
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

	//auto adjust endtime
	$('.f400-optDate-multi-time1').timepicker().on('changeTime.timepicker', function(e) {
		check_starttime_diff(e.time.value, $('#DT1-2-time2'));
	});
	//auto adjust starttime
	$('.f400-optDate-multi-time2').timepicker().on('changeTime.timepicker', function(e) {
		check_endtime_diff(e.time.value, $('#DT1-2-time1'));
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

	//auto adjust endtime
	$('.f400-optDays-multi-time1').timepicker().on('changeTime.timepicker', function(e) {
		check_starttime_diff(e.time.value, $('#DT2-1-time2'));
	});
	//auto adjust starttime
	$('.f400-optDays-multi-time2').timepicker().on('changeTime.timepicker', function(e) {
		check_endtime_diff(e.time.value, $('#DT2-1-time1'));
	});	


	//FORM STATIC TEXT
	function init_feature_btn_tooltip()
	{
		$('.form400ftrtooltip').tooltip({
		});
	}

// -------------------------------------------------------------------------------------

	// LAYOUTS selection	
	//btn layouts - selected
    $(document).on('click', 'label[name="layout"]', function(){
		var id = $(this).attr("id");
    	if($(this).hasClass("active") == false)
    	{
    		layoutsArr.push(id);
    	}
    	else
	    	{
	    		var index = $.inArray(id, layoutsArr);
				if(index !==-1)
				{
					layoutsArr.splice(index,1);
				}	    		
	    	}
	});


	// FEATURES selection	
	//btn feature - selected
    $(document).on('click', 'label[name="feature"]', function(){
		var id = $(this).attr("id");
    	if($(this).hasClass("active") == false)
    	{
    		featuresArr.push(id);
    	}
    	else
	    	{
	    		var index = $.inArray(id, featuresArr);
				if(index !==-1)
				{
					featuresArr.splice(index,1);
				}	    		
	    	}
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
    	var form_data = $("#amrform400quote :input.srlz, #amrform400quote :hidden.srlz").serializeArray();
    	form_data.push(
    		{ name: "layoutsids", value: layoutsArr },
    		{ name: "featureids", value: featuresArr },    		    		    		    		    		    		    		
    		{ name: "datetimeObj", value: JSON.stringify(datetimeArr) }
    	);   	
    	form_data = jQuery.param(form_data);				 
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
	    // reset_DT_table();
	    init_optDates();
	    init_optDays();
	})



});
