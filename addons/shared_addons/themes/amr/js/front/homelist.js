
/* VARS */
		/* MORE VARS LOADES IN home_list_####.php */
		var filtersVals = amr_filters();
		var targetUri = '';	

		function amr_filters()
		{
		    var vec = [];
		    vec['city-area'] = '';			    
		    vec['category'] = '';			
		    vec['category_select'] = new Array();
		    vec['capacity'] = '';
		    vec['layouts'] = new Array();
		    vec['loctypes'] = new Array();
		    vec['facilities'] = new Array();
		    vec['features'] = new Array();
		    vec['page'] = '';		    	
		    return vec;	    		    		    		    
		}


$(document).ready(function(){ 

// FIXED ELEMENTS ----------------------------------------

		//HEADER FIXED POSITION
		$('#header_top').affix({
        	offset: { top: $('#header_top').offset().top }
    	});

		//GMAP FIXED POSITION
		$('#fixedmap').affix({
		    offset: {
		    	top: 0, 
		    	bottom: 430
		    }
    	});

		//FILTER FIXED POSITION
		$('#fixedFilterRow').affix({
		    offset: {
		    	top: 277, 
		    	bottom: 430
		    }
    	});    	

// ------------------------------------------------------

		/* filters */
   		$("#usetype").select2({
   			placeholder: "clic para seleccionar mas opciones"
   		}); 

   		$("#select_category").select2({
   			placeholder: "otras categorias",
   			width: 'copy',
   			maximumSelectionSize: 1   			
   		});

   		$("#select_facilities").select2({
   			placeholder: "clic para seleccionar"   			
   		});

   		$("#select_features").select2({
   			placeholder: "clic para seleccionar"   			
   		});

   		$("[data-toggle='tooltip']").tooltip({
   			html: true,
			container: 'body'   			
   		});

		$('#btnlayoutsinfo').popover({
			html: true,
			placement: 'right',
			trigger: 'hover',
			content: layout_img	
		});   		

		$('#btnlocationtypesinfo').popover({
			html: true,
			placement: 'right',
			trigger: 'hover',
			container: 'body',
			content: $('#location_types_table').html()	
		}); 

		$('#btnusetypesinfo').popover({
			html: true,
			placement: 'auto',
			trigger: 'hover',
			container: 'body',			
			content: $('#usetypes_table').html()	
		}); 

		$('.btn-group').button();		

		//galeries
		$('.carousel').carousel({
			interval: false
		});		

		//init filters state
		initFilters();		


/* retrieve BUTTON values  -------------------------------- */	
	
/* AUX */
		/* save btn selected value in global array */
		function amr_save_btn_value(obj, btnselected)
		{
			filtersVals[btnselected] = obj.childNodes[1].value;
		}

		/* save btn selected value in global array, IF checked->adds, IF unchecked->deletes */
		function amr_save_btn_value_array(objid, btnselected)
		{
			/* state before DOM writes */
			if( $('#' + objid).hasClass( "active" ))
			{
				var index = filtersVals[btnselected].indexOf($('#' + objid + ' input').val());
				if (index > -1) {
				    filtersVals[btnselected].splice(index, 1);
				}
			}				
			else
				{
					filtersVals[btnselected].push($('#' + objid + ' input').val());
				}				
		}

		function amr_add_element_to_auxurifilter(vec, index, filter, value)
		{
			vec[index][filter] = [];
			vec[index][filter].push(value);
			vec['count']++;
			return vec;
		}

		function amr_generate_uri_search()
		{
			/* 
				URI FORMAT
				BASE_URL/ CAT_SLUG + category / CITY_SLUG + area + location_type / +filters
			*/
		    var LOCTYPE_SLUG_ARRAY = $.parseJSON(LOCTYPE_SLUG_json);
			var auxUrifilters = [];
			auxUrifilters['count'] = 0; // counter
			auxUrifilters['filters'] = [];
			targetUri = BASE_URL;	
			/* [segment 1] 'product_category+category/' ------------------------------ */ 
			/* product_category */			
			targetUri+= CAT_SLUG;
			targetUri+= filtersVals['category']!= '' ? '+' + filtersVals['category'] : '' ;
			targetUri+= '/';
			/* [segment 2] 'city+area+location_type/' -------------------------------- */ 
			/* city */
			targetUri+= CITY_SLUG;
			/* aux location_type */
			var locaux = '';		
			if(filtersVals['loctypes'].length > 0 )
			{
				auxUrifilters = amr_add_element_to_auxurifilter(auxUrifilters, 'filters', 'loctypes', filtersVals['loctypes']);	
				/* si solo 1 tipo seleccionado */
				if(filtersVals['loctypes'].length == 1)
				{
					locaux = LOCTYPE_SLUG_ARRAY[filtersVals['loctypes'][0]];
				}
			}
			/* city-area  */
			//si city-AREA y LOCTYPE
			if(filtersVals['city-area'] != '' && locaux!='')
			{
				targetUri+= '+' + filtersVals['city-area'] + '+' + locaux;			
			}
			/* si solo city-area */
			else if(filtersVals['city-area'] != '')
				{
					targetUri+= '+' + filtersVals['city-area'];
				}				
				else
					/* si solo loctype */				
					if(locaux!='')
					{
						targetUri+= '+all+' + locaux;
					}
			targetUri+= '/';
			/* [segment FILTER] --------------------------------------------------------------- */
			//capacity
			if(filtersVals['capacity'] != '')
			{
				auxUrifilters = amr_add_element_to_auxurifilter(auxUrifilters, 'filters', 'capacity', filtersVals['capacity']);			
			}
			//layouts
			if(filtersVals['layouts'].length>0)
			{
				auxUrifilters = amr_add_element_to_auxurifilter(auxUrifilters, 'filters', 'layouts', filtersVals['layouts']);					
			}		
			//facilities
			if(filtersVals['facilities'].length>0)
			{
				auxUrifilters = amr_add_element_to_auxurifilter(auxUrifilters, 'filters', 'facilities', filtersVals['facilities']);						
			}		
			//features
			if(filtersVals['features'].length>0)
			{
				auxUrifilters = amr_add_element_to_auxurifilter(auxUrifilters, 'filters', 'features', filtersVals['features']);			
			}
			//page num
			// if(filtersVals['page']!='')
			// {
			// 	auxUrifilters = amr_add_element_to_auxurifilter(auxUrifilters, 'filters', 'page', filtersVals['page']);						
			// }		
			/* gen filter URI*/
			if(auxUrifilters['count']>0)
			{
				auxcount = auxUrifilters['count'];
				targetUri+= '?';			
				for(filter in auxUrifilters['filters'])
				{
					auxcount = auxcount-1;
					targetUri+= filter + '=';
					for(i=0; i<=auxUrifilters['filters'][filter].length-1; i++)
					{
						targetUri+= auxUrifilters['filters'][filter][i];
						if( i < auxUrifilters['filters'][filter].length-1 )
						{
							targetUri+= ','
						}
					}
					if(auxcount>0)
					{
						targetUri+='&';
					}
				}
			}	
		}

		function amr_loadsearch()
		{
			amr_generate_uri_search();
			window.location.assign(targetUri);
		}	

		/* unifica valores de btn y select */
		function unify_category_value(origin)
		{
			switch(origin)
			{
				case 'category':		/* queda valor BTN, always */
										 $("#select_category").select2("val", "");
										 filtersVals['category_select'] = [];
										break;

				case 'category_select':	/* queda valor select, IF action == choice */
										if(filtersVals['category_select'].length>0)
										{
											$(".category-filter").removeClass('active');
											filtersVals['category'] = filtersVals['category_select'][0];
										}
										else 
											{
												filtersVals['category'] = '';	
											}
										break;
			}
		}			

/* BTN CATEGORY (usetypes) */

		/* Category BTN (usetypes) */
		$('label.btn.category-filter').on("click", function(){
		    var val = amr_save_btn_value( $(this)[0], 'category' );   	
		    unify_category_value('category');
        });

		/* Category SELECT (usetypes) */
		$("#select_category").on("change", function(e) { 
			filtersVals['category_select'] = e.val;
		    unify_category_value('category_select');			
		});        

/* BTN CAPACITY */

		$('label.btn.cap-filter').on("click", function(){       	
		    var val = amr_save_btn_value( $(this)[0], 'capacity' );   	
        });      

/* BTN LOCTYPES */

		$('label.btn.loc-filter').on("click", function(){     
		    var val = amr_save_btn_value_array( $(this)[0].id, 'loctypes' );        	
        });  

/* BTN LAYOUTS */

		$('label.btn.layout-filter').on("click", function(){       	
		    var val = amr_save_btn_value_array( $(this)[0].id, 'layouts' );        	
        });  

/* SELECT2 FACILITIES */
		$("#select_facilities").on("change", function(e) { 
			filtersVals['facilities'] = e.val;
		});

/* SELECT2 SERVICES */
		$("#select_features").on("change", function(e) { 
			filtersVals['features'] = e.val;
		});				


/* BTN FILTER SEARCH */
		$("#btnfiltersearch").on("click", function(e) { 
			amr_loadsearch();
		});




/* init buttons state and vualues */

		function initFilters()
		{

			//filter values json to js object
	    	var FILTER_VALUES = $.parseJSON(FILTER_VALUES_json);
	    	var toggle = false;	

			//btn categoria
			var btngroup = $('label.btn.category-filter');
			filtersVals['category'] = FILTER_VALUES['category'];			
			for (var i = 0; i <= btngroup.length-1; i++) 
			{
				if(FILTER_VALUES['category'] == btngroup[i].childNodes[1].value)
				{
					$('#category-filter-' + i).addClass('active');
					toggle = true;
					break;
				}
			};
			if(FILTER_VALUES['category']!='' && toggle == false)
			{
				$("#select_category").select2("val", FILTER_VALUES['category']);
			}

			//btn capacity
			var btngroup = $('label.btn.cap-filter');
			for (var i = 0; i <= btngroup.length-1; i++) 
			{
				if(FILTER_VALUES['capacity'] == btngroup[i].childNodes[1].value)
				{
					$('#cap-filter-' + i).addClass('active');
					filtersVals['capacity'] = FILTER_VALUES['capacity'];					
					break;
				}
			};

			//btn loctypes
			for (var i = 0; i <= FILTER_VALUES['loctypes'].length-1; i++) 
			{
				$('#loc-filter-' + FILTER_VALUES['loctypes'][i]).addClass('active');		
				filtersVals['loctypes'].push(FILTER_VALUES['loctypes'][i]);
			};

			//btn layouts
			for (var i = 0; i <= FILTER_VALUES['layouts'].length-1; i++) 
			{
				$('#layout-filter-' + FILTER_VALUES['layouts'][i]).addClass('active');
				filtersVals['layouts'].push(FILTER_VALUES['layouts'][i]);
			};

			//select facilities
			filtersVals['facilities'] = FILTER_VALUES['facilities'];
			$("#select_facilities").select2("val", FILTER_VALUES['facilities']);

			//currentpage
			filtersVals['page'] = FILTER_VALUES['page'];
		}


	/* MORE RESULTS --------------------------------------- */	

		$("#btnmoreresults").on("click", function(e) { 
			var uri = get_nextpage_uri();
			doAjaxQuery(uri);
		});

		function doAjaxQuery(uri)
		{
			//disable boton
			$('#btnmoreresults').attr('disabled','disabled');
		    $.ajax({
		        type: "POST",
		        url: uri,
		        dataType: 'json',
		        success: function(data)
		        {
		        	if(data.result == true)
		        	{
		        		process_btn_state(data.pagination);
		        		$('div#amrresulttable:last').after(data.html);
		        		gmap_add_newpage_markers(data.map);
		        		replace_uri_state(uri);
		        		filtersVals['page'] = data.pagination.currentpage;
		           	}
		        	else
			        	{
			        		process_btn_state(false);
			        	}
		        },
		        error: function()
		        {
			        process_btn_state(false);
		        }
		    });         
		}		

		function get_nextpage_uri()
		{
			var currenturi = get_current_uri();
			var currentpagetxt = 'page=' + filtersVals['page'];
			var nextpage = filtersVals['page'] + 1;			
			var nextpagetxt = 'page=' + nextpage;
			if(currenturi.indexOf('?') == -1)
			{
				currenturi+= '?' + nextpagetxt;
			}
			else
			{						
				if(currenturi.indexOf('?page=') > -1)
				{
					currenturi = currenturi.replace(currentpagetxt, nextpagetxt);
				}
				else if(currenturi.indexOf('&page=') > -1)
					{
						currenturi = currenturi.replace(currentpagetxt, nextpagetxt);
					}	
					else if(currenturi.indexOf('page=') > -1)
						{
							currenturi = currenturi.replace(currentpagetxt, nextpagetxt);
						}		
						else
							{
								currenturi+= '&' + nextpagetxt;
							}
			}	
			return currenturi;
		}

		function process_btn_state(pagination)
		{
    		$('#btnmoreresults').removeAttr('disabled');
    		if(pagination.link === '')
    		{
    			$('#btnmoreresults').hide();			
			}
		}


	/* HISTORY API ---------------------------------------- */	

	function get_current_uri()
	{
		var state = History.getState();
		return state.url;
	}

	function get_current_state()
	{
		return History.getState(); 
	}

	function get_page_title()
	{
		return $(document).find("title").text();
	}

	function replace_uri_state(uri)
	{
		var title = get_page_title();
		var index = History.getCurrentIndex();
		nextIndex = index + 1;
		History.replaceState({state:nextIndex}, title, uri);
	}

});