<?php
/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */
// no direct access
defined( '_JEXEC' ) or defined( '_VALID_MOS' ) or die( 'Restricted access' );


class TOOLBAR_jolomea
{
	function _DEFAULT()
	{				
		JoomlaCompatibilityHelper::setTitle(JoomlaCompatibilityHelper::__( 'Jolomea' ), 'langmanager.png' );
		JoomlaCompatibilityHelper::addToolbarIcon("search", "preview", "preview", "search");
	}
	
	function _EDIT(){
		JoomlaCompatibilityHelper::addToolbarIcon("saveTranslationFile", "save.png", "save.png", "save");
		JoomlaCompatibilityHelper::addToolbarBackButton();
	}
}