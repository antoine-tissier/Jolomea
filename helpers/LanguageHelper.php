<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

class LanguageHelper{

	public static function getIso2($string){	
	
		$s = explode("-",$string);
		
		if (is_array($s)){
			if (isset($s[0])){
				if (2==strlen($s[0])){
					return $s[0];
				}
			}
		}
	
		return $string;
	}
}


?>