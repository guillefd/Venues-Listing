<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * template Class
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
		$this->set_sections();
	}

	private function set_sections()
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
	}


	private function check_section_params($name, $params)
	{
		if(array_key_exists($name, $this->sections))
		{
			foreach($this->sections[$name] as $param)
			{
				if(!isset($params[$param]))
				{
					$params[$param] = '';
				}
			}
		}
		else
			{
				return false;
			}
	}

	public function build_section($section, $prms)
	{
		$html = '';
		$prm = $this->check_section_params($section, $prm);
		if($prm)
		{
			switch($section)
			{
				case 'opentag': 
						$html = file_get_contents($this->folderpath.'section_'.$section);
						break;

				case 'hiddenline':	
						$html = '<span style="color:#fff;font-size:1px;display:none!important">'.$prms['txt1']
							   .'</span>';	
						break;

				case 'header': 
						$html = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>' 
								.'<tr><td align="left" style="padding:10px 20px 15px 0">'
								.'<a href="'.$prms['logolink'].'" target="_blank">'
								.'<img alt="'.$prms['logoalt'].'" border="0" width="'.$prms['logowidth'].'" height="'.$prms['logoheight'].'" '
								.'src="'.$prms['logosrc'].'"></a>' 
								.'</td></tr>' 
								.'<tr><td width="100%" height="1" style="border-top:solid 1px #e8e8e8;display:block;font-size:1px">&nbsp;</td></tr>'
								.'<tr><td height="30" style="font-size:1px">&nbsp;</td></tr>' 
								.'</tbody></table>';
						break;	

				case 'bodyintro': 
						$html = '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-right:20px"><tbody>'
								.'<tr><td>'
								.'<span style="color:#333333;font-size:14px;font-family:Arial">'.$prms['txthello'].'</span>'
								.'</td></tr>'
								.'<tr><td height="10" style="font-size:1px">&nbsp;</td></tr>'
								.'<tr><td><span style="margin:0;display:block;color:#333333;padding:0 0 5px 0;color:#333333;font-family:Arial;font-size:14px">'.$prms['txtintro'].'</span></td><td></td></tr>'
								.'</tbody></table>';
						break;	

				case 'bodymsg': 
						$html = '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-right:20px">'
								.'<tbody><tr><td colspan="2"><div style="border: 1px solid #ededed;margin:10px;padding:10px;">' 
								.$prms['txtblock']
								.'</div></td></tr><tr><td height="10" style="font-size:1px">&nbsp;</td></tr></tbody></table>';
						break;	

				case 'bodyfooter': 
						$html = '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:40px;"><tbody>'
								.'<tr><td><div style="color:#333333;font-family:Arial;font-size:12px;margin-bottom:25px;">'
								.$prms['txt1'].'</div></td></tr>'
								.'<tr><td><span style="color:#333333;font-family:Arial;font-size:14px">'.$prms['txtbye']
								.'</span><br><span style="color:#333333;font-family:Arial;font-size:14px">'
								.'<a href="'.$prms['linkref'].'">'.$prms['linktxt'].'</a></span></td></tr>'
								.'<tr><td height="15" style="font-size:1px">&nbsp;</td></tr></tbody></table>';
						break;	

				case 'footer': 
						$html = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>'
								.'<tr><td width="100%" style="border-bottom:solid 1px #e8e8e8;display:block;padding-top:5px">'
								.'</td></tr>'
								.'<tr><td style="padding:10px 0 0 0"><span style="color:#999999;font-size:11px;font-family:Arial">'
								.$prms['txt1'].'<a href="'.$prms['linkref'].'" target="_top">'.$prms['linktxt'].'</a>.'
								.'</span></td></tr><tr><td height="30" style="font-size:1px">&nbsp;</td></tr></tbody></table>';
						break;	

				case 'closetag': 
						$html = '</div>';
						break;						
			}
		}
		return $html;
	}




}

