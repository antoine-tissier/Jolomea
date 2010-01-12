<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

class LanguageHelper{

	public static function getIso2($string){	
	
		$s = explode("-",$string);
		
		if (is_array($s)){
			if (isset($s[0])){
				if (2==strlen($s[0])){
					return $s[0];
				}
			}
		}
		
		switch($string){
			case 'afar': $string = 'aa';break;
			case 'abkhazian': $string = 'ab';break;
			case 'avestan': $string = 'ae';break;
			case 'afrikaans': $string = 'af';break;
			case 'akan': $string = 'ak';break;
			case 'amharic': $string = 'am';break;
			case 'aragonese': $string = 'an';break;
			case 'arabic': $string = 'ar';break;
			case 'assamese': $string = 'as';break;
			case 'avaric': $string = 'av';break;
			case 'aymara': $string = 'ay';break;
			case 'azerbaijani': $string = 'az';break;
			case 'bashkir': $string = 'ba';break;
			case 'belarusian': $string = 'be';break;
			case 'bulgarian': $string = 'bg';break;
			case 'bihari languages': $string = 'bh';break;
			case 'bislama': $string = 'bi';break;
			case 'bambara': $string = 'bm';break;
			case 'bengali': $string = 'bn';break;
			case 'tibetan': $string = 'bo';break;
			case 'breton': $string = 'br';break;
			case 'bosnian': $string = 'bs';break;
			case 'catalan': $string = 'ca';break;
			case 'valencian': $string = 'ca';break;
			case 'chechen': $string = 'ce';break;
			case 'chamorro': $string = 'ch';break;
			case 'corsican': $string = 'co';break;
			case 'cree': $string = 'cr';break;
			case 'czech': $string = 'cs';break;
			case 'chuvash': $string = 'cv';break;
			case 'welsh': $string = 'cy';break;
			case 'danish': $string = 'da';break;
			case 'german': $string = 'de';break;
			case 'divehi': $string = 'dv';break;
			case 'dhivehi': $string = 'dv';break;
			case 'maldivian': $string = 'dv';break;
			case 'dzongkha': $string = 'dz';break;
			case 'ewe': $string = 'ee';break;
			case 'greek': $string = 'el';break;
			case 'english': $string = 'en';break;
			case 'esperanto': $string = 'eo';break;
			case 'spanish': $string = 'es';break;
			case 'castilian': $string = 'es';break;
			case 'estonian': $string = 'et';break;
			case 'basque': $string = 'eu';break;
			case 'persian': $string = 'fa';break;
			case 'fulah': $string = 'ff';break;
			case 'finnish': $string = 'fi';break;
			case 'fijian': $string = 'fj';break;
			case 'faroese': $string = 'fo';break;
			case 'french': $string = 'fr';break;
			case 'western frisian': $string = 'fy';break;
			case 'irish': $string = 'ga';break;
			case 'gaelic': $string = 'gd';break;
			case 'galician': $string = 'gl';break;
			case 'guarani': $string = 'gn';break;
			case 'gujarati': $string = 'gu';break;
			case 'manx': $string = 'gv';break;
			case 'hausa': $string = 'ha';break;
			case 'hebrew': $string = 'he';break;
			case 'hindi': $string = 'hi';break;
			case 'hiri motu': $string = 'ho';break;
			case 'croatian': $string = 'hr';break;
			case 'haitian': $string = 'ht';break;
			case 'hungarian': $string = 'hu';break;
			case 'armenian': $string = 'hy';break;
			case 'herero': $string = 'hz';break;
			case 'interlingua': $string = 'ia';break;
			case 'indonesian': $string = 'id';break;
			case 'interlingue': $string = 'ie';break;
			case 'igbo': $string = 'ig';break;
			case 'sichuan yi': $string = 'ii';break;
			case 'nuosu': $string = 'ii';break;
			case 'inupiaq': $string = 'ik';break;
			case 'ido': $string = 'io';break;
			case 'icelandic': $string = 'is';break;
			case 'italian': $string = 'it';break;
			case 'inuktitut': $string = 'iu';break;
			case 'japanese': $string = 'ja';break;
			case 'javanese': $string = 'jv';break;
			case 'georgian': $string = 'ka';break;
			case 'kongo': $string = 'kg';break;
			case 'kikuyu': $string = 'ki';break;
			case 'kuanyama': $string = 'kj';break;
			case 'kazakh': $string = 'kk';break;
			case 'kalaallisut': $string = 'kl';break;
			case 'greenlandic': $string = 'kl';break;
			case 'central khmer': $string = 'km';break;
			case 'kannada': $string = 'kn';break;
			case 'korean': $string = 'ko';break;
			case 'kanuri': $string = 'kr';break;
			case 'kashmiri': $string = 'ks';break;
			case 'kurdish': $string = 'ku';break;
			case 'komi': $string = 'kv';break;
			case 'cornish': $string = 'kw';break;
			case 'kirghiz': $string = 'ky';break;
			case 'kyrgyz': $string = 'ky';break;
			case 'latin': $string = 'la';break;
			case 'luxembourgish': $string = 'lb';break;
			case 'letzeburgesch': $string = 'lb';break;
			case 'ganda': $string = 'lg';break;
			case 'limburgan': $string = 'li';break;
			case 'limburger': $string = 'li';break;
			case 'limburgish': $string = 'li';break;
			case 'lingala': $string = 'ln';break;
			case 'lao': $string = 'lo';break;
			case 'lithuanian': $string = 'lt';break;
			case 'luba-katanga': $string = 'lu';break;
			case 'latvian': $string = 'lv';break;
			case 'malagasy': $string = 'mg';break;
			case 'marshallese': $string = 'mh';break;
			case 'maori': $string = 'mi';break;
			case 'macedonian': $string = 'mk';break;
			case 'malayalam': $string = 'ml';break;
			case 'mongolian': $string = 'mn';break;
			case 'marathi': $string = 'mr';break;
			case 'malay': $string = 'ms';break;
			case 'maltese': $string = 'mt';break;
			case 'burmese': $string = 'my';break;
			case 'nauru': $string = 'na';break;
			case 'bokmål': $string = 'nb';break;
			case 'ndebele': $string = 'nd';break;
			case 'nepali': $string = 'ne';break;
			case 'ndonga': $string = 'ng';break;
			case 'dutch': $string = 'nl';break;
			case 'flemish': $string = 'nl';break;
			case 'norwegian': $string = 'nn';break;
			case 'nynorsk': $string = 'nn';break;
			case 'norwegian': $string = 'no';break;
			case 'ndebele': $string = 'nr';break;
			case 'navajo': $string = 'nv';break;
			case 'navaho': $string = 'nv';break;
			case 'chichewa': $string = 'ny';break;
			case 'chewa': $string = 'ny';break;
			case 'nyanja': $string = 'ny';break;
			case 'occitan': $string = 'oc';break;
			case 'ojibwa': $string = 'oj';break;
			case 'oromo': $string = 'om';break;
			case 'oriya': $string = 'or';break;
			case 'ossetian': $string = 'os';break;
			case 'ossetic': $string = 'os';break;
			case 'panjabi; punjabi': $string = 'pa';break;
			case 'pali': $string = 'pi';break;
			case 'polish': $string = 'pl';break;
			case 'pushto': $string = 'ps';break;
			case 'pashto': $string = 'ps';break;
			case 'portuguese': $string = 'pt';break;
			case 'quechua': $string = 'qu';break;
			case 'romansh': $string = 'rm';break;
			case 'rundi': $string = 'rn';break;
			case 'romanian': $string = 'ro';break;
			case 'moldavian': $string = 'ro';break;
			case 'moldovan': $string = 'ro';break;
			case 'russian': $string = 'ru';break;
			case 'kinyarwanda': $string = 'rw';break;
			case 'sanskrit': $string = 'sa';break;
			case 'sardinian': $string = 'sc';break;
			case 'sindhi': $string = 'sd';break;
			case 'northern sami': $string = 'se';break;
			case 'sango': $string = 'sg';break;
			case 'sinhala': $string = 'si';break;
			case 'sinhalese': $string = 'si';break;
			case 'slovak': $string = 'sk';break;
			case 'slovenian': $string = 'sl';break;
			case 'samoan': $string = 'sm';break;
			case 'shona': $string = 'sn';break;
			case 'somali': $string = 'so';break;
			case 'albanian': $string = 'sq';break;
			case 'serbian': $string = 'sr';break;
			case 'swati': $string = 'ss';break;
			case 'sotho': $string = 'st';break;
			case 'sundanese': $string = 'su';break;
			case 'swedish': $string = 'sv';break;
			case 'swahili': $string = 'sw';break;
			case 'tamil': $string = 'ta';break;
			case 'telugu': $string = 'te';break;
			case 'tajik': $string = 'tg';break;
			case 'thai': $string = 'th';break;
			case 'tigrinya': $string = 'ti';break;
			case 'turkmen': $string = 'tk';break;
			case 'tagalog': $string = 'tl';break;
			case 'tswana': $string = 'tn';break;
			case 'tonga': $string = 'to';break;
			case 'turkish': $string = 'tr';break;
			case 'tsonga': $string = 'ts';break;
			case 'tatar': $string = 'tt';break;
			case 'twi': $string = 'tw';break;
			case 'tahitian': $string = 'ty';break;
			case 'uighur': $string = 'ug';break;
			case 'uyghur': $string = 'ug';break;
			case 'ukrainian': $string = 'uk';break;
			case 'urdu': $string = 'ur';break;
			case 'uzbek': $string = 'uz';break;
			case 'venda': $string = 've';break;
			case 'vietnamese': $string = 'vi';break;
			case 'volapük': $string = 'vo';break;
			case 'walloon': $string = 'wa';break;
			case 'wolof': $string = 'wo';break;
			case 'xhosa': $string = 'xh';break;
			case 'yiddish': $string = 'yi';break;
			case 'yoruba': $string = 'yo';break;
			case 'zhuang': $string = 'za';break;
			case 'chuang': $string = 'za';break;
			case 'chinese': $string = 'zh';break;
			case 'zulu': $string = 'zu';break;				
		}
	
		return $string;
	}
	
	public static function equals($language, $iso2){
		return (self::getIso2($language) == $iso2);
	}
	
	public static function filter($available_languages, $iso2){
		$filter=array();
		if (is_array($available_languages)){
			foreach($available_languages as $language){
				if (self::equals($language,$iso2)){
					$filter[$language]=$iso2;
				}
			}
		}
		return $filter;
	}
	
}


?>