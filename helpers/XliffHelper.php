<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

class XliffHelper{

	public static function export($translation_array, $source_language, $target_language,$original_file, $datatype){
		$export = '<?xml version=\'1.0\' encoding=\'utf-8\'?>'."\n";
		$export .= '<xliff xmlns="urn:oasis:names:tc:xliff:document:1.2" version="1.2">'."\n";
		$export .='<file original="'.$original_file.'" source-language="'.$source_language.'" target-language="'.$target_language.'" datatype="'.$datatype.'">'."\n";
		$export .='<body>'."\n";
		
		foreach ($translation_array as $k=>$v){
			$export .= '<trans-unit id="'.$k.'">'."\n";
			$export .= '<source><![CDATA['.$v['source'].']]></source>'."\n";
			$export .=' <target><![CDATA['.$v['target'].']]></target>'."\n";
			$export .= '</trans-unit>'."\n";
		}
		
		$export .='</body>'."\n";
		$export .='</file>'."\n";
		$export .= '</xliff>'."\n";
		return $export;
	}

	public static function import($string){
		$object = simplexml_load_string($string);
	
		$file = $object->file;

		$body = $file->body;
		
		$array = array();
		
		foreach ($body->children() as $trans_unit){
			if ($trans_unit->getName()<>"trans-unit") continue;
			$id="";
			foreach($trans_unit->attributes() as $k=>$v){
				if ($k=="id")$id=strval($v);
			}
			foreach($trans_unit->children() as $trans_unit_children){
				if($trans_unit_children->getName()=="target"){
					$array[$id] = strval($trans_unit_children);
				}
			}
		}
		
		return $array;
	}
}


?>