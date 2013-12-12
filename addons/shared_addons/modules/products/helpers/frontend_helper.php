<?php defined('BASEPATH') OR exit('No direct script access allowed');



////////////////////////////
// AUX --------------- // //
////////////////////////////


	function round_number($num)
	{
		$num = round($num,-2);
		$num = $num == 0 ? 100 : $num;
		return $num;
	}
