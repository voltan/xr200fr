<?php
include 'admin_header.php';
xoops_cp_header();
$op = audio_CleanVars($_REQUEST, 'op', 'list', 'string');
switch ($op) {
	default:
	case "list":
		$form = new XoopsThemeForm($title, 'form', '', 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		//permissions
		$member_handler = & xoops_gethandler('member');
		$group_list = &$member_handler->getGroupList();
		$gperm_handler = &xoops_gethandler('groupperm');
		$full_list = array_keys($group_list);
		global $xoopsModule;
		$item_news_can_download_checkbox = new XoopsFormCheckBox(_AM_AUDIO_PERM_DOWNLOAD_DSC, 'item_download[]', $full_list);
		// pour télécharger
		$item_news_can_download_checkbox->addOptionArray($group_list);
		$form->addElement($item_news_can_download_checkbox);
		$form->addElement(new XoopsFormHidden('op', 'save'));
		//bouton d'envoi du formulaire
		$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
		$form->display();
		break;

	case "save":
		$criteria = new CriteriaCompo();
		$criteria->setSort('lid');
		$criteria->setOrder('ASC');
		$downloads = $downloads_Handler->getall($criteria);
		$cpt = 0;
		foreach (array_keys($downloads) as $i) {
			//permission pour télécharger
			echo $downloads[$i]->getVar('lid') . '<br>';
			$gperm_handler = &xoops_gethandler('groupperm');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('gperm_itemid',  $downloads[$i]->getVar('lid'), '='));
			$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
			$criteria->add(new Criteria('gperm_name', 'audio_download_item', '='));
			$gperm_handler->deleteAll($criteria);
			if(isset($_POST['item_download'])) {
				foreach($_POST['item_download'] as $onegroup_id) {
					$gperm_handler->addRight('audio_download_item', $downloads[$i]->getVar('lid'), $onegroup_id, $xoopsModule->getVar('mid'));
				}
			}
			$cpt++;
		}
		echo     '<br><br><br>'.$cpt;
		break;
}
xoops_cp_footer();
?>