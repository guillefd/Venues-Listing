$(document).ready(function(){ 

		$('.carousel').carousel({
			interval: 4500
		});

   		/* filter form  */
        $("#searchform").submit(function( event ) {
        	var uri = '';
            if( $('input[name="category-slug"]').val() != '' )
            {
	            var uri = $('input[name="category-slug"]').val();
	            if( $('input[name="city-slug"]').val() != '')
	            {
	            	uri+= '/' + $('select[name="city-slug"]').val() + '/';
		            if( $('select[name="capacity-range"]').val() != '')
		            {
		            	uri+= '?capacity=' + $('select[name="capacity-range"]').val();
		            }
			        //submit only if city selected   
		            $("#searchform").attr("action", uri);
					$("#searchform").submit();
		        }    
	        }
		});

});