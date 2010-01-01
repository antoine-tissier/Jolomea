<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

require_once "Gettext/TGettext.class.php";

class POHelper{

	public static function export($array_source,$array_target){
			
		$po = TGettext::factory('PO');
		
		$array = array();
		
		foreach($array_source as $k=>$v){		
			$array[$k] = array();
			$array[$k]['source'] = $v;
			$array[$k]['target'] = $array_target[$k];
		}
			
		
		$export =  'msgid ""'."\n";
		$export .= 'msgstr ""'."\n";
		$export .= '"Content-Type: text/plain; charset=UTF-8\n"'."\n\n";

		
		foreach($array as $k=>$v){
			$export.= 'msgctxt  "'  . TGettext::prepare($k, true) . '"' . "\n" ;
			$export.= 'msgid "'  . TGettext::prepare($v['source'], true) . '"' . "\n" ;
            $export.= 'msgstr "' . TGettext::prepare($v['target'], true) . '"' . "\n\n";					
		}
		return $export;		
	}

	public static function import($string){
		$array = array();
	
		return $array;
	}
}


?>