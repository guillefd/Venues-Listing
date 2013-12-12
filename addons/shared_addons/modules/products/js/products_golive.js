
//CLOUDIMGQTY (declared in golive.php)
//var processmode (declared in golive.php)
//var VECqueueProcess (declared in golive.php)
var PIndex = 0; // process index
var Nindex; // processed status index
var NindexTop = new Array();
var progressBarDiv = new Array();
var progressBarTop = new Array();
var progressbar = new Array();
var progressLabel = new Array();
var queueTxt = new Array();
var queueIndexComplete = new Array(false, false, false);
var queueComplete = false;
var img_loader = '<img src="' + IMG_PATH + 'indicator.gif" style="float:center; margin-left:10px;" id="loader" alt="" /></div>';

// BUTTON RETURN
function btnReturn_onclick() 
{
    window.location.href = BASE_URL + "admin/products/front";
}



$(document).ready(function(){

	init();

	function init()
	{
		//init Nindex (index of items processed in current process)
		Nindex = 0;		
		// index top for each queue process
		NindexTop[0] = CLOUDIMGQTY; //imagenes a procesar
		NindexTop[1] = 1;  // cantidad de procesos ajax
		NindexTop[2] = 1;  // cantidad de procesos ajax
		//progress bars init
	    progressbar[0] = $( "#cloudimagesProgressbar" ),
	    progressLabel[0] = $( ".cloudimages-progress-label" );
	    progressbar[1] = $( "#cloudimagesProgressbar2" ),
	    progressLabel[1] = $( ".cloudimages-progress-label2" );
	    progressbar[2] = $( "#productProgressbar" ),
	    progressLabel[2] = $( ".product-progress-label" );
	    for(var i=0;i<progressbar.length;i++)
	    {
			//init progressbar
		    progressbar[i].progressbar({
		      	value: false
		    });	    	
			progressbar[i].progressbar("value", 0 );	    	
	    }
	    init_txt_message();
	    //Queue label txt
	    queueTxt[0] = $('#cloudimages-queueTxt');	
	    queueTxt[1] = $('#cloudimages-queueTxt2');
	    queueTxt[2] = $('#product-queueTxt');
	    initProgressbar();	    	
		//run ajax
		run();
	}

	function init_txt_message()
	{
	    //ADD LOADER
	    $("#loader").remove();
	    $('#txtcompleted').html('Procesando la publicación, espere por favor' + img_loader);		
	}

	function initProgressbar()
	{				 		
		//obtener fraccion para aumentar por cada progreso
		progressBarDiv[PIndex] = 100 / NindexTop[PIndex];
		//set queue text in window
		setQueueTxt();
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
				var timer = setTimeout( run, 1000 );
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
        	processMode: processmode,
            process: VECqueueProcess[PIndex],
            index: Nindex,
            draftid: draftID,
            frontid: frontID,
            prodcatid: prodcatID,
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
    	$('#txtcompleted').html('La publicación ha sido cancelada y no se ha completado.');
    	progressLabel[PIndex].text( "Proceso cancelado" ); 
    }

    function run_complete()
    {
    	$("#loader").remove();
    	$('#txtcompleted').html('La publicación se ha realizado con éxito &#10004;');
    	$('#txtcompleted').addClass("blue");
    	$('#btnReturn').attr("class","btn blue");
    	$('#btnReturn').removeAttr("disabled");
    	if(queueComplete = false)
    	{
    		$('#txtcompleted').append(' (error:[queueCompleteFalse]) ');
    	}	
    }    
 

});	