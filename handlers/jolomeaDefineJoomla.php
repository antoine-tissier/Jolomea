<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

if (defined( '_VALID_MOS' )){

class jolomeaDefineJoomla extends jolomeaHandler{	

	public function getFilesPath(){
	}

	public static function displayEntryMenu(){		
		$handler = JoomlaCompatibilityHelper::getRequestCmd('handler');
		JoomlaCompatibilityHelper::addEntrySubMenu('Joomla','index2.php?option=com_jolomea&handler=jolomeaDefineJoomla',$handler=='jolomeaDefineJoomla');
	}
	
	public function parseFile($file){
		$data = file($file);
		$uniqid = uniqid ();
		
		$script ='class jolomea_'.$uniqid.'{static $data = array();function define($key,$value){self::$data[$key]=$value;}}';
		
		foreach($data as $k=>$row){
			//$data[$k] = preg_replace  ( "/$(\s)*[define|DEFINE](\s)*\(/"  , "define_".$uniqid."("  , $row  );	
			//$data[$k] = preg_replace  ( "/$(\s)*[define|DEFINE](\s)*\(/"  , "define_".$uniqid."("  , $row  );	
			$data[$k] = str_ireplace("define(","jolomea_".$uniqid."::define("  , $row  );
			$data[$k] = str_ireplace("<?php", "" , $data[$k]  );
			$data[$k] = str_ireplace("?>", "" , $data[$k]  );
			$script .= $data[$k];
		}
		
		$script .= 'return jolomea_'.$uniqid.'::$data;';
			
		$data = eval ($script);
		
		return $data;
	}
	
	public function getTranslationFileToArray($file){
	
	
	}
	
	public  function getArrayToTranslationFile($array,$language,$group){		
	}
	
	public function translationGroupToFilename($group,$language){
		global $mosConfig_absolute_path;
		
		$path = $mosConfig_absolute_path."/language";
	
		return $path."/".$language.".php";		
	}
	
	public function getTranslationFilesToArray($fileSource, $fileTarget){
		$source_array = $this->parseFile($fileSource);
		$target_array = $this->parseFile($fileTarget);
			
		$retour = array();
		
		if (is_array($source_array)){
				foreach($source_array as $key=>$source_value){
					$retour[$key] = array();
					$retour [$key]['source'] = $source_value;
					if (isset($target_array[$key])){
						$retour[$key]['target'] = $target_array[$key];
					} else {
						$retour[$key]['target'] = '';
					}
				}
		}
		
		return $retour;
	}
	
	public function getAvailableLanguages(){
		global $mosConfig_absolute_path;
		
		$path = $mosConfig_absolute_path."/language/";
		
		$languages = array();
		if ($handle=opendir($path))
		{		
			while (false!==($file=readdir($handle)))
			{	  
				if ($file<>"." AND $file<>"..")
				{
					if (is_file($path."/".$file))
					{
						$pathinfo = pathinfo($file);
						
						if ($pathinfo['extension']=='xml'){
							$languages [] =  basename($file,'.xml');			
						}															
					}
				}
			}
		}	
		return $languages;
	}
	
	
	public function getAvailableTranslationDataForGroup($language,$key){
		global $mosConfig_absolute_path;
		$path = $mosConfig_absolute_path."/language/";
		if ($key=="*"){
			$file=$language.".php";
		} else {
			$file=$language.".".$key.".php";
		}
		return $path."/".$file;
	}
	
	public function getAvailableTranslationData($language){
		global $mosConfig_absolute_path;
		
		$translationFiles = array();
		$languages =  $this->getAvailableLanguages();
		
		$path = $mosConfig_absolute_path."/language/";
		
		if ($handle=opendir($path))
		{		
			while (false!==($file=readdir($handle)))
			{	
				if ($file<>"." AND $file<>"..")
				{
					if (is_file($path."/".$file))
					{
						$pathinfo = pathinfo($file);
						
						if ($pathinfo['extension']=='php'){			
						
							$tmp =array();
							
							$translation_group = "*";
							
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

}