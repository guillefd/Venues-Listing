// global
var img_loader = '<img src="' + IMG_PATH + 'loader.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';
var img_loader_2 = '<img src="' + IMG_PATH + 'indicator.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';

var input_seller_account = $('input[name="seller_account"]');

var vecL = new Array();
var vecS = new Array();
var usetypeSelectOptions;

$(document).ready(function(){

    // Init location and space dropdown    
    init_locations_and_spaces_selects();
    //init seller account input
    init_seller_account_input();
    //init seller account input
    init_basic_publication();


// watchers - triggers :::::::::::::::::::::::::::::::::::::::::
 
    $('#product_chk_seller_account').click(function() {
        toggle_seller_account_input();
    }); 

    $('#chk_basic_publication').click(function() {
        toggle_basic_publication();
    });

    //autocomplete publication name
    $('select[name="space_usetype_id"]').change(function() {        
        var usetype = SPACES_USETYPE_ARRAY[$('select[name="space_usetype_id"]').val()];
        var location = get_vecL_location( $('select[name="location_id"]').val() );
        var space = get_vecS_space( $('select[name="space_id"]').val() );
        var spacedenom = typeof space.denomination != 'undefined' ? space.denomination : '';         
        var locname = typeof location.slug != 'undefined' ? location.slug : '';         
        var spacename = typeof space.name != 'undefined' ? space.name : '';    
        var txt = 'Alquiler de ' + spacedenom + ' - '+ usetype + ' - ' + spacename + '@' + locname;
        $('input[name="name"]').val(txt);
    });         

    function get_vecL_location(id)
    {
        for (var i = vecL.count - 1; i >= 0; i--) 
        {
            if(vecL.locations[i].id == id)
            {
                return vecL.locations[i];
            }
        };
    }

    function get_vecS_space(id)
    {
        for (var i = vecS.count - 1; i >= 0; i--) 
        {
            if(vecS.spaces[i].space_id == id)
            {
                return vecS.spaces[i];
            }
        };
    }

    function init_seller_account_input()
    {
        if(CHK_SELLER_ACCOUNT == 0 || CHK_SELLER_ACCOUNT == '')
        {
            $('#seller_accountAjax').attr('disabled','disabled');
        }
        if(CHK_SELLER_ACCOUNT ==1)
        {
            $('#seller_accountAjax').removeAttr('disabled');
        }
    }

    function init_basic_publication()
    {
        toggle_basic_publication();      
    }

    function toggle_seller_account_input()
    {
        if( $('#product_chk_seller_account').is(':checked') )
        {
            $('#seller_accountAjax').removeAttr('disabled');
            $('#seller_accountAjax').focus();
        }
        else
        {
            $('#seller_accountAjax').attr('disabled','disabled');
        }
    }

    function toggle_basic_publication()
    {
        if( $('#chk_basic_publication').is(':checked') )
        { 
            $('.basicTargetFields').addClass('UsetypeDisabledOption'); 
        }
        else
            {
                $('.basicTargetFields').removeClass('UsetypeDisabledOption');          
            }       
    }    

    function init_locations_and_spaces_selects()
    {
        if( $('input[name="account_id"]').val()>0 )
        {
            request_locations(true);
            if( LOCATIONID > 0 )
            {
                request_spaces(true);           
            }                    
        }
    }

    function init_space_usetype_select()
    {
        if( SPACEID > 0)
        {
            //copy spaces usetype to selector
            var usetypes_html = gen_space_usetype_dd(SPACEID, true);
            update_space_usetype_select(usetypes_html);
        }          
    }        

    // needed so that Keywords can return empty JSON
    $.ajaxSetup({
        allowEmpty: true
    });

    // tags input
    $('#keywords').tagsInput({
        autocomplete_url:'admin/keywords/autocomplete'
    });
        
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


   //watcher location dropdown
   $('select[name="location_id"]').change(function() {
        if( $(this).val()!="")
        {    
            request_spaces();    
        }
   });


   //watcher location dropdown
   $('select[name="space_id"]').change(function() {
        if( $(this).val()!="")
        {    
            //copy spaces usetype to selector
            var usetypes_html = gen_space_usetype_dd($(this).val());
            update_space_usetype_select(usetypes_html);
        }
   }); 


// functions :::::::::::::::::::::::::::::::::::::::::

    function update_space_usetype_select(options)
    {
        $('select[name="space_usetype_id"]').html(options);    
        $('select[name="space_usetype_id"]').trigger("liszt:updated");          
    }
    
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
                        if(item.razon_social=="") { 
                                                    var razonsocial = ''; 
                                                  }
                                                  else{
                                                        razonsocial = ' (' + item.razon_social + ')';   
                                                      }   
                        return {
                                label: item.name + razonsocial,
                                value: item.name + razonsocial,
                                accountid: item.account_id                                                              
                                }
                            })
                        );
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
            request_locations();  
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });
     

    $("#seller_accountAjax").autocomplete({
        source: function( request, response ) {
            $("#seller_accountAjax").after(img_loader_2);                    
            $.ajax({
                url: SITE_URL + 'admin/accounts/accounts_autocomplete_ajax',
                dataType: "json",
                data: {
                    limit: 20,
                    term: request.term
                },
                success: function( data ) {
                        response( $.map( data.accounts, function( item ) {
                        if(item.razon_social=="") { 
                                                    var razonsocial = ''; 
                                                  }
                                                  else{
                                                        razonsocial = ' (' + item.razon_social + ')';   
                                                      }   
                        return {
                                label: item.name + razonsocial,
                                value: item.name + razonsocial,
                                accountid: item.account_id                                                              
                                }
                            })
                        );
                        $('#loader').remove(); 
                }
            });
        },
        focus: function( event, ui ) {
            $( "#seller_accountAjax" ).val( ui.item.label );
            return false;
        },                        
        minLength: 3,
        select: function( event, ui ) {                                
            $('input[name="seller_account_id"]').val(ui.item.accountid);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });


    function request_locations(init)
    {
        init = (typeof init === "undefined") ? false : init; 
        //reset SPACES selector
        $('select[name="space_id"]').html('');
        //trigger que dispara la actualizacion del dropdown CHOSEN
        $('select[name="space_id"]').trigger("liszt:updated");              
        //img loader
        $('select[name="location_id"]').after(img_loader_2);                        
            var form_data = {
                limit: 20,
                account_id: $('input[name="account_id"]').val()                    
            };
            $.ajax({
                    type: "POST",
                    url: SITE_URL + 'admin/products/locations_by_accountid_ajax',
                    dataType: 'json',
                    data: form_data,
                    success: function(result){
                        // quita el GIF
                        $('#loader').remove();
                    if(result.status == 'OK') 
                    {
                        var options;
                        vecL = result;
                        // valor vacio a proposito - para js-chosen
                        if(result.count < 1)
                        {
                            options = '<option value="">' + MSG_QUERY_EMPTY + '</option>';
                        }
                        if(result.count > 0)
                        {
                            options = '<option value="">' + MSG_SELECT + '</option>';
                            for (var i = 0; i < vecL.locations.length; i++) 
                            {
                                options += '<option value="' + vecL.locations[i].id + '" ';
                                if(init && vecL.locations[i].id == LOCATIONID) {  options += ' selected="selected"'; } 
                                options += '>' + vecL.locations[i].name + '</option>';
                            }
                        }
                    } 
                    if(result.status != 'OK') 
                    {
                        options = '<option value="">' + MSG_QUERY_FEATURES_FAIL + '</option>'; 
                    }
                    //copy html to selector
                    $('select[name="location_id"]').html(options);
                    //trigger que dispara la actualizacion del dropdown CHOSEN
                    $('select[name="location_id"]').trigger("liszt:updated");               
                },
                error: function()
                {
                    $('#loader').remove();   
                }            
        });     
    }


    function request_spaces(init)
    {
        init = (typeof init === "undefined") ? false : init; 
        //img loader
        $('select[name="space_id"]').after(img_loader_2); 
        if(init)
        {
            locID = LOCATIONID;
        }
        else
            {
                locID = $('select[name="location_id"]').val(); 
            }                       
            var form_data = {
                limit: 20,
                location_id: locID               
            };
            $.ajax({
                    type: "POST",
                    url: SITE_URL + 'admin/products/spaces_by_locationid_ajax',
                    dataType: 'json',
                    data: form_data,
                    success: function(result){
                        // quita el GIF
                        $('#loader').remove();
                    if(result.status == 'OK') 
                    {
                        var options;
                        vecS = result;
                        // valor vacio a proposito - para js-chosen
                        if(result.count < 1)
                        {
                            options = '<option value="">' + MSG_QUERY_EMPTY + '</option>';
                        }
                        if(result.count > 0)
                        {
                            options = '<option value="">' + MSG_SELECT + '</option>';
                            for (var i = 0; i < vecS.spaces.length; i++) 
                            {
                                options += '<option value="' + vecS.spaces[i].space_id + '" ';
                                if(init && vecS.spaces[i].space_id == SPACEID) {  options += ' selected="selected" '; } 
                                options += '>' + vecS.spaces[i].name + '</option>';
                            }
                        }
                    } 
                    if(result.status != 'OK') 
                    {
                        options = '<option value="">' + MSG_QUERY_EMPTY + '</option>'; 
                    }
                    //copy spaces list to selector
                    $('select[name="space_id"]').html(options);
                    //trigger que dispara la actualizacion del dropdown CHOSEN
                    $('select[name="space_id"]').trigger("liszt:updated");  
                    if(init)
                    {
                        //init space usetype dropdown
                        init_space_usetype_select();                          
                    }                                                     
                },
                error: function()
                {
                    $('#loader').remove();   
                }            
        });     
    }


    function gen_space_usetype_dd(space_id, init)
    {
        init = (typeof init === "undefined") ? false : init;         
        if(vecS.count > 0)
        {
            options = '<option value="">' + MSG_SELECT + '</option>';
            for (var i = 0; i < vecS.spaces.length; i++) 
            {
                if(vecS.spaces[i].space_id == space_id)
                {
                    var usetypes = JSON.parse(vecS.spaces[i].usetypes);
                    for (var j = 0; j < usetypes.length; j++)
                    {
                        if(SPACES_USETYPE_ARRAY[usetypes[j]] != "")
                        {
                            options += '<option value="' + usetypes[j] + '" ';
                            if(init && usetypes[j] == SPACE_USETYPEID) {  options += ' selected="selected"'; }                             
                            options += '>' + SPACES_USETYPE_ARRAY[usetypes[j]] + '</option>';
                        }
                    }    
                    break;
                }
            }
        }
        else
            {
                options = '<option value="">' + MSG_QUERY_EMPTY + '</option>';            
            } 
        return options;           
    }



    //MODAL - ajax create: categories
    $('#products-options-tab ul li:first a').colorbox({
        srollable: false,
        innerWidth: 600,
        innerHeight: 280,
        href: SITE_URL + 'admin/products/categories/create_ajax',
        onComplete: function() {
            $.colorbox.resize();
            $('form#categories').removeAttr('action');
            $('form#categories').live('submit', function(e) {
                var form_data = $(this).serialize();
                $.ajax({
                    url: SITE_URL + 'admin/products/categories/create_ajax',
                    type: "POST",
                    data: form_data,
                    success: function(obj) {
                        if(obj.status == 'ok') {
                           //succesfull db insert do this stuff
                            var select = 'select[name=category_id]';
                            var opt_val = obj.category_id;
                            var opt_text = obj.title;
                            var option = '<option value="'+opt_val+'" selected="selected">'+opt_text+'</option>';
								
                            //append to dropdown the new option
                            $(select).append(option);
																
                            // TODO work this out? //uniform workaround
                            $('#products-options-tab li:first span').html(obj.title);
								
                            //close the colorbox
                            $.colorbox.close();
                        } else {							
                            //append the message to the dom
                            $('#cboxLoadedContent').html(obj.message + obj.form);
                            $('#cboxLoadedContent p:first').addClass('notification error').show();
                        }
                    }
                });
                e.preventDefault();
            });				
        }
    });


}); // end document jQuery(document).ready