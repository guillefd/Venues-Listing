

$(document).ready(function(){ 

	//galeries
	$('.carousel').carousel({
		interval: false
	});

	//tooltip
	$('span.label.label-success.facility').tooltip({
		container: 'body'
	});
	$('span.label.label-default.facility').tooltip({
		container: 'body'
	});
	$('span.service-icon-included').tooltip({
		container: 'body'
	});	

	//tooltip
	$('span.label.usetype').tooltip();

	$('.amr-tooltip').tooltip({
		container: 'body'
	});

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
	})

});			