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
			mosMenuBar::custom($task, $image, $image_over, $caption);
		}
	}
	
	public static function getRequestVar($param, $default){
		if (self::isJoomla1_5()){
			return JRequest::getVar($param,$default,$_REQUEST);
		} else {
			//TODO : securite
			return mosGetParam($_REQUEST,$param,$default);
		}
	}
	
	public static function getRequestCmd($param,$default=""){
	
		if (self::isJoomla1_5()){
			return JRequest::getCmd($param,$default,$_REQUEST);
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
			echo "<ul>";
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
	
	public static function __($key){
		if (self::isJoomla1_5()){
			return JText::_($key);
		} else {
			return $key;
		}
	}
	
}

?>