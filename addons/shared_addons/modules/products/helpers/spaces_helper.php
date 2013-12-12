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
	        'field' => 'location',
	        'label' => 'lang:spaces:location',
	        'rules' => 'trim',
	        ),            
	    array(
	        'field' => 'location_id',
	        'label' => 'lang:spaces:location',
	        'rules' => 'trim|required|callback__check_validLocationId',
	        ),
	    array(
	        'field' => 'denomination',
	        'label' => 'lang:spaces:denomination',
	        'rules' => 'trim',
	        ),             
	    array(
	        'field' => 'denomination_id',
	        'label' => 'lang:spaces:denomination',
	        'rules' => 'trim|required',
	        ),            
	    array(
	        'field' => 'name',
	        'label' => 'lang:spaces:name',
	        'rules' => 'trim|required|callback__check_name',
	        ),
	    array(
	        'field' => 'description',
	        'label' => 'lang:spaces:description',
	        'rules' => 'trim',
	        ),            
	    array(
	        'field' => 'level',
	        'label' => 'lang:spaces:level',
	        'rules' => 'trim',
	        ),            
	    array(
	        'field' => 'width',
	        'label' => 'lang:spaces:width',
	        'rules' => 'trim',
	        ), 
	    array(
	        'field' => 'length',
	        'label' => 'lang:spaces:length',
	        'rules' => 'trim',
	        ), 
	    array(
	        'field' => 'height',
	        'label' => 'lang:spaces:heigth',
	        'rules' => 'trim',
	        ),             
	    array(
	        'field' => 'square_mt',
	        'label' => 'lang:spaces:square_mt',
	        'rules' => 'trim',
	        ),            
	    array(
	        'field' => 'shape_id',
	        'label' => 'lang:spaces:shape',
	        'rules' => 'trim',
	        ),            
	    array(
	        'field' => 'layouts',
	        'label' => 'lang:spaces:layouts',
	        'rules' => 'trim',
	        ),            
	    array(
	        'field' => 'facilities',
	        'label' => 'lang:spaces:facilities',
	        'rules' => '',
	        ),  
	    array(
	        'field' => 'usetypes',
	        'label' => 'lang:spaces:usetypes',
	        'rules' => 'required',
	        )  	                                        
	    );
}


function _gen_dropdown_list()
{
    $_dd->denominations_array = ci()->spaces_denominations->gen_dd_array();
    $_dd->shapes_array = ci()->shapes->gen_dd_array();            
    $_dd->layouts_array = ci()->layouts->gen_dd_array();            
    $_dd->facilities_array = ci()->facilities->gen_dd_array();
    $_dd->usetypes_array = ci()->spaces_usetype->gen_dd_array();    
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
    if($location = ci()->products->get_location($reg->location_id))
    {    
//nombre de la cuenta
        $account = ci()->accounts->get_account($location->account_id);
//Use Geoworldmap library - nombre de la ciudad
        $city = ci()->geoworldmap->getCityByID($account->CityID);
        $reg->location_extended = $location->name.' [ '.$account->name.' ]';
        $reg->account = $account->name;
        $reg->location = $location->name;
        $reg->address = $location->address_l1.' '.$location->address_l2;
        $reg->area = $location->area;
        $reg->city = $city->City;
    }
    else
    {
        $reg->location = " --- ";
    }     
    $reg->denomination = $reg->denomination_id > 0 ? $dd_array->denominations_array[$reg->denomination_id] : '';
    $reg->shape = $reg->shape_id > 0 ? $dd_array->shapes_array[$reg->shape_id] : '';
    $reg->layouts_txt = convertLayoutsToText($reg->layouts, $dd_array);
}       

function convertLayoutsToText($string, $dd_array)
{
    $txt = "";            
    if (!empty($string))
    {
        $strvecs = explode(';',$string);
        foreach($strvecs as $vec)
        {
            if(!empty($vec))
            {
                $reg = explode(',',$vec);
                $txt.= $dd_array->layouts_array[$reg[0]].': '.$reg[1].'<br>';
            }
        }

    }
    return $txt;
}

function convertFacilitiesToText($array, $dd_array)
{
    $txt = '<table><th colspan="2">'.lang('spaces:facilities').'</th>';
    foreach($dd_array->facilities_array as $key => $subVec)
    {
        $txt.='<tr><td>'.$key.': </td><td>';
        foreach($subVec as $skey =>$reg)
        {
            $txt.= in_array($skey, $array) ? ' ['.$reg.']' : '';
        }
        $txt.='</td></tr>';
    }
    $txt.='</table>';
    return $txt;
}

function convertUseTypesToText($array, $dd_array)
{
    $txt = '<table><th>'.lang('spaces:usetypes').'</th><tr><td>';
    foreach($array as $usetype_id)
    {
        if(array_key_exists($usetype_id, $dd_array->usetypes_array))
        {	
        	$txt.='['.$dd_array->usetypes_array[$usetype_id].'] ';
    	}
    }
    $txt.='</td></tr></table>';
    return $txt;
}

function unserialize_and_json_spaces_usetypes($array = array() )
{
	if(empty($array)) return $array;
	foreach ($array as $space) 
	{
		$space->usetypes = json_encode(unserialize($space->usetypes));
	}
	return $array;
}