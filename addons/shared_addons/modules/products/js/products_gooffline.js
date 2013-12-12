
//VECqueueProcess (declared in golive.php)
var PIndex = -1; // process index
var Nindex; // processed status index
var NindexTop = new Array();
var progressBarDiv = new Array();
var progressBarTop = new Array();
var progressbar = new Array();
var progressLabel = new Array();
var queueTxt = new Array();
var queueIndexComplete = new Array(false, false);
var queueComplete = false;
var img_loader = '<img src="' + IMG_PATH + 'indicator.gif" style="float:center; margin-left:10px;" id="loader" alt="" /></div>';

// BUTTON RETURN
function btnReturn_onclick() 
{
    window.location.href = BASE_URL + "admin/products/front/index/" + typeID;
}



$(document).ready(function(){

	//watcher
	$("#btnUnpublish").on('click', function () {
		start_process();
	});

	init();

	function init()
	{
		//init Nindex (index of items processed in current process)
		Nindex = 0;		
		// index top for each queue process
		NindexTop[0] = 1; //imagenes a procesar
		NindexTop[1] = 1;  // cantidad de procesos ajax
		//progress bars init
	    progressbar[0] = $( "#cloudimagesProgressbar" ),
	    progressLabel[0] = $( ".cloudimages-progress-label" );
	    progressbar[1] = $( "#productProgressbar" );
	    progressLabel[1] = $( ".product-progress-label" );
	    for(var i=0;i<progressbar.length;i++)
	    {
			//init progressbar
		    progressbar[i].progressbar({
		      	value: false
		    });	    	
			progressbar[i].progressbar("value", 0 );	    	
	    }
	    //Queue label txt
	    queueTxt[0] = $('#cloudimages-queueTxt');	
	    queueTxt[1] = $('#product-queueTxt');
	    initProgressbar();	    	
	}


	function start_process()
	{
		if(PIndex<0)
		{
			PIndex = 0;
    		$('#btnUnpublish').attr("disabled","disabled");			
    		$('#btnReturn').attr("disabled","disabled");	
	    	init_txt_message();	
			//set queue text in window
			setQueueTxt();	    			
			run();
		}
	}


	function init_txt_message()
	{
	    //ADD LOADER
	    $("#loader").remove();
	    $('#txtcompleted').html('Pasando la publicación a estado OFFLINE, espere por favor' + img_loader);		
	}

	function initProgressbar()
	{				 		
		//obtener fraccion para aumentar por cada progreso
		progressBarDiv[PIndex] = 100 / NindexTop[PIndex];
	}

	function runInit()
	{
		if(PIndex<VECqueueProcess.length-1)
		{
			//continue next Queue
			PIndex++;
			Nindex = 0;
			initProgressbar();	
			run();
		}
		else
			{
				//end queues
				run_complete();			
			}					
	}

	function run()
	{
		//update progressbar
		runprogressbar();
		//conitnue queue
		if(Nindex < NindexTop[PIndex])
		{
			processQueueAjax();
		}
		else
		{			
			//check if actual queue is done
			if(queueIndexComplete[PIndex]==false)
			{
				var timer = setTimeout( run, 100 );
			}
			else
			{
				clearTimeout(timer);
				runInit();
			}					
		}
	}
 

    function runprogressbar()
    {
	    progressbar[PIndex].progressbar({
	      	change: function() {
	        	progressLabel[PIndex].text( progressbar[PIndex].progressbar( "value" ) + "%" );
	      	},
	      	complete: function() {
	        	progressLabel[PIndex].text( "completado" );
	        	queueTxt[PIndex].html('Procesado ' + NindexTop[PIndex] + '/' + NindexTop[PIndex]); 	
	        	queueIndexComplete[PIndex] = true;        	        	
	      	}
	    });    	
    	setQueueTxt();
 		progressBarTop[PIndex] = progressBarDiv[PIndex] * Nindex;
 		progressBarTop[PIndex] = Math.round(progressBarTop[PIndex]);
 		if( (progressBarTop[PIndex] == 1 || isNaN(progressBarTop[PIndex])) && Nindex == 0)
 		{
 			progressBarTop[PIndex] = 50;
 		}
 		if (progressBarTop[PIndex] > 100) progressBarTop[PIndex] = 100;
 		progress();    	

    }

    function progress() {
      	var val = progressbar[PIndex].progressbar( "value" ) || 0;    	
   	    progressbar[PIndex].progressbar("value", val + 1 );  	
      	if ( val < progressBarTop[PIndex] ) {
        	setTimeout( progress, 100 );
      	}
    }


    function processQueueAjax()
    {                                  
        var form_data = {
            process: VECqueueProcess[PIndex],
            index: Nindex,
            frontid: frontID,
            typeid: typeID                   
        };
        $.ajax({
                type: "POST",
                url: SITE_URL + 'products/admin/ajxrunqueue',
                dataType: 'json',
                data: form_data,
                success: function(result){
	                if(result.done == true) 
	                {
		                if(result.finished == true) 
		                {
		                	queueComplete = true;
		                } 	                	
	                	Nindex++;
	                	run();
	                } 	                
	                if(result.done == false) 
	                {
						var r=confirm("Ocurrió un error (" + result.status + "), presiona Aceptar para reintentar.");
						if (r==true)
						{
							run(); 	
						}
						else
						{	
							cancelProcess();
							return false;
						} 
	                }             
	            },
	            error: function(result)
	            {
					var r=confirm("Ocurrió un error de conexion(" + result.status + "), presiona Aceptar para reintentar.");
					if (r==true)
					{
						init(); 	
					}
					else
					{
						cancelProcess();
						return false;
					} 
	            } 	                        
        });     
    }

    function setQueueTxt()
    {
   		queueTxt[PIndex].html('Procesando ' + Nindex + '/' + NindexTop[PIndex]);     	
    }

    function cancelProcess()
    {
    	$("#loader").remove();
    	$('#txtcompleted').html('La baja ha sido cancelada y no se ha completado.');
    	progressLabel[PIndex].text( "Proceso cancelado" ); 
    }

    function run_complete()
    {
    	$("#loader").remove();
    	$('#txtcompleted').html('La publicación se ha puesto OFFLINE correctamente &#10004;');
    	$('#txtcompleted').addClass("blue");
    	$('#btnReturn').attr("class","btn blue");
    	$('#btnReturn').removeAttr("disabled");
    	$('#btnReturn').html("Volver al listado de publicaciones");    	
    	if(queueComplete = false)
    	{
    		$('#txtcompleted').append(' (error:[queueCompleteFalse]) ');
    	}	
    }    
 

});	