<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

require_once 'helpers/JoomlaCompatibilityHelper.php';
require_once 'helpers/XliffHelper.php';
require_once 'helpers/POHelper.php';
require_once 'helpers/IniHelper.php';
require_once 'helpers/handlerHelper.php';

$task = JoomlaCompatibilityHelper::getRequestCmd('task');

$defaultHandlerName = 'jolomeaFrontOfficeIniJoomla';

if (!defined('_JEXEC')){
	$defaultHandlerName = 'jolomeaDefineJoomla';
}

$handler = JoomlaCompatibilityHelper::getRequestCmd('handler',$defaultHandlerName);

$language_source =  JoomlaCompatibilityHelper::getRequestCmd('language_source');
$language_target =  JoomlaCompatibilityHelper::getRequestCmd('language_target');

$server_method = $_SERVER['REQUEST_METHOD'];

require_once 'views/jolomea.php';

if ($handle=opendir(dirname(__FILE__).'/handlers'))
{		
	while (false!==($file=readdir($handle)))
	{	  
		if ($file<>"." AND $file<>"..")
		{
			if (is_file(dirname(__FILE__).'/handlers/'.$file))
			{
				require_once dirname(__FILE__).'/handlers/'.$file;
				if (class_exists(basename($file,'.php'))){
					eval( basename($file,'.php')."::displayEntryMenu();");				
				}				
			}
		}
	}
}	

JoomlaCompatibilityHelper::displaySubMenu();

if (class_exists($handler)){
  $jolomea_translation_handler = eval ("return  new $handler();");
	
	$available_languages = $jolomea_translation_handler->getAvailableLanguages();

	
	
	if (empty($language_source)){
		$language_source=$jolomea_translation_handler->getDefaultLanguageSource();
	}

	if (empty($language_target)){
		$language_target=$jolomea_translation_handler->getDefaultLanguageTarget();
	}
	
	
	if (!in_array($task,array('importIni','importXliff'))){
		JolomeaViewJolomea::displayHeader(
			$jolomea_translation_handler->getAvailableLanguagesSelect("language_source",$language_source, " onchange=\"$('idFormLanguage').submit()\" "),
			$jolomea_translation_handler->getAvailableLanguagesSelect("language_target",$language_target, " onchange=\"$('idFormLanguage').submit()\" "),
			$handler
		);
	}

	$translation_group = JoomlaCompatibilityHelper::getRequestCmd('translation_group');
	
	
	switch($task){
		case "saveTranslationFile":
			$language_source =  JoomlaCompatibilityHelper::getRequestCmd('language_source');
			$language_target =  JoomlaCompatibilityHelper::getRequestCmd('language_target');
			$target_array = JoomlaCompatibilityHelper::getRequestVar('target',array());
			if ($jolomea_translation_handler->getArrayToTranslationFile($target_array,$language_target,$translation_group)){
				$mainframe->redirect('/administrator/index.php?option=com_jolomea&handler='.$handler.'&task=editTranslationGroup&translation_group='.$translation_group.'&language_source='.$language_source.'&language_target='.$language_target,'OK');
			} else {
				$mainframe->redirect('/administrator/index.php?option=com_jolomea&handler='.$handler.'&task=editTranslationGroup&translation_group='.$translation_group.'&language_source='.$language_source.'&language_target='.$language_target,'Err');
			}
			break;
			
		case 'editTranslationGroup':
			$translation_group = JoomlaCompatibilityHelper::getRequestCmd('translation_group');

			$fileSource = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_source);
			
			$fileTarget = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_target);
			
			$translation_array = $jolomea_translation_handler->getTranslationFilesToArray($fileSource, $fileTarget);
			
			JolomeaViewJolomea::editTranslationData($translation_array);
			
			break;
			
		case 'exportXliff':
			$language_source =  JoomlaCompatibilityHelper::getRequestCmd('language_source');
			$language_target =  JoomlaCompatibilityHelper::getRequestCmd('language_target');

			$fileSource = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_source);
			
			$fileTarget = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_target);
			
			$translation_array = $jolomea_translation_handler->getTranslationFilesToArray($fileSource, $fileTarget);
			$xliff = XliffHelper::export($translation_array, $language_source, $language_target,$handler."_".$translation_group, "htmlbody");
						
			ob_end_clean();
			header("Content-Type: application/force-download; name=\"".$handler."_".$translation_group.".xlf");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".strlen($xliff));
			header("Content-Disposition: attachment; filename=\"".$handler."_".$translation_group.".xlf");
			header("Expires: 0");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache"); 
			die($xliff);
			break;

		case 'exportPO':
			$language_target =  JoomlaCompatibilityHelper::getRequestCmd('language_target');
			
			$fileSource = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_source);
			$fileTarget = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_target);
			
			$po = POHelper::export($jolomea_translation_handler->getTranslationFileToArray($fileSource),$jolomea_translation_handler->getTranslationFileToArray($fileTarget));
						
			ob_end_clean();			
			header("Content-Type: application/force-download; name=\"".$handler."_".$translation_group."_".$language_target.".po");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".strlen($po));
			header("Content-Disposition: attachment; filename=\"".$handler."_".$translation_group."_".$language_target.".po");
			header("Expires: 0");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache"); 
			die($po);
			break;

			
		case 'exportIni':
			$language_target =  JoomlaCompatibilityHelper::getRequestCmd('language_target');
			
			$fileTarget = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_target);
			
			$ini = IniHelper::export($jolomea_translation_handler->getTranslationFileToArray($fileTarget));
						
			ob_end_clean();
			header("Content-Type: application/force-download; name=\"".$handler."_".$translation_group.".ini");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".strlen($ini));
			header("Content-Disposition: attachment; filename=\"".$handler."_".$translation_group.".ini");
			header("Expires: 0");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache"); 
			die($ini);
			break;
		
		case 'importIni':		
		case 'importXliff':
			$message = "";
			
			if ($server_method=='POST'){										
			
				$tmp_file = $_FILES['importFile']['tmp_name'];				
				
				if( !is_uploaded_file($tmp_file) )	{
					$message = JoomlaCompatibilityHelper::__('File access error');
				} else {

					$fileTarget = $jolomea_translation_handler->translationGroupToFilename($translation_group,$language_target);
					
					$translation_array = array();
					if ($task=='importIni'){					
						$translation_array = IniHelper::import(file_get_contents($tmp_file));
					} else {
						$translation_array = XliffHelper::import(file_get_contents($tmp_file));						
					}
					
					if (!$jolomea_translation_handler->getArrayToTranslationFile($translation_array,$language_target,$translation_group)){					
						$message = JoomlaCompatibilityHelper::__('File access error');
					}
				}
				
				if (empty($message)){
					$mainframe->redirect('/administrator/index.php?option=com_jolomea&handler='.$handler.'&translation_group='.$translation_group.'&language=source='.$language_source.'&language_target='.$language_target.'&task=editTranslationGroup');
					die();
				} 								
			}
			
			if ($task=='importIni'){									
				JolomeaViewJolomea::displayImportForm($jolomea_translation_handler->getAvailableLanguagesSelect("language_target",$language_target), $handler, $translation_group, $message, "ini");					
			} else {
				JolomeaViewJolomea::displayImportForm($jolomea_translation_handler->getAvailableLanguagesSelect("language_target",$language_target), $handler, $translation_group, $message, "xliff");					
			}
		
			
			break;
				
		default :		
			$available_translation_datas = $jolomea_translation_handler->getAvailableTranslationData($language_source);		
			JolomeaViewJolomea::listTranslationData($available_translation_datas, $language_source, $language_target,$handler);		
	}
	
	JolomeaViewJolomea::displayFooter();
}