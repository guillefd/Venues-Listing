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

    function __construct($params)
    {	
    	$this->init_result();
		switch($params['prodcatid'])
		{
			case ALQUILERDESALAS_CATID: 
										require_once 'messaging/prodcatid100/Msg_class.php';					    				
									    break;

			default:					return $this->result;						    
		}				
		ci()->config->load('messaging_'.ENVIRONMENT, TRUE);		
		$params['cfgsettings'] = ci()->config->item('messaging_'.ENVIRONMENT);
		$params['viewid'] = ci()->input->post('dataFviewid');
		//new msg object
		$this->msg = new msg($params);		
	}

	private function init_result()
	{
		$this->result = new stdClass();
		$this->result->response = true;
		$this->result->Error = true;
		$this->result->message = 'initError';
	}

    ////////////////////////////
    //process --------------- //
    ////////////////////////////

	public function process_request()
	{	       
        ci()->form_validation->set_rules($this->msg->validationrules);           
        // Validate the data
        if(ci()->form_validation->run())
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
	            $this->result->response = true;
	            $this->result->Error = true;
	            $this->result->message = validation_errors(); 
			}		
	}


	private function process_message()
	{
		$this->msg->process_message();		
		$this->msg->set_message_queuelist();
		$this->msg->save_message_to_db();							
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
$this->__dump();       	
    	foreach($this->msg->queuelist as $queue)
    	{ 		
    		//insert queue to DB
			//API CALL 
			//save API result
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