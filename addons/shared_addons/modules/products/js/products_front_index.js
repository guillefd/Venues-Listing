var img_loader_2 = '<img src="' + IMG_PATH + 'indicator.gif" style="float:right; margin:5px;" id="loader" alt="" /></div>';


function doAjaxQuery(data)
{
   $('#loader').remove();
   $('#btnCancel').after(img_loader_2);     
   var form_data = {
        f_keywords: data.keyword.val(),
    };

    $.ajax({
        type: "POST",
        url: data.link,
        data: form_data,
        dataType: 'html',
        success: function(result){
            $('#btnCancel').attr('class','cancel btn orange');            
            //replace Table with new values 
            $('#indexView').replaceWith(result);            
            // remove GIF
            $('#loader').remove();                          
        },
        error: function()
        {
            $('#loader').remove(); 
        }
    });         
}

function set_button_stateview()
{
    switch(STATEVIEW)
    {
        case 'all':     
                        $('#btnAllview').attr("class","btn blue");
                        break;

        case 'update':  
                        $('#btnUpdateview').attr("class","btn blue");
                        break;

        case 'offline': 
                        $('#btnOfflineview').attr("class","btn blue");
                        break;

        case 'requestaction': 
                        $('#btnRequestactionview').attr("class","btn blue");
                        break;

    } 
}


$(document).ready(function(){ 
   
        set_button_stateview();

        var f_data = new Object();
        f_data.keyword = $('input[name="f_keywords"]');              
        f_data.link = SITE_URL + 'admin/products/front/index/' + TYPEID + '/' + STATEVIEW + '/';
        $('#btnCancel').attr('class','btn gray cancel');
   
        //WATCHERS - ACTIVATE FILTER
        f_data.keyword.keyup(function() {
            if(f_data.keyword.val().length > 2)
            {
                doAjaxQuery(f_data);       
            }
        });  

        //STATEVIEW BTN
        $('#btnAllview').on("click", function(){
            loadstateview('all');
        });      
        $('#btnUpdateview').on("click", function(){
            loadstateview('update');
        });  
        $('#btnOfflineview').on("click", function(){
            loadstateview('offline');
        }); 
        $('#btnRequestactionview').on("click", function(){
            loadstateview('requestaction');
        });          

        function loadstateview(view)
        {
            window.location.assign(BASE_URL + 'admin/products/front/index/' + TYPEID + '/' + view + '/');
        }              

     

});        