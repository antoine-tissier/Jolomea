<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

class JoomlaCompatibilityHelper{

	static $subMenu = array();

	public static function isJoomla1_5(){
		return defined('_JEXEC');
	}
	
	public static function isJoomla1_0(){
		return defined('_VALID_MOS');
	}

	public static function getJoomlaRoot(){
		return $_SERVER['DOCUMENT_ROOT'];
	}
	
	public static function getDS(){
		if (self::isJoomla1_0()){
			return "/";
		} else {
			return DS;
		}
	}

	public static function getComponentVersion($component_name){
		
			
	
	}
	
	public static function setTitle($title, $image){
		if (defined( '_JEXEC' )){
			JToolBarHelper::title( $title, $image );
		} else {
		
			
		}
	}
	
	public static function addToolbarBackButton($alt = 'Back', $href = 'javascript:history.back();'){
		if (self::isJoomla1_5()){
			JToolBarHelper::back($alt,$href);
		}
	}
	
	public static function addToolbarIcon($task, $image, $image_over, $caption){
		if (self::isJoomla1_5()){
			JToolBarHelper::custom($task, $image, $image_over, $caption);
		} else {
			require_once self::getJoomlaRoot()."/administrator/includes/menubar.html.php";			
									
			if (substr($image,-4)<>'.png') 
				$image=$image.'.png';										
				
			if (substr($image_over,-4)<>'.png')
				$image_over=$image_over.'.png';										
			
			mosMenuBar::custom($task, $image, $image_over, $caption);
		}
	}
	
	public static function toolbartStart(){
		if (self::isJoomla1_0()) mosMenuBar::startTable();
	}
	
	public static function toolbartEnd(){
		if (self::isJoomla1_0()) mosMenuBar::endTable();
	}
	
	public static function getRequestVar($param, $default=""){
		if (self::isJoomla1_5()){
			return JRequest::getVar($param,$default,'default');
		} else {
			//TODO : securite
			return mosGetParam($_REQUEST,$param,$default);
		}
	}
	
	public static function getRequestCmd($param,$default=""){
	
		if (self::isJoomla1_5()){
			return JRequest::getCmd($param,$default,'default');
		} else {
			//TODO : securite
			return mosGetParam($_REQUEST,$param,$default);
		}
	}
	
	public static function addEntrySubMenu($label,$link,$selected=false){
		if (self::isJoomla1_5()){
			JSubMenuHelper::addEntry($label, $link,$selected);
		} else {
			$subMenuEntry = array();
			$subMenuEntry['label'] = $label;
			$subMenuEntry['link'] = $link;
			$subMenuEntry['selected'] = $selected;
			self::$subMenu[] = $subMenuEntry;
		}	
	}
	
	public static function displaySubMenu(){
		//TODO : ameliorer l'affichage
		if (!self::isJoomla1_5()){	

			?>
			
			<style type="text/css">
				.jolomeasubmenu li {display:inline;margin : 0px 10px; border : 1px solid black; padding:10px;}
			</style>
			
			<?php
		
			echo "<ul class='jolomeasubmenu'>";
			foreach(self::$subMenu as $entry){
				echo "<li>";
				if (!$entry['selected']){
					echo "<a href='".$entry['link']."'>";
					echo $entry['label'];
					echo "</a>";
				} else {
					echo $entry['label'];
				}
				echo "</li>";
			}
			echo "</ul>";
		}
	}
	
	public static function loadMootools(){
		if (self::isJoomla1_5()){
			JHTML::_('behavior.mootools');			
		}else {
			global $mainframe;
			//Mootols is not installed on a fresh Joomla 1.0 installation.
			$mainframe->addCustomHeadTag('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.11/mootools-yui-compressed.js"></script>');
		}
	}
	
	public static function __($key){
		if (self::isJoomla1_5()){
			return JText::_($key);
		} else {
			//English only for Joomla 1.0.
			return ucfirst(strtolower($key));							
		}
	}
	
	public static function getIndexPage(){
		return 'index'.(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"").'.php';
	}
	
	public static function getDatabaseObject(){
		if (self::isJoomla1_0()){	
			global $database;
			return $database;
		} else {
			return JFactory::getDBO();
		}		
	}
	
}

?>