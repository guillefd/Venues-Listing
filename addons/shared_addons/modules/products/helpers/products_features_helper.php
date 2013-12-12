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
	            'field' => 'cat_feature_id',
	            'label' => 'lang:features:category_label',
	            'rules' => 'trim|required',
	        ),
	        array(
	            'field' => 'cat_product_id',
	            'label' => 'lang:features:cat_product',
	            'rules' => 'trim|required',
	        ),
	        array(
	            'field' => 'name',
	            'label' => 'lang:features:name',
	            'rules' => 'trim|required',
	        ),
	        array(
	            'field' => 'description',
	            'label' => 'lang:features:description_label',
	            'rules' => 'trim',
	        ),
	        array(
	            'field' => 'usageunit_id',
	            'label' => 'lang:features:usageunit',
	            'rules' => 'trim|required',
	        ),
	        array(
	            'field' => 'value',
	            'label' => 'lang:features:value_label',
	            'rules' => 'trim',
	        ),
	        array(
	            'field' => 'group',
	            'label' => 'lang:features:group_label',
	            'rules' => 'trim',
	        ),
	    );
	}


    /**
     * Array that contains the validation rules
     * @access protected
     * @var array
     */
    function validation_rules_cat_features() 
    {
    	return array(
			        array(
			            'field' => 'name',
			            'label' => 'lang:features:name',
			            'rules' => 'trim|required',
			        ),
			        array(
			            'field' => 'description',
			            'label' => 'lang:features:description',
			            'rules' => 'trim|required',
			        ),
			    );
    }


    function _gen_dropdown_list() 
    {
        $_dd->cat_features_array = ci()->features_categories->gen_dd_array();
        $_dd->usageunit_array = ci()->usageunit->gen_dd_array();
        $_dd->type_array = ci()->product_type->gen_dd_array();        
        $_dd->cat_products_multiarray = ci()->categories->gen_dd_multiarray();
        $_dd->cat_products_array = ci()->categories->gen_dd_array();   
        return $_dd;     
    }


    /**
     * Convierte ID´s de resultado SQL a texto - acepta objeto o arrays de objetos
     * @param result array resultado SQL
     * @return result object 
     */
    function _convertIDtoText($results, $dd_arrays)
    {       
        if(is_array($results))
        {                
            foreach($results as $reg)
            {
                _convertIDtoText_run($reg, $dd_arrays);
            }
        }else
            {
                _convertIDtoText_run($results, $dd_arrays);
            }    
        return $results;              
    }
    
    
    function _convertIDtoText_run(&$reg, $dd_arrays)
    {                
        $reg->cat_feature = $reg->cat_feature_id > 0 ? $dd_arrays->cat_features_array[$reg->cat_feature_id] : '';
        $reg->cat_product = $reg->cat_product_id > 0 ? $dd_arrays->cat_products_array[$reg->cat_product_id] : '';
        $reg->usageunit   = $reg->usageunit_id > 0   ? $dd_arrays->usageunit_array[$reg->usageunit_id] : '';  
    }