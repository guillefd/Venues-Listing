<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Geoworldmap Class
 *
 * Info of Countrys, regions, cities, locations + webservices
 *
 * @package			CodeIgniter
 * @subpackage                  Libraries
 * @category                    Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Geoworldmap
{
  
    /**
        * Constructor - Sets loads and vars
        *
        */
    function __construct()
    {
        //global object    
        $this->t_countries = 'geo_countries'; 
        $this->t_cities = 'geo_cities';
            
    }    
    
    /**
     * Devuelve array de paises
     * @return type array 
     */
    public function all_countries()
    {
        $countries = array();
        ci()->db->order_by('Country', 'ASC');
        if ($result = ci()->db->get($this->t_countries)->result())
        {
                foreach ($result as $country)
                {
                        $countries[$country->CountryId] = $country->Country;
                }
        }        
        return $countries;        
    }
    

    /**
     * Devuelve objeto Ciudad (datos completos)
     * @param type integer $id 
     * @return type object
     */    
    public function getCityByID($id, $bool = false)
    {
        $q = ci()->db->get_where($this->t_cities, array('CityID' => $id));      
        if($q->num_rows()>0)
        {
            $data = $q->row();
        }
        else
        {
            if($bool)
            {
                $data = false;
            }
            else
            {
                $data->City = "";
            }    
        }        
        return $data;
    }    


    /**
     * Devuelve objeto Country (datos completos)
     * @param type integer $id 
     * @return type object
     */    
    public function getCountryByID($id, $bool = false)
    {
        $q = ci()->db->get_where($this->t_countries, array('CountryId' => $id));      
        if($q->num_rows()>0)
        {
            $data = $q->row();
        }
        else
        {
            if($bool)
            {
                $data = false;
            }
            else
            {
                $data->Country = "";
            }    
        }        
        return $data;
    } 

    /**
     * [staticmap generate static map image]
     * @param  integer $lat    [latitude]
     * @param  integer $lng    [longitud]
     * @param  string  $zoom   [zoom]
     * @param  string  $width  [image width]
     * @param  string  $height [image height]
     * @return [image]         [image]
     */
    public function staticmap($lat = 0, $lng = 0, $zoom = '15', $width = '350', $height = '250')
    {
        if($lat != 0 && $lng != 0)
        {    
           return '<img src="http://maps.google.com/maps/api/staticmap?center='.
                  $lat.','.$lng.'&zoom='.$zoom.'&size='.$width.'x'.$height.
                  '&markers=color:blue%7Clabel:S%7C'.$lat.'+'.$lng.
                  '&sensor=false"'.                  
                  '/>';   
        }
        else
            {
                return '<span>Insuficient data!</span>';
            }
    }
}

/* End of file XXX.php */
/* Location: ./application/controllers/XXX.php */
