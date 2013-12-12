
// global
var img_loader = '<img src="' + IMG_PATH + 'loader.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';
var img_loader_2 = '<img src="' + IMG_PATH + 'indicator.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';
var target = SITE_URL + 'admin/accounts/contacts';
var target_filter = SITE_URL + 'admin/products/spaces/ajax_filter';

$(document).ready(function(){ 
   
        var location_filter = $('input[name="f_location_id"]')
        var keyword_filter = $('input[name="f_keywords"]');
        $('#btnCancel').attr('class','btn gray');
   
        //input filter action - keypress
        keyword_filter.keypress(function() {
            $('#loader').remove();
            keyword_filter.after(img_loader_2);  
            doAjaxQuery(keyword_filter, location_filter, target_filter);            
        }); 
        
        
//input autocomplete - Locations
        $('input[name="f_location"]').autocomplete({
			source: function( request, response ) {
				keyword_filter.after(img_loader_2); 
                $.ajax({
					url: SITE_URL + 'admin/products/locations_autocomplete_ajax',
					dataType: "json",
					data: {
						limit: 20,
						term: request.term
					},
                    success: function( data ) {
                        response( $.map( data.locations, function( item ) {
                                return {
                                        label: item.name + " (" + item.geo_street_name + item.geo_street_number + ")",
                                        value: item.name + " (" + item.geo_street_name + item.geo_street_number + ")",
                                        locationid: item.id                                                              
                                }       
                        }));
                        $('#loader').remove();  
                    }
				});
			},
            focus: function( event, ui ) {
               $( "#f_location" ).val( ui.item.label );
               return false;
            },                        
			minLength: 3,
			select: function( event, ui ) {                                
                $('input[name="f_location_id"]').val(ui.item.locationid);
                doAjaxQuery(keyword_filter, location_filter, target_filter);                                  
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});                

        //Autocomplete Cities
		$('input[name="f_city"]').autocomplete({
			source: function( request, response ) {
				$(keyword_filter).after(img_loader_2); 
                                $.ajax({
					url: SITE_URL + 'admin/geoworldmap/cities/autocomplete_ajax',
					dataType: "json",
					data: {
						limit: 50,
						term: request.term
					},
					success: function( data ) {
						response( $.map( data.cities, function( item ) {
							return {
								label: item.city + ", " + item.region + ", " + item.country,
                                value: item.city + ", " + item.region + ", " + item.country,
                                cityid: item.id,
                                countryphonecode: item.countryphonecode,
                                cityphonecode: item.cityphonecode                                                                
                        }
						}));
                        $('#loader').remove(); 
					}
				});
			},
            focus: function( event, ui ) {
                   $( "#CityAjax" ).val( ui.item.label );
                   return false;
            },                        
			minLength: 3,
			select: function( event, ui ) {                                
                $('input[name="f_city_id"]').val(ui.item.cityid);
                doAjaxQuery(keyword_filter, account_filter, city_filter, target_filter);                                 
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		}); 
        
   
});



/**
 * Do ajax request for Index - table view
 * @param keyword_filter select object selector
 * @param account_filter select object selector
 * @param city_filter input object selector
 * @param link URL to send query, responds HTML
 * success updates #table with new values
 */
function doAjaxQuery(keyword_filter, location_filter, link)
{
    var form_data = {
        f_location : location_filter.val(),  
        f_keywords: keyword_filter.val(),
    };
    $.ajax({
        type: "POST",
        url: link,
        data: form_data,
        dataType: 'html',
        success: function(result){
            $('#btnCancel').attr('class','btn orange');            
            //replace Table with new values 
            $('#indexTable').replaceWith(result);            
            // remove GIF
            $('#loader').remove();                          
        },
        error: function()
        {
            $('#loader').remove(); 
        }
    });         
}
