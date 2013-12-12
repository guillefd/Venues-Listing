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
                        'field' => 'type_id',
                        'label' => 'lang:cat_type_label',
                        'rules' => 'trim|required'
                    ),    
                    array(
                        'field' => 'title',
                        'label' => 'lang:cat_title_label'
                    ),              
                    array(
                        'field' => 'description',
                        'label' => 'lang:cat_description_label',
                        'rules' => 'trim|required'
                    ),            
                );
    }


    function _gen_dropdown_list()
    {
        return ci()->product_type->gen_dd_array();
    }