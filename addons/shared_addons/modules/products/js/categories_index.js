
// global
var img_loader = '<img src="' + IMG_PATH + 'loader.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';
var img_loader_2 = '<img src="' + IMG_PATH + 'indicator.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';
var target_filter = SITE_URL + 'admin/products/categories/ajax_filter';

$(document).ready(function(){ 
   
    var keyword_filter = $('input[name="f_keywords"]');
    var product_type_filter = $('#f_product_type');    
    $('#btnCancel').attr('class','btn gray');

    //input filter action - keypress
    keyword_filter.keypress(function() {
        call_ajax();
    });       

    //input filter action - keypress
    product_type_filter.change(function() {
        call_ajax();
    }); 

    function call_ajax()
    {
        $('#loader').remove();
        keyword_filter.after(img_loader_2);  
        doAjaxQuery(keyword_filter, product_type_filter, target_filter);         
    }

    /** [doAjaxQuery  * Do ajax request for Index - table view] */
    function doAjaxQuery(keyword_filter, product_type_filter, link)
    {
        var form_data = { 
            f_keywords: keyword_filter.val(),
            f_product_type : product_type_filter.val(),             
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

});