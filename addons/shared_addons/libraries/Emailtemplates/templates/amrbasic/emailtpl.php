<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * template Class
 *
 * Email template
 * 
 *
 * @package			CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author			Guillermo Dova
 * @license			
 * @link			
 */

class Emailtpl
{
	public $folderpath;
	public $sections;
	public $html;

	function __construct($params)
	{	
		$this->folderpath = $params['path'];
		$this->set_sections_and_vars();
	}

	private function set_sections_and_vars()
	{
		//sections and vars per section
		$this->sections = array(
								'opentag'=>array(),
								'hiddenline'=>array('txt1'),
								'header'=>array('logolink', 'logoalt', 'logowidth', 'logoheight', 'logosrc'),
								'bodyintro'=>array('txthello', 'txtintro'),
								'bodymsg'=>array('txtblock'),
								'bodyfooter'=>array('txt1', 'txtbye', 'linkref', 'linktxt'),
								'footer'=>array('txt1', 'linkref', 'linktxt'),								
								'closetag'=>array(),
								);
		//build html sections strings
		$this->build_html_string();
	}


	private function build_html_string()
	{
		$this->html = array();
		foreach ($this->sections as $section => $vars) 
		{
			$filename = $this->folderpath.'section_'.$section.'.php';
			if (file_exists($filename)) 
			{			
				$this->html[$section] = file_get_contents($this->folderpath.'section_'.$section.'.php');
			}
			else
				{
					//template is not complete
					$this->html = false;
					break;	
				}			
		}
	}



}

