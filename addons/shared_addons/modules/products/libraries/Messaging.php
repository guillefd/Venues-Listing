<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Message Class
 *
 * Manage process and queue sending for each message
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Messaging
{
	public $msg;
	public $result;
	public $messenger;
	public $messenger_domain;
	public $messenger_response;

    function __construct($params)
    {	
    	$this->init_result();
    	define('DIRPATH', 'Messaging/prodcatid'.$params['prodcatid'].'/'); 
		switch($params['prodcatid'])
		{
			case ALQUILERDESALAS_CATID: 
										//Messaging/prodcatid100/Msg_class.php
										require_once DIRPATH.'Msg_class.php';					    				
									    break;

			default:					
					            $this->result->message = 'Error (catid)'; 				
								return;						    
		}			
		//get form view ID
		$params['viewid'] = ci()->input->post('dataFviewid');
		$msgtplclass = DIRPATH.'msgtemplate/Msg_'.$params['viewid'].'_class.php';
		switch($params['viewid'])
		{
			case 'form300query':
								require_once $msgtplclass;	
								break;

			case 'form400quote':
								require_once $msgtplclass;
								break;

			default:
					            $this->result->message = 'Error (class)'; 			
								return;					

		}		
		// CONFIG SETTINGS	
		ci()->config->load('messaging_'.ENVIRONMENT, TRUE);		
		//load messaging setting
		$params['cfgsettings'] = ci()->config->item('messaging_'.ENVIRONMENT);
		//get config settings for form view
		$cfgformfile = 'messaging_prodcatid'.$params['prodcatid'].'_'.$params['viewid'];
		ci()->config->load($cfgformfile, TRUE);	
		$params['cfgsettings_msg'] = ci()->config->item($cfgformfile);	
		//new MSG object
		$this->msg = new msg($params);		
	}

	private function init_result()
	{
		$this->result = new stdClass();
		$this->result->response = true;
		$this->result->Error = true;
		$this->result->message = 'initError';
	}

	private function load_mailgun_api()
	{
		require_once 'addons/shared_addons/libraries/Mailgun/Mailgun.php';
		# Instantiate the client.
		$this->messenger = new Mailgun($this->msg->cfg['mailgun_api']);
		$this->messenger_domain = $this->msg->cfg['mailgun_domain'];
	}

    ////////////////////////////
    //process --------------- //
    ////////////////////////////

	public function process_request()
	{		       
        ci()->form_validation->set_rules($this->msg->validationrules);           
        // Validate the data
        if(ci()->form_validation->run() && $this->msg->run_custom_validation() )
        {
        	$this->msg->set_frontitem();
        	if($this->msg->frontitem)
        	{
        		$this->process_message();
        	}
        	else
	        	{
		            $this->result->response = true;
		            $this->result->Error = true;
		            $this->result->message = 'Error (codigo:itemNotFound)';
	        	}      	
		}
		else
			{
				$custommsg = '';
				foreach ($this->msg->validationcustommessages as $rule => $msg) 
				{
					$custommsg.= '<p>'.$msg.'</p>';
				}				
	            $this->result->response = true;
	            $this->result->Error = true;
	            $this->result->message = validation_errors().$custommsg; 
			}		
	}


	private function process_message()
	{
		$this->msg->process_message();			
		$this->msg->set_message_queuelist();		
		$this->msg->save_message_to_db();	
print_r($this->msg); 
die;								
		if( $this->msg->queuelist>0 )
		{
			$this->run_queues();
            $this->result->response = true;
            $this->result->Error = false;
            $this->result->message = '¡Mensaje enviado!';         			
		}
		else
    		{
	            $this->result->response = true;
	            $this->result->Error = true;
	            $this->result->message = 'Error al enviar el mensaje, vuelve a intentarlo.'; 
    		}		
	}


    public function run_queues()
    { 
        //config
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        ci()->email->initialize($config);

    	$this->messenger_response = array();  	
    	foreach($this->msg->queuelist as $queue)
    	{ 		
	        ci()->email->from($queue['from']);
	        ci()->email->to($queue['to']);
	        ci()->email->subject($queue['subject']);
	        ci()->email->message($queue['html']);        
        	$this->messenger_response[] = ci()->email->send();    		
    	}
    	$this->save_queuelist_to_db();        	
    } 


    public function save_queuelist_to_db()
    {
    	$data = array();
    	$q = array();
    	$i = 0;
		foreach($this->msg->queuelist as $queue)
		{
			$q['msg_id'] = $this->msg->dbinsertedID;
			$q['queuetype'] = $queue['type'];
			$q['from'] = $queue['from'];
			$q['to'] = $queue['to'];
			$q['subject'] = $queue['subject'];
			$q['html'] = $queue['html'];
			$q['sendattempt'] = $this->messenger_response[$i];
			array_push($data, $q);
			$i++;
		}		
		if(!empty($data))
		{
			ci()->db->insert_batch($this->msg->cfg['msgqueuedb'], $data);
		}		    	
    }


	//////////////////////////////
	// AUX -----------------// //
	//////////////////////////////

	/* Testing porpose */
	private function __dump($dump = '')
	{
		print '<pre>'.__FILE__.' <br> '.$dump.'</pre>';
		if(ci()->input->is_ajax_request())
		{
			echo'<pre>';
			print_r($this->msg);
			echo '</pre>';
		}
		else
			{
				var_dump($this->msg);
			}
		die;	
	}

}	