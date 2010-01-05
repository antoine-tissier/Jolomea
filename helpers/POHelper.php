<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');


class POHelper{

	public static function export($array_source,$array_target){
		
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
			$export.= 'msgctxt "'  . self::prepare($k, true) . '"' . "\n" ;
			$export.= 'msgid "'  . self::prepare($v['source'], true) . '"' . "\n" ;
            $export.= 'msgstr "' . self::prepare($v['target'], true) . '"' . "\n\n";					
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
            $array[self::prepare($msgctxt)] = self::prepare($msgstr);
        }			
		return $array;
	}		
	
	/**
	  * Stolen from PEAR Gettext
	  * Copyright (c) 2004 Michael Wallner <mike@iworks.at> 
	  */
	
	public static function prepare($string, $reverse = false)
    {
        if ($reverse) {
            $smap = array('"', "\n", "\t", "\r");
            $rmap = array('\"', '\\n"' . "\n" . '"', '\\t', '\\r');
            return (string) str_replace($smap, $rmap, $string);
        } else {
        	$string = preg_replace('/"\s+"/', '', $string);
            $smap = array('\\n', '\\r', '\\t', '\"');
            $rmap = array("\n", "\r", "\t", '"');
            return (string) str_replace($smap, $rmap, $string);
        }
    }
	
}


?>