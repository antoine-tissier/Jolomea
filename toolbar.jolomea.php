<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined( '_JEXEC' ) or defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once "toolbar.jolomea.html.php";

switch ($task)
{
	case "editTranslationGroup":
		TOOLBAR_jolomea::_EDIT(($handler=="jolomeaBackOfficeIniJoomla")&&($translation_group=="com_jolomea"));
		TOOLBAR_jolomea::_DEFAULT();
		break;
	default:
		TOOLBAR_jolomea::_DEFAULT();
		break;
}