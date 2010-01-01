<?php

/**
  * @author Libre d'esprit
  * @link http://libre-d-esprit.thinking-days.net
  * @license		GNU/GPL, see LICENSE.php
  */

// no direct access
defined('_JEXEC') or defined( '_VALID_MOS' ) or die('Restricted access');

class JolomeaViewJolomea{

	function displayImportForm($availableLanguagesTargetSelect, $handler, $translation_group, $message="", $format="ini"){
	?>
		<form action="index.php?option=com_jolomea" enctype="multipart/form-data" method="post" name="adminForm" id="idFormLanguage">		
			<input type="hidden" name="boxchecked" value="1" />
			<input type="hidden" name="task" value="import<?=ucfirst($format)?>"/>
			<input type="hidden" name="handler" value="<?=$handler?>"/>
			
			<?php if (!empty($message)) { ?>
			<strong><?=$message?></strong>
			<?php } ?>
			
			<p>
				<label><?=JoomlaCompatibilityHelper::__("Translation group")?> : </label>
				<input type="text" name="translation_group" readonly="readonly" value="<?=$translation_group?>"/>
			</p>
			
			<?=JoomlaCompatibilityHelper::__('Target language')?> : <?=$availableLanguagesTargetSelect?>			
			<p>
				<label><?=JoomlaCompatibilityHelper::__('file')?> :</label>
				<input name="importFile" type="file"/>
			</p>			
			<input type="submit" />
		</form>
	<?
	}

	function displayHeader($availableLanguagesSourceSelect,$availableLanguagesTargetSelect, $handler){
	?>
		<form action="index.php?option=com_jolomea" method="post" name="adminForm" id="idFormLanguage">
			<input type="hidden" name="boxchecked" value="1" />
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="handler" value="<?=$handler?>"/>
						
			<table style="width:100%">
				<tr>
					<td nowrap="nowrap">
					<?=JoomlaCompatibilityHelper::__('Source language')?>: <?=$availableLanguagesSourceSelect?>
					<?=JoomlaCompatibilityHelper::__('Target language')?>:  <?=$availableLanguagesTargetSelect?>
					</td>
				</tr>
			</table>			
	<?
	}

	function displayFooter(){
		?>
		</form>
		<?
	}
	
	function listTranslationData($translationData, $language_source, $language_target,$handler){
	?>
		<table class="adminlist">
	<?
			if (is_array($translationData)) 
			foreach($translationData as $available_translation_group => $available_translation_group_info){
				?><tr><td><?
				//Ecrire les translation group de facon plus jolie du style : composant banner plutot que com_banner
				echo $available_translation_group;
		
				?>
				<ul>
					<li>				
						<a href="index<?=(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"")?>.php?option=com_jolomea&handler=<?=$handler?>&translation_group=<?=$available_translation_group?>&language_source=<?=$language_source?>&language_target=<?=$language_target?>&task=editTranslationGroup">
							<?=JoomlaCompatibilityHelper::__('Edit translation')?>
						</a>
					</li>
				<?

				?>
					<li>
						<?=JoomlaCompatibilityHelper::__('Export in ')?>					
						<a href="index<?=(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"")?>.php?option=com_jolomea&handler=<?=$handler?>&translation_group=<?=$available_translation_group?>&language_source=<?=$language_source?>&language_target=<?=$language_target?>&task=exportXliff">
							XLIFF
						</a>,
						<a href="index<?=(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"")?>.php?option=com_jolomea&handler=<?=$handler?>&translation_group=<?=$available_translation_group?>&language_source=<?=$language_source?>&language_target=<?=$language_target?>&task=exportPO">
							PO
						</a>

				<?

						if (JoomlaCompatibilityHelper::isJoomla1_5()){
						?>	
						,						
						<a href="index<?=(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"")?>.php?option=com_jolomea&handler=<?=$handler?>&translation_group=<?=$available_translation_group?>&language_source=<?=$language_source?>&language_target=<?=$language_target?>&task=exportIni">
							INI
						</a>
						<?
						}

				
				//Ecrire les pourcentage d'avancement de traduction... de la language_target
				//Permettre l'édition en ligne de la language target (avec filtre sur les trad restantes uniquement)
		
				//Il faut un md5 des ou equivalent pour ne pas refaire les traitements a chaque fois
	
				?>
					</li>
					<li>
						<?=JoomlaCompatibilityHelper::__('Import in ')?>
						
						<a href="index<?=(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"")?>.php?option=com_jolomea&handler=<?=$handler?>&translation_group=<?=$available_translation_group?>&language_source=<?=$language_source?>&language_target=<?=$language_target?>&task=importXliff">
							XLIFF
						</a>
												
						<a href="index<?=(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"")?>.php?option=com_jolomea&handler=<?=$handler?>&translation_group=<?=$available_translation_group?>&language_source=<?=$language_source?>&language_target=<?=$language_target?>&task=importPO">
							, PO
						</a>
												
						<?php if (JoomlaCompatibilityHelper::isJoomla1_5()){ ?>
							<a href="index<?=(JoomlaCompatibilityHelper::isJoomla1_0()?"2":"")?>.php?option=com_jolomea&handler=<?=$handler?>&translation_group=<?=$available_translation_group?>&language_source=<?=$language_source?>&language_target=<?=$language_target?>&task=importIni">
								, INI
							</a>
						<?php }?>
					</li>
				
				
				</ul>
				
				</td></tr><?
			}
		?></table>
	<?
	}
	
	function editTranslationData($translation_array){
		?>
		<script type='text/javascript'>
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			
			// do field validation
			submitform( pressbutton );
		}
	
		function jolomeaGoogleTranslate(el){		
			
			var id =el.getParent().getParent().id;
					
			google.language.translate($(id).getElement("td.source").innerHTML, "en", "fr", function(result) {
				if (!result.error) {
					var id =el.getParent().getParent().id;					
					var container  = $(id).getElement("td.target").getElement('textarea');
					container.innerHTML = result.translation;
				}
			});				
			
			return false;
		}	
		</script>
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
		google.load("language", "1");
		</script>
		
			<table class="adminlist">
				<thead>
					<th>Key</th>
					<th style="width:40%">Source</th>
					<th style="width:40%">Target</th>
				</thead>
				<tbody>
				<?php foreach($translation_array as $key=>$row){	?>
				<tr id="row_<?=$key?>">
				<td class="key"><?=$key?></td>
				<td class="source"><?=$row['source']?></td>
				<td class="target"><textarea name='target[<?=$key?>]' style="width:100%"><?=$row['target']?></textarea></td>
				<td><button onclick="jolomeaGoogleTranslate(this);return false;"><?=JoomlaCompatibilityHelper::__('Suggest')?></button></td>
				</tr>
				<?php } ?>
				</tbody>				
			</table>		
			<input type="hidden" name="translation_group" value="<?=JoomlaCompatibilityHelper::getRequestVar('translation_group', '')?>"/>
			
		<?
	}
	
}

?>