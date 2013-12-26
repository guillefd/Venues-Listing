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
$this->__dump();		
		if( $this->msg->queuelist )
		{
            $this->result->response = true;
            $this->result->Error = false;
            $this->result->message = '¡Mensaje enviado!';         			
		}
		else
    		{
	            $this->result->response = true;
	            $this->result->Error = true;
	            $this->result->message = 'Error al enviar el mensaje, vuelve a intentarlo.';
	            //set SENDATTEMPT to false in database row   
    		}		
	}


    public function run_queues()
    { 
        //config
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        ci()->email->initialize($config);   

        switch($target)
        {       	
            case 'location':
                        //message
                        $from      = $this->msg->data['sender_email'];
                        $from_name = $this->msg->data['sender_name'];
                        $reply_to  = $this->msg->data['sender_email'];
                        $to        = $this->msg->data['account_agent_email'];
                        $subject   = $this->msg->data['subject'];
                        $body      = $this->msg->data['body']; 
                        break;

            case 'sender':
                        //message
                        $from      = $this->msg->cfg['emailparams']['amrfromaddress'];
                        $from_name = $this->msg->cfg['emailparams']['amrfromname'];
                        $reply_to  = $this->msg->cfg['emailparams']['amrreplyto'];
                        $to        = $this->msg->data['sender_email'];
                        $subject   = $this->msg->data['subject'];
                        $body      = $this->msg->data['body'];  
                        break;                        
        }        
        //send
        ci()->email->from($from, $from_name);
        ci()->email->reply_to($reply_to);
        ci()->email->to($to);
        ci()->email->subject($subject);
        ci()->email->message($body);        
        return ci()->email->send();        
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