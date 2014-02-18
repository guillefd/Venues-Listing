

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


	// MESSAGES
	/* BTN SEND MESSAGE */
		$("#amrbtnsendspacemessage").on("click", function(e) { 
			var link = BASE_URL + 'alquiler-de-salas/messaging';
			doAjaxQuery(link);
		});	


	function doAjaxQuery(link)
	{
		disable_sumbit_button();
	    clean_msgbox_class_and_html();  		
	    var form_data = $(":input,:hidden").serialize();
	    $.ajax({
	        type: "POST",
	        url: link,
	        data: form_data,
	        dataType: 'json',
	        success: function(result)
	        {
	        	enable_submit_button();
	        	if(result.response === true)
	        	{
	        		if(result.Error === true)
	        		{
	        			$('#msgbox').addClass('alert alert-danger');
	        			$('#msgbox').html(result.message);
	        		}
	        		if(result.Error === false)
	        		{       			
	        			$('#msgbox').addClass('alert alert-success');	        	        
	        			$('#msgbox').html(result.message);
	        			$('textarea[name="message"]').val('');

	        		}
	        	}
	        	else
		        	{	        		
	        			$('#msgbox').addClass('alert alert-danger');
	        			$('#msgbox').html('<p>Hubo un error al enviar el mensaje, vuelva a intentarlo.</p>');
		        	}
	        },
	        error: function()
	        {
            	enable_submit_button();          	
	        	$('#msgbox').addClass('alert alert-warning');            	
	        	$('#msgbox').html('<p>Error de comunicación, vuelva a intentarlo.</p>');            	
	        }
	    });         
	}


	function disable_sumbit_button()
	{
		$("#amrbtnsendspacemessage").attr('disabled', 'disabled');
	}

	function enable_submit_button()
	{
		$("#amrbtnsendspacemessage").removeAttr('disabled');
	}

	function clean_msgbox_class_and_html()
	{
		$('#msgbox').removeClass('alert alert-danger alert-warning');
		$('#msgbox').html('');
	}

	$('#amrformmessage').on('hidden.bs.modal', function (e) {
	    clean_msgbox_class_and_html();  
	})

});			