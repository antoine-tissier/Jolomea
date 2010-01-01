<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

if (defined( '_JEXEC' )){

abstract class jolomeaIniJoomla extends jolomeaHandler{

	public abstract function getIniFilesPath();

	public static function displayEntryMenu(){		
		$handler = JoomlaCompatibilityHelper::getRequestCmd('handler');
		JoomlaCompatibilityHelper::addEntrySubMenu(JText::_('Joomla Front'), 'index.php?option=com_jolomea&handler=jolomeaFrontOfficeIniJoomla',$handler=='jolomeaFrontOfficeIniJoomla');
		JoomlaCompatibilityHelper::addEntrySubMenu(JText::_('Joomla Back'), 'index.php?option=com_jolomea&handler=jolomeaBackOfficeIniJoomla',$handler=='jolomeaBackOfficeIniJoomla');
	}
	
	public function translationGroupToFilename($group,$language){
		if ((!empty($group))&&($group<>"*")){
			return $this->getIniFilesPath().DS.$language.DS.$language.".".$group.".ini";
		}
		
		return $this->getIniFilesPath().DS.$language.DS.$language.".ini";
	}
	
	public function getTranslationFileToArray($file){
		$handlerIni = & JRegistryFormat::getInstance('INI');
		$data = file_get_contents($file);
		$object = $handlerIni->stringToObject($data);
		return get_object_vars($object);
	}
	
	public  function getArrayToTranslationFile($array,$language,$group){		
		$filename = $this->translationGroupToFilename($group,$language);
		
		$handlerIni = & JRegistryFormat::getInstance('INI');
		$object = new StdClass;
		
		foreach($array as $k=>$v){
			$object->$k=$v;
		}
		
		$string = $handlerIni->objectToString($object,null);
		
		try{
			$fp = fopen($filename, 'w');
			fwrite($fp, $string);
			fclose($fp);
		} catch (Exception $e){
			return false;
		}
		
		return true;
	}
	
	public function getAvailableLanguages(){
	
		$path = $this->getIniFilesPath();
		
		$languages = array();
		if ($handle=opendir($path))
		{		
			while (false!==($file=readdir($handle)))
			{	  
				if ($file<>"." AND $file<>"..")
				{
					if (is_dir($path.DS.$file))
					{
						if ($file<>"pdf_fonts"){					
							$languages [] =  $file;	
						}						
					}
				}
			}
		}	
		return $languages;
	}
	
	public function getAvailableTranslationDataForGroup($language,$key){
		$path = $this->getIniFilesPath().DS.$language;
		if ($key=="*"){
			$file=$language.".ini";
		} else {
			$file=$language.".".$key.".ini";
		}
		return $path.DS.$file;
	}
	
	public function getAvailableTranslationData($language){
		$path = $this->getIniFilesPath().DS.$language;

		$languages =  $this->getAvailableLanguages();
		
		$translationFiles = array();
		if ($handle=opendir($path))
		{		
			while (false!==($file=readdir($handle)))
			{	  
				if ($file<>"." AND $file<>"..")
				{
					if (is_file($path.DS.$file))
					{
						$pathinfo = pathinfo($file);
						
						if ($pathinfo['extension']=='ini'){					
							$translation_group = substr(basename($file,'.ini'), strlen($language));
						
							if (strlen($translation_group)>1){
								$translation_group = substr($translation_group,1);
							} else {
								$translation_group = '*';
							}
						
							$tmp =array();
							foreach($languages as $_language){
							
								$tmp[$_language] = $this->getAvailableTranslationDataForGroup($_language,$translation_group);
								
							}
							$translationFiles [$translation_group] = $tmp;
						
						}
					}
				}
			}
		}
		return $translationFiles;
	}
}

class jolomeaBackOfficeIniJoomla extends jolomeaIniJoomla{
	public function getIniFilesPath(){
		return JPATH_ADMINISTRATOR.DS."language";
	}
}

class jolomeaFrontOfficeIniJoomla extends jolomeaIniJoomla{
	public function getIniFilesPath(){
		return JPATH_ROOT.DS."language";		
	}
}

}