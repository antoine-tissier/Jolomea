<?php
defined( '_JEXEC' ) or defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

class jolomeaClassVirtuemart extends jolomeaHandler{

	public static function displayEntryMenu(){		
		$handler = JoomlaCompatibilityHelper::getRequestCmd('handler');
		
		if (file_exists(JoomlaCompatibilityHelper::getJoomlaRoot()."/administrator/components/com_virtuemart/languages/")){
			JoomlaCompatibilityHelper::addEntrySubMenu(JoomlaCompatibilityHelper::__('Virtuemart'), 'index'.(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"").'.php?option=com_jolomea&handler=jolomeaClassVirtuemart',$handler=='jolomeaClassVirtuemart');
		}
		return array('jolomeaClassVirtuemart');
	}

	public static function getHandlersList(){
		return array('jolomeaClassVirtuemart');
	}
	
	public function getHandlerName() {
		return 'jolomeaClassVirtuemart';
	}
	
	public function getFilenameToTranslationGroup($file,$language){		
		$s_filename = substr($file, 0, strlen($file)-(strlen($language)+5) );
				
		return basename($s_filename);
	}
	

	public function search($keyword, $language_target, $language_source){		
		$search = array();
		if (!empty($keyword)){			
		
			$langages = $this->getAvailableLanguages();						
		
			$language_source_array = LanguageHelper::filter($langages,$language_source);			
		
			$language_source_array = array_keys  ($language_source_array );
		
			$language_source = reset($language_source_array);
		
			$language_filtered = LanguageHelper::filter($langages,$language_target);																		
			
			foreach($language_filtered as $_l=>$l_v){			
		
				$groups = $this->getAvailableTranslationData($_l);							
				
				foreach($groups as $group) {
					$filename = $group[$_l];
					
					if (is_file($filename)){					
						$content = file_get_contents($filename);
						
						if (false<>mb_strpos($content,$keyword, 0, 'UTF8')){				
						
							$translation_array = $this->getTranslationFileToArray($filename);
						
							foreach($translation_array  as $k=>$t){													
								if (is_string($t)&&(!empty($t))){
							
									if (false <> mb_strpos($t,$keyword, 0, 'UTF8')){
										$search_r = array();
										$search_r['group'] = $this->getFilenameToTranslationGroup($filename,$_l);
										$search_r['handler'] = $this->getHandlerName();
										$search_r['key'] = $k;
										$search_r['language'] =$_l;
										$search_r['language_source'] = $language_source;
										$search_r['text'] =$t;
										$search[] = $search_r;
									}
								}										
							}
						}
					}
				}
			}
		}
		return $search;
	}
	
	public function getFilePath(){	
		return JoomlaCompatibilityHelper::getJoomlaRoot()."/administrator/components/com_virtuemart/languages/";
	}
		
	public function getTranslationFileToArray($file){
		$data = file($file);
		$uniqid = uniqid ();
		
		$script ='class jolomea_'.$uniqid.'{static $data = array();function initModule($dummy,$array){self::$data=$array;}}';
		
		foreach($data as $k=>$row){
			//$data[$k] = preg_replace  ( "/$(\s)*[define|DEFINE](\s)*\(/"  , "define_".$uniqid."("  , $row  );	
			//$data[$k] = preg_replace  ( "/$(\s)*[define|DEFINE](\s)*\(/"  , "define_".$uniqid."("  , $row  );	
			$data[$k] = str_ireplace('$VM_LANG->initModule(',"jolomea_".$uniqid."::initModule("  , $row  );			
			$data[$k] = str_ireplace('global $VM_LANG;', "" , $data[$k]  );
			$data[$k] = str_ireplace("<?php", "" , $data[$k]  );
			$data[$k] = str_ireplace("?>", "" , $data[$k]  );
			$script .= $data[$k];
		}
		
		$script .= 'return jolomea_'.$uniqid.'::$data;';
			
		$data = eval ($script);
		
		return $data;
	}
	
	public  function getArrayToTranslationFile($array,$language,$group){		
		$filename = $this->translationGroupToFilename($group,$language);
		
		$string = '';
	
		$path = $this->getFilePath();
	
		if (file_exists($path."common")){
			
		}
	
		$string .= '<?php'."\n";
		$string .= 'if( !defined( \'_VALID_MOS\' ) && !defined( \'_JEXEC\' ) ) die( \'Direct Access to \'.basename(__FILE__).\' is not allowed.\' ); '."\n";
		$string .= 'global $VM_LANG;'."\n";
		
		$string .= '$langvars = array ('."\n";
		
		$n_keys = count($array);
		$i = 0;
		
		//TODO : proteger les quotes
		
		foreach($array as $key=>$value){
			$string .= "\t";
			$value = str_replace('\'','\\\'',$value);
			$string .= '\''.$key.'\' => \''.$value.'\'';	
			if($i<$n_keys){
				$string.=',';
			}
			$string.="\n";
			$i++;
		}					
		
		$string .='); $VM_LANG->initModule( \''.$group.'\', $langvars );';
		$string .='?>'."\n";
	
		try{
			$fp = fopen($filename, 'w');
			fwrite($fp, $string);
			fclose($fp);
		} catch (Exception $e){
			return false;
		}
		
		return true;
	}
	
	public function translationGroupToFilename($group,$language){
		if ($group=="*"){
			return $this->getFilePath().$language.".php";
		} else {
			return $this->getFilePath().$group."/".$language.".php";
		}
	}
	
	public function filenameToTranslationGroup($filename){
	}
	
	public function getAvailableLanguages(){
		$availableLanguages = array();
		
		$path = $this->getFilePath();
			
		if (file_exists($path."common")){
			$path.="common";
		}
			
		$languages = array();
		if ($handle=@opendir($path))
		{		
			while (false!==($file=readdir($handle)))
			{	  
				if ($file<>"." AND $file<>".." AND $file<>"index.html")
				{				
					if (is_file($path."/".$file)){
						$availableLanguages[]=basename($file,'.php');
					}
				}
			}
		}
		
		return $availableLanguages;
	}
	
	public function getAvailableTranslationDataForGroup($language,$key){
		return $this->getFilePath()."/".$key."/".$language.".php";
	}
	
	public function getAvailableTranslationData($language){
		$translationFiles = array();
		$path = $this->getFilePath();
		
		$languages = $this->getAvailableLanguages();
		
		if ($handle=@opendir($path))
		{		
			while (false!==($file=readdir($handle)))
			{	  
				if ($file<>"." AND $file<>"..")
				{				
				
					if (file_exists($path.DS."common")){
						if (is_dir($path.DS.$file))
						{											
							$translation_group = $file;
							$tmp =array();
							foreach($languages as $_language){								
								$tmp[$_language] = $this->getAvailableTranslationDataForGroup($_language,$translation_group);
							}
							$translationFiles [$translation_group] = $tmp;
						}
					} else {					
						foreach($languages as $_language){
							$tmp =array();
							$translation_group='*';
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