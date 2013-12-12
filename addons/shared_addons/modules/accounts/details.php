<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Accounts extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Accounts',
                'es' => 'Cuentas'
			),
			'description' => array(
				'en' => 'Accounts customers and providers + contacts',
				'es' => 'Cuentas de proveedores y clientes + contactos'                            
			),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'AMR', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
			'sections' => array(
				'accounts' => array(
					'name' 	=> 'accounts:accounts',
					'uri' 	=> 'admin/accounts',
                    'shortcuts' => array(
        					'create' => array(
                					'name' 	=> 'accounts:create',
                					'uri' 	=> 'admin/accounts/create',
                					'class' => 'add'
                					)
        					)
				),
				'contacts' => array(
					'name' 	=> 'accounts:contacts',
					'uri' 	=> 'admin/accounts/contacts',
                                        'shortcuts' => array(
							'create' => array(
								'name' 	=> 'accounts:create_contact',
								'uri' 	=> 'admin/accounts/contacts/create',
								'class' => 'add'
								)
							)
						)
				)                                  
		);
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
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */
