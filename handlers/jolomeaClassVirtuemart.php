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
		if ($handle=opendir($path))
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
		return $this->getFilePath()."/".$key."/".$language.".".$key.".php";
	}
	
	public function getAvailableTranslationData($language){
		$translationFiles = array();
		$path = $this->getFilePath();
		
		$languages = $this->getAvailableLanguages();
		
		if ($handle=opendir($path))
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