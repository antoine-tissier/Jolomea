<?php
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
		return array('jolomeaJoomfish');
	}
	
	public function getHandlerName() {
		return 'jolomeaJoomfish';
	}

	public static function getHandlersList(){
		return array('jolomeaJoomfish');
	}

	
	public function getFilePath(){	
		return JoomlaCompatibilityHelper::getJoomlaRoot()."/administrator/components/com_virtuemart/languages/";
	}
	
	public function index(){
		$database = &JoomlaCompatibilityHelper::getDatabaseObject();
		$database->setQuery("SHOW INDEX FROM #__jf_content WHERE key_name='value_full_text'");
		if (!$database->loadResult()){
			$database->setQuery('ALTER TABLE `#__jf_content` ADD FULLTEXT `value_full_text` (`value`);');
			$database->query();
		}		
	}
	
	public function getLanguageByCode($langue){
		$database = &JoomlaCompatibilityHelper::getDatabaseObject();
		$database->setQuery("SELECT * from #__languages where code=".$database->Quote($langue));
		$language  = $database->loadObject();
		return $language;
	}
	
	public function getDefaultLanguage(){				
		$language = null;
		if (JoomlaCompatibilityHelper::isJoomla1_5()){
			$params = JComponentHelper::getParams('com_languages');
			$langue = $params->get("site", 'en-GB');		
			$language = self::getLanguageByCode($langue);		
		} else {
			$database = &JoomlaCompatibilityHelper::getDatabaseObject();
			$database->setQuery("SELECT * from #__languages where active=1 order by ordering limit 1");
			$database->loadObject($language);
		}
		return $language;
	}
	
	public function search($keyword, $language_target, $language_source){		
		$search = array();
		$search_r = array();
				
		if (!empty($keyword)){			
			$this->index();
						
			$database					=& JFactory::getDBO();
		
			$database->setQuery('SELECT id from #__languages where shortcode='.$database->Quote($language_source));			
			$language_source=$database->loadResult();
		
			$database->setQuery('SELECT id from #__languages where shortcode='.$database->Quote($language_target));
			$language_target=$database->loadResult();
						
			$keyword = '%'.$keyword.'%';
			$database->setQuery('SELECT * from #__jf_content where `value` like'.$database->Quote($keyword).' and language_id='.$language_target);
			
			$list = $database->loadObjectList();
			
			
			
			if (is_array($list)){
				foreach ($database->loadObjectList() as $r){
					$search_r = array();
					$search_r['group'] = $r->reference_table;
					$search_r['handler'] = $this->getHandlerName();
					$search_r['key'] = $r->reference_id.'_'.$r->reference_field;
					$search_r['language'] =$r->language_id;
					$search_r['language_source'] = "";
					$search_r['text'] =$r->value;
					$search[] = $search_r;
				}
			}
		}
		return $search;
	}
	
	public function getTranslationFileToArray($params){
		$params = explode(':', $params);
		$getTranslationFileToArray = array();
		
		$file = $params[0];
		
		$language = $params[1];		
		
		$defaultLanguage = $this->getDefaultLanguage();
		
		$database = &JoomlaCompatibilityHelper::getDatabaseObject();
		
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
		
		if ($defaultLanguage->code==$language){		
			$query = 'SELECT `'.$reference_id.'`';
			
			foreach($fields as $field){
				$query .= ',`';
				$query .= $field;
				$query .= '`';
			}			
			
			$query .= ' from #__'.$table_name;
			
			
			$database->setQuery($query);
			
			foreach($database->loadAssocList(0) as $row){
				foreach($fields as $field){						
					$getTranslationFileToArray [$row[$reference_id]."_".$field] = $row[$field];	
				}
			}				
		}

		//Whatever it is the default language, we should check if the results are overrided by Joomfish		
		$database->setQuery("SELECT #__jf_content.* from #__jf_content inner join #__languages on #__languages.id = #__jf_content.language_id where reference_table=".$database->Quote($file)." and code=".$database->Quote($language)." and published=1");
		
		$results =$database->loadObjectList();
		
		if (is_array($results)){
			foreach($results as $result){		
				$getTranslationFileToArray[$result->reference_id."_".$result->reference_field] = $result->value;		
			}
		}
		
		return $getTranslationFileToArray;
	}
	
	/**
	  * Save of the translation in the database
	  */	
	public  function getArrayToTranslationFile($array,$language,$reference_table){		
		
		$user =& JFactory::getUser();				
		
		$database = &JoomlaCompatibilityHelper::getDatabaseObject();
				
		$language_id = self::getLanguageByCode($language);
		$language_id = $language_id->id;				
		
		$modified=time();
		$modified_by=$user->id;	
		
		$non_empty_keys = array();
		$non_empty_ids = array();		
		
		foreach ($array as $key=>$value){		
			if (!empty($value)){			
				$non_empty_keys [$key] = $key;										
				$non_empty_ids[intval($key)]	= intval($key);			
			}		
		}		
		
		$query = implode(',',$non_empty_ids);
		
		$query = "SELECT concat(#__jf_content.reference_id,'_',#__jf_content.reference_field) from #__jf_content inner join #__languages on #__languages.id =#__jf_content.language_id where  reference_table='categories' and reference_id in (".$query.")";
		
		$database->setQuery($query);
		
		$existing_list = $database->loadResultArray();
		
		$update_array[] = array();
		
		$database->setQuery("DROP TABLE IF EXISTS `#__jf_content_jolomea`");		
		$database->query();
		$database->setQuery("CREATE TABLE `#__jf_content_jolomea` (`reference_id` INT NOT NULL ,`reference_field` VARCHAR( 100 ) NOT NULL ,`value` mediumTEXT NOT NULL) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$database->query();
		$database->setQuery("ALTER TABLE `#__jf_content_jolomea` ADD INDEX ( `reference_id` , `reference_field` )");
		$database->query();
								
		$query ='';
			
		if (is_array($existing_list)){
			foreach($existing_list as $e){
										
				$update_array[$e] = $value;
										
				$reference_id = intval($e); 
				
				$keys = explode('_',$e);
				unset ($keys[0]);
				
				
				$reference_field = implode('_',$keys);
				
				if (!empty($query)){
					$query.=',';
				}
				
				$query .="(".$reference_id.",".$database->Quote($reference_field).",".$database->Quote($array[$e]).")";					
			}
		
			$query = 'INSERT INTO #__jf_content_jolomea VALUES '.$query;
				
			$database->setQuery($query);
			$database->query();
		
		}
								
		$database->setQuery('UPDATE #__jf_content, #__jf_content_jolomea set modified_by='.$modified_by.' ,modified=now(),#__jf_content.`value`=#__jf_content_jolomea.`value` where #__jf_content_jolomea.reference_id=#__jf_content.reference_id and #__jf_content_jolomea.reference_field=#__jf_content.reference_field and #__jf_content.reference_table='.$database->Quote($reference_table)." and #__jf_content.language_id=".$language_id );
		$database->query();
		
		// Now we inserts the new translation
		
		$query ="";		
		
		if (is_array($array)){		
			foreach($array as $key=>$value){
				if (!empty($value)){
					if (!isset($update_array[$key])){
						if (!empty($query)) $query.=',';
						$reference_id = intval($key); 
						$keys = explode('_',$key);
						unset ($keys[0]);						
						$reference_field = implode('_',$keys);
						$query.='('.$language_id.','.$reference_id.','.$database->Quote($reference_table).','.$database->Quote($reference_field).','.$database->Quote($value).',now(),'.$modified_by.',1)';
					}		
				}
			}
			
			$query = 'INSERT INTO #__jf_content (language_id,reference_id,reference_table,reference_field,value,modified,modified_by,published) VALUES '.$query;
		
			$database->setQuery($query);
			$database->query();		
		}
		
		return true;
	}
	
	public function translationGroupToFilename($group,$language){
		return $group.':'.$language;
	}
	
	public function filenameToTranslationGroup($filename){
	}
	
	public function getAvailableLanguages(){				
		$database = &JoomlaCompatibilityHelper::getDatabaseObject();
		
		$getAvailableLanguages = array();
		$database->setQuery('SELECT id, code from #__languages');
	
		$list = $database->loadObjectList('id');
	
		if (is_array($list)){	
			foreach ($database->loadObjectList('id') as $k=>$v){
				$getAvailableLanguages[]=$v->code;
			}
		}
	
		return $getAvailableLanguages;
	}
	
	public function getAvailableTranslationDataForGroup($language,$key){
	}
	
	public function getAvailableTranslationData($language){	
		$database = &JoomlaCompatibilityHelper::getDatabaseObject();
		$getAvailableTranslationData = array();
		$database->setQuery("select joomlatablename from #__jf_tableinfo");
		foreach ($database->loadObjectList() as $v){
			$getAvailableTranslationData[$v->joomlatablename]=1;
		}
		return $getAvailableTranslationData;
	}
}	