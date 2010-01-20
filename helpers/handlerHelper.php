<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');


abstract class jolomeaHandler{
	public static function displayEntryMenu(){}
	
	public static function getHandlersList(){}
	
	public abstract function getAvailableLanguages();
	
	public function getDefaultLanguageSource(){	
		$languages = $this->getAvailableLanguages();
		
		return $languages[0];
	}
	
	public function getDefaultLanguageTarget(){	
		$languages = $this->getAvailableLanguages();
		if (!empty($languages[1])){
			return $languages[1];
		} else {
			return $languages[0];
		}
	}
	
	public function getAvailableLanguagesSelect($name,$selected="", $attribs=""){
		$availableLanguagesSelect = "<select name='".$name."' ".$attribs.">";
		foreach ($this->getAvailableLanguages() as $lang){
			$availableLanguagesSelect .= "<option ".($selected==$lang?" selected='selected' ":" ") .">".$lang."</option>";
		}
		$availableLanguagesSelect .= "</select>";
		return $availableLanguagesSelect;
	}

	public abstract function getTranslationFileToArray($file);
	
	public abstract function getArrayToTranslationFile($array,$language,$group);
	
	public function getTranslationFilesToArray($fileSource, $fileTarget){
		
		$source_array = $this->getTranslationFileToArray($fileSource);
		$target_array = $this->getTranslationFileToArray($fileTarget);

		$retour = array();
		
		foreach($source_array as $key=>$source_value){
			$retour[$key] = array();
			$retour [$key]['source'] = $source_value;
			if (isset($target_array[$key])){
				$retour[$key]['target'] = $target_array[$key];
			} else {
				$retour[$key]['target'] = '';
			}
		}
		
		return $retour;
	}
	
	public abstract function translationGroupToFilename($group, $language);
	
	public abstract function getAvailableTranslationData($language);
}