<?
defined( '_JEXEC' ) or defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

class jolomeaJoomfish extends jolomeaHandler{

	public static function displayEntryMenu(){		
		$handler = JoomlaCompatibilityHelper::getRequestCmd('handler');
		
		if (file_exists(JoomlaCompatibilityHelper::getJoomlaRoot()."/administrator/components/com_joomfish/")){
			JoomlaCompatibilityHelper::addEntrySubMenu(JoomlaCompatibilityHelper::__('Joomfish'), 'index'.(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"").'.php?option=com_jolomea&handler=jolomeaJoomfish',$handler=='jolomeaJoomfish');
		}
	}
	
	public function getFilePath(){	
		return JoomlaCompatibilityHelper::getJoomlaRoot()."/administrator/components/com_virtuemart/languages/";
	}
		
	public function getTranslationFileToArray($params){
		$params = explode(':', $params);
		$getTranslationFileToArray = array();
		
		$file = $params[0];
		
		$object = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/administrator/components/com_joomfish/contentelements/".$file.".xml");
		
		$reference_id = $object->reference->table->xpath("field[@type='referenceid']");		
		$reference_id =	$reference_id [0];
		$reference_id = $reference_id->attributes();
		$reference_id = strval($reference_id ['name']);
		
		$table_name = $object->reference->table->attributes();
		$table_name = $table_name['name'];
		
		$fields = array();
		
		foreach($object->reference->table->xpath("field[@translate='1']") as $field){
			$field = $field->attributes();
			$fields[]=strval($field['name']);
		}
		
		$query = 'SELECT `'.$reference_id.'`';
		
		foreach($fields as $field){
			$query .= ',`';
			$query .= $field;
			$query .= '`';
		}
		
		
		$query .= ' from #__'.$table_name;
		
		$database					=& JFactory::getDBO();
		$database->setQuery($query);
		
		foreach($database->loadAssocList(0) as $row){
			foreach($fields as $field){
						
				$getTranslationFileToArray [$row[$reference_id]."_".$field] = $row[$field];
	
			}
		}
		
		
		return $getTranslationFileToArray;
	}
	
	public  function getArrayToTranslationFile($array,$language,$group){		
	}
	
	public function translationGroupToFilename($group,$language){
		return $group.':'.$language;
	}
	
	public function filenameToTranslationGroup($filename){
	}
	
	public function getAvailableLanguages(){
		$database					=& JFactory::getDBO();
		$getAvailableLanguages = array();
		$database->setQuery('SELECT id, code from #__languages');
	
		foreach ($database->loadObjectList('id') as $k=>$v){
			$getAvailableLanguages[]=$v->code;
		}
	
		return $getAvailableLanguages;
	}
	
	public function getAvailableTranslationDataForGroup($language,$key){
	}
	
	public function getAvailableTranslationData($language){	
		$database					=& JFactory::getDBO();
		$getAvailableTranslationData = array();
		$database->setQuery("select joomlatablename from #__jf_tableinfo");
		foreach ($database->loadObjectList() as $v){
			$getAvailableTranslationData[$v->joomlatablename]=1;
		}
		return $getAvailableTranslationData;
	}
}	