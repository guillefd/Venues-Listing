<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Array that contains the validation rules
 * @access protected
 * @var array
 */
function validation_rules()
{
	return array(
				array(
	                'field' => 'account_id',
	                'label' => 'lang:location:account_label',
	                'rules' => 'trim|required|callback__check_validAccountId',
	            ),
				array(
	                'field' => 'account',
	                'label' => 'lang:location:account_label',
	                'rules' => 'trim',
	            ),            
		        array(
					'field' => 'name',
					'label' => 'lang:location:name_label',
					'rules' => 'trim|required|max_length[40]|callback__check_name'
				),
				array(
					'field' => 'slug',
					'label' => 'lang:location:slug_label',
					'rules' => 'trim|required|max_length[40]|callback__check_slug'
				),  
				array(
					'field' => 'location_type_id',
					'label' => 'lang:location:type_label',
					'rules' => 'trim|required|numeric'
				),  				          
				array(
					'field' => 'intro',
					'label' => 'lang:location:intro_label',
					'rules' => 'trim'
				),            
				array(
					'field' => 'description',
					'label' => 'lang:location:description_label',
					'rules' => 'trim|required'
				),            
				array(
					'field' => 'address_l1',
					'label' => 'lang:location:address_label',
					'rules' => 'trim|required'
				),  
				array(
					'field' => 'address_l2',
					'label' => 'lang:location:address_label',
					'rules' => 'trim'
				),  
				array(
					'field' => 'geo_street_name',
					'label' => 'lang:location:address_label',
					'rules' => 'trim'
				),  
				array(
					'field' => 'geo_street_number',
					'label' => 'lang:location:address_label',
					'rules' => 'trim'
				),  				          
				array(
					'field' => 'City',
					'label' => 'lang:location:city_label',
					'rules' => 'trim'
				),                    
				array(
					'field' => 'CityID',
					'label' => 'lang:location:city_label',
					'rules' => 'trim|required|numeric'
				), 
				array(
					'field' => 'area',
					'label' => 'lang:location:area_label',
					'rules' => 'trim'
				),    
				array(
					'field' => 'zipcode',
					'label' => 'lang:location:zipcode_label',
					'rules' => 'trim'
				),             
				array(
					'field' => 'Latitude',
					'label' => 'lang:location:latitude_label',
					'rules' => 'trim'
		                    ),
				array(
					'field' => 'Longitude',
					'label' => 'lang:location:longitude_label',
					'rules' => 'trim'
		                    ),
				array(
					'field' => 'latlng_precision',
					'label' => 'lang:location:latlng_precision_label',
					'rules' => 'trim'
		                    ),            
				array(
					'field' => 'phone_area_code',
					'label' => 'lang:location:phonearea_label',
					'rules' => 'trim'
				),              
				array(
					'field' => 'phone',
					'label' => 'lang:location:phone_label',
					'rules' => 'trim'
				),  
				array(
					'field' => 'fax',
					'label' => 'lang:location:fax_label',
					'rules' => 'trim'
				),
				array(
					'field' => 'mobile',
					'label' => 'lang:location:mobile_label',
					'rules' => 'trim'
				),            
				array(
					'field' => 'email',
					'label' => 'lang:location:email_label',
					'rules' => 'trim'
				),             
				array(
					'field' => 'chatSocial_accounts',
					'label' => 'lang:location:chatSocial_label',
					'rules' => 'trim'
				),                  
				array(
					'field' => 'type',
					'rules' => 'trim|required'
				),            
			);
}


function gen_dropdown_arrays()
{
	//Gen dropdown list for social
    $_dd->social = ci()->social->get_list();
    $_dd->locations_type = ci()->locations_type->gen_dd_array();   
    return $_dd;
}


/**
 * Convierte ID´s de resultado SQL a texto - acepta objeto o arrays de objetos
 * @param result array resultado SQL
 * @return result object 
 */
function _convertIDtoText($results, $dd_array)
{  
    if(is_array($results))
    {                
        foreach($results as $reg)
        {
            _convertIDtoText_run($reg, $dd_array);
        }
    }else
        {
            _convertIDtoText_run($results, $dd_array);
        }              
    return $results;              
}

function _convertIDtoText_run(&$reg, $dd_array)
{          
    //Use Geoworldmap library - nombre de la ciudad
    $city = ci()->geoworldmap->getCityByID($reg->CityID);
    $reg->City = $city ? $city->City : '';                
    //nombre de la cuenta
    $account = ci()->accounts->get_account($reg->account_id);
    $reg->account = $account ? $account->name : '';  
    //tipo de locacion
    $reg->location_type = $dd_array->locations_type[$reg->location_type_id];      
} 


/**
 * Formats values for correct view in index
 * @param type $result
 * @return type 
 */
function _formatValuesForView($result)
{
    foreach($result as $reg)
    {
        $reg->intro = substr($reg->intro, 0, 110);
        $reg->intro = wordwrap($reg->intro, 60, "<br />\n");
    }
    return $result;
}  


/**
 * [gen_location_slug description]
 * @param  string $street [description]
 * @param  string $number [description]
 * @return [type]         [description]
 */
function gen_location_slug($street = "", $number = "", $location_name = "", $id = 0)
{
	if( empty($address) && empty($number) )
	{
		if(!empty($location_name))
		{
			$slug = slugify_string($location_name);
		}
		else
			{
				return '';	
			}
	}
	else
	{
	    $num = round_number($number);		
		$slug = slugify_string($street.' '.$num);		
	}	
	$slug = check_location_slug($slug, $id);
	return $slug;
}     


/**
 * [round_address_number description]
 * @param  string $number [description]
 * @return [type]         [description]
 */
function round_number($number="")
{
	if(strlen($number)>2)
	{
		$digits = 2;
	}
	else
    	{
 			if(strlen($number)>1)
 			{
 				$digits = strlen($number)-1;
 			}   
 			else
 			{
 				return $number;	
 			}		
    	} 	
	return round($number, -($digits));
}


/**
 * [slugify_string description]
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function slugify_string($text) {
	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	// trim
	$text = trim($text, '-');	
	// transliterate
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	// lowercase
	$text = strtolower($text);	
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);	
	if (empty($text))
	{
		return 'n-a';
	}
    return trim($text, '-');
} 


	/**
	 * Callback method that checks the slug_location
	 * @access public
	 * @param string title The title to check
	 * @return bool
	 */
	function check_location_slug($slug = '', $id = 0)
	{	
        if (ci()->products_locations_m->check_location_slug($slug))
        {    	
            if($id>0) //editing location
            {                           	
               //Check slug does not exist in DB and ID not equal to ID of current edited record
               if(ci()->products_locations_m->check_location_slug_edited($slug, $id))
               {                   	
					$i = 1;
					$slug = $slug.'_'.$i; 
					while(ci()->products_locations_m->check_location_slug_edited($slug, $id))
					{
						$i++;	
						$slug = $slug.$i; 								
					}
               }              
            }
            else
	            {
					$i = 1;
					$slug = $slug.'_'.$i; 
					while(ci()->products_locations_m->check_location_slug($slug))
					{
						$i++;	
						$slug = $slug.$i; 								
					}	            	
	            }               
        }        
        return $slug;
	}  