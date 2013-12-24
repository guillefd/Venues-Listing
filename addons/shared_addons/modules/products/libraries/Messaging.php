<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Message Class
 *
 * Messages process
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
	public $msgcatlib;
	public $dbcatmodel;
	public $msg;
	public $result;

    function __construct($params)
    {	
    	$this->init_result();
		switch($params['prodcatid'])
		{
			case ALQUILERDESALAS_CATID: 

										$this->dbcatmodel = ci()->load->model('products_frontend_1_m');	
										require_once 'messaging/prodcatid100/Msg_class.php';					    				
									    break;

			default:					return $this->result;						    
		}				
		ci()->config->load('product_'.ENVIRONMENT, TRUE);		
		$params['cfgsettings'] = ci()->config->item('product_'.ENVIRONMENT);
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
        	$this->set_frontitem();
        	if($this->msg->frontitem)
        	{
        		if($this->process_message()=== true)
        		{
		            $data->response = true;
		            $data->Error = false;
		            $data->message = '¡Mensaje enviado!';         			
        		}
        		else
	        		{
			            $data->response = true;
			            $data->Error = true;
			            $data->message = 'Error al enviar el mensaje, vuelve a intentarlo.';   
	        		}
        	}
        	else
	        	{
		            $data->response = true;
		            $data->Error = true;
		            $data->message = 'Error (codigo:itemNotFound)';
	        	}      	
		}
		else
			{
	            $data->response = true;
	            $data->Error = true;
	            $data->message = validation_errors(); 
			}
		return $data;	 			
	}


	private function set_frontitem()
	{		
		$this->msg->set_frontitem_params(ci()->input->post());
		$item = $this->dbcatmodel->MSG_get_item_space($this->msg->frontitemparams);
		$this->msg->set_frontitem($item);
	}


	private function process_message()
	{
		$this->msg->process_message();
		$this->msg->set_dbdata();		
		$this->dbcatmodel->save_message_data($this->msg->dbdata);
		return ( $this->send_email('location') && $this->send_email('sender') ) ;
	}


    public function send_email($target)
    { 
        //config
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        ci()->email->initialize($config);   

        $this->msg->set_msg_body($target); 
$this->__dump();
   
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