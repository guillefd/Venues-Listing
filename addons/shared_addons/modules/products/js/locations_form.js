
// global
var img_loader = '<img src="' + IMG_PATH + 'loader.gif" style="margin:5px;" id="loader" alt="" /></div>';
var img_loader_2 = '<img src="' + IMG_PATH + 'indicator.gif" style="margin:5px;" id="loader" alt="" /></div>';


$(document).ready(function(){   
    
//::::::::::::::: Social Field :::::::::::::::::::::

                //inicializa campo hidden pago_proveedores_dias_horarios, si tiene valor los agrega al html
                init_hidden_social_accounts();  		

                //copies HTML values of select element for rewriting element after item added
                social_list = $('select[name="dd_social"]').html();

                //watcher btn ADD
                $("#btn_add").click(function() {
                    $("#btn_add").removeAttr('href');      
                    processData();           
                });

//                //watcher btn BORRAR (x) item de lista de dias/horarios
                $(document).on('click', 'a[name="btn_del"]', function(){ //do stuff here })
                    $(this).removeAttr('href');
                    var id = $(this).attr("id");
                    deleteItem(id);
                });
//::::::::::::::: Social Field ::::::::::::::::::::::                
                
                
                // generate a slug when the user types a name in
		pyro.generate_slug('input[name="name"]', 'input[name="slug"]');
		
		// editor switcher
		$('select[name^=type]').live('change', function() {
			chunk = $(this).closest('li.editor');
			textarea = $('textarea', chunk);
			
			// Destroy existing WYSIWYG instance
			if (textarea.hasClass('wysiwyg-simple') || textarea.hasClass('wysiwyg-advanced')) 
			{
				textarea.removeClass('wysiwyg-simple');
				textarea.removeClass('wysiwyg-advanced');
					
				var instance = CKEDITOR.instances[textarea.attr('id')];
			    instance && instance.destroy();
			}
			
			
			// Set up the new instance
			textarea.addClass(this.value);			
			pyro.init_ckeditor();			
		});

                $("#accountAjax").autocomplete({
                        source: function( request, response ) {
                                $("#accountAjax").after(img_loader_2);                    
                                $.ajax({
                                        url: SITE_URL + 'admin/accounts/accounts_autocomplete_ajax',
                                        dataType: "json",
                                        data: {
                                                limit: 20,
                                                term: request.term
                                        },
                                        success: function( data ) {
                                                response( $.map( data.accounts, function( item ) {
                                                        return {
                                                                label: item.name + " (" + item.razon_social + ")",
                                                                value: item.name + " (" + item.razon_social + ")",
                                                                accountid: item.account_id                                                              
                                                        }
                                                }));
                                                $('#loader').remove(); 
                                        }
                                });
                        },
                        focus: function( event, ui ) {
                                $( "#accountAjax" ).val( ui.item.label );
                                return false;
                        },                        
                        minLength: 3,
                        select: function( event, ui ) {                                
                                $('input[name="account_id"]').val(ui.item.accountid);  
                        },
                        open: function() {
                                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
                        },
                        close: function() {
                                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                        }
                });


		$( "#CityAjax" ).autocomplete({
			source: function( request, response ) {
				$("#CityAjax").after(img_loader_2); 
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
                $('input[name="CityID"]').val(ui.item.cityid);
                $('input[name="phone_area_code"]').val('+' + ui.item.countryphonecode + '.' + ui.item.cityphonecode);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});                

});


