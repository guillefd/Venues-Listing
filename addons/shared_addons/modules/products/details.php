<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Products extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
            'name' => array
                                 (
                                  'en' => 'Publications',
                                  'es' => 'Publicaciones'
                                 ),
			'description' => array
                                 (
                                  'en' => 'Publications: Manager',
                                  'es' => 'Publicaciones: Administrador de publicaciones'
                                 ),
			'frontend'	=> TRUE,
			'backend'	=> TRUE,
			'skip_xss'	=> TRUE,
			//'menu'		=> 'AMR',                   
			'roles' => array(
                              'put_live', 'edit_live', 'delete_live'
                            ),			
			'sections' => array
                              (
                              'front' => array
                                            (
                                            'name' => 'front_list',
                                            'uri' => 'admin/products/front/index/1',
                                            'shortcuts' => array
                                                           (),
                                                    ),                             	
                              'products' => array
                                            (
                                            'name' => 'products_list',
                                            'uri' => 'admin/products/index/1',
                                            'shortcuts' => array
                                                           (
                                                            array
                                                                (
                                                                'name' => 'products_create_title',
                                                                'uri' => 'admin/products/create_index',
                                                                'class' => 'add'
                                                                 ),
                                                           ),
                                                    ), 
                              'spaces' => array
                                              (
                                               'name' => 'spaces:list',
                                               'uri' => 'admin/products/spaces',
                                               'shortcuts' => array
                                                              (
                                                                array
                                                                    (
                                                                    'name' => 'spaces:create_title',
                                                                    'uri' => 'admin/products/spaces/create',
                                                                    'class' => 'add'
                                                                    ),
                                                                ),
                                                        ),                            
                              'locations' => array
                                              (
                                               'name' => 'location:list',
                                               'uri' => 'admin/products/locations',
                                               'shortcuts' => array
                                                              (
                                                                array
                                                                    (
                                                                    'name' => 'location:create_title',
                                                                    'uri' => 'admin/products/locations/create',
                                                                    'class' => 'add'
                                                                    ),
                                                                ),
                                                        ),                                                                                                    
                              '|' => array('name'=>'splitter', 'uri' =>'', 'shortcuts'=>array()),                                                                                                                              
                              'features' => array
                                              (
                                               'name' => 'features:list_title',
                                               'uri' => 'admin/products/features',
                                               'shortcuts' => array
                                                              (
                                                                array
                                                                    (
                                                                    'name' => 'features:create_title',
                                                                    'uri' => 'admin/products/features/create',
                                                                    'class' => 'add'
                                                                    ),
                                                                ),
                                                ), 
                              'categories' => array
                                              (
                                               'name' => 'cat_list_title',
                                               'uri' => 'admin/products/categories',
                                               'shortcuts' => array
                                                              (
                                                                array
                                                                    (
                                                                    'name' => 'cat_create_title',
                                                                    'uri' => 'admin/products/categories/create',
                                                                    'class' => 'add'
                                                                    ),
                                                                ),
                                                        ),                                               
                            
                              ),
                            );
	}

	public function admin_menu(&$menu)
	{
	    $menu['AMR'] = array(
            'Geoworldmap' => 'admin/geoworldmap',	    	
            'Cuentas' => 'admin/accounts',	    	
	        'Publicaciones' => 'admin/products',
	    );
		add_admin_menu_place('lang:cp:nav_AMR', 1);	    
	}


	public function install()
	{
		return TRUE;
	}

	public function uninstall()
	{
        return TRUE;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		/**
		 * Either return a string containing help info
		 * return "Some help info";
		 *
		 * Or add a language/help_lang.php file and
		 * return TRUE;
		 *
		 * help_lang.php contents
		 * $lang['help_body'] = "Some help info";
		*/
		return TRUE;
	}
}

/* End of file details.php */