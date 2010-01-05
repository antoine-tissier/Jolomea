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
		$export .= '"MIME-Version: 1.0\n"'."\n";
		$export .= '"Content-Transfer-Encoding: 8bit\n"'."\n";
		$export .= '"Content-Type: text/plain; charset=UTF-8\n"'."\n\n";				
		
		foreach($array as $k=>$v){
			$export.= 'msgctxt "'  . TGettext::prepare($k, true) . '"' . "\n" ;
			$export.= 'msgid "'  . TGettext::prepare($v['source'], true) . '"' . "\n" ;
            $export.= 'msgstr "' . TGettext::prepare($v['target'], true) . '"' . "\n\n";					
		}
		return $export;		
	}

	public static function import($contents){
		$array = array();		
	
		 // match all msgctxt/msgid/msgstr entries
        $matched = preg_match_all(
			'/(msgctxt\s+("([^"]|\\\\")*?"\s*)+)\s+' .
            '(msgid\s+("([^"]|\\\\")*?"\s*)+)\s+' .			
            '(msgstr\s+("([^"]|\\\\")*?"\s*)+)/',
            $contents, $matches
        );		
	
		if (!$matched) {
            return false;
        }
	
		// get all msgids and msgtrs
        for ($i = 0; $i < $matched; $i++) {					
			$msgctxt = preg_replace(
                '/\s*msgctxt\s*"(.*)"\s*/s', '\\1', $matches[1][$i]);													
            $msgstr= preg_replace(
                '/\s*msgstr\s*"(.*)"\s*/s', '\\1', $matches[7][$i]);
            $array[TGettext::prepare($msgctxt)] = TGettext::prepare($msgstr);
        }			
		return $array;
	}		
}


?>