<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

class IniHelper{

	public static function export($array){
	
		$handlerIni = & JRegistryFormat::getInstance('INI');
		$object = new StdClass;
		
		foreach($array as $k=>$v){
			$object->$k=$v;
		}
		
		$string = $handlerIni->objectToString($object,null);
	
		
		return $string;
	}

	public static function import($string){
		$handlerIni = & JRegistryFormat::getInstance('INI');
		$object = $handlerIni->stringToObject($string);		
		return get_object_vars($object);		
	}
}


?>