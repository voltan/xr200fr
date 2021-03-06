<?php
include_once 'header.php';

// template d'affichage
$xoopsOption ['template_main'] = 'audio_liste.html';
include_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet ( XOOPS_URL . '/modules/' . $xoopsModule->getVar ( 'dirname', 'n' ) . '/css/style.css', null );
$categories = audio_MygetItemIds ( 'audio_view', 'audio' );

if (isset ( $_REQUEST ['title'] )) {
	$_REQUEST ['title'] != '' ? $title = $_REQUEST ['title'] : $title = '';
} else {
	$title = '';
}

if (isset ( $_REQUEST ['cat'] )) {
	$_REQUEST ['cat'] != 0 ? $cat = $_REQUEST ['cat'] : $cat = 0;
} else {
	$cat = 0;
}

// tableau ------

$criteria_2 = new CriteriaCompo ();
$criteria_2->add ( new Criteria ( 'status', 0, '!=' ) );
$criteria_2->add ( new Criteria ( 'cid', '(' . implode ( ',', $categories ) . ')', 'IN' ) );

// ------

//breadcrumb
$xoopsTpl->assign ( 'breadcrumb', '<a href="' . XOOPS_URL . '">' . _MD_AUDIO_HOME . '</a>  &raquo; <a href="index.php">' . $xoopsModule->name() . '</a> &raquo; ' . _MD_AUDIO_SEARCH_BT );

//formulaire de recherche
$form = new XoopsThemeForm ( _MD_AUDIO_SEARCH, "search", "search.php", 'post' );
$form->setExtra ( 'enctype="multipart/form-data"' );

//recherche par titre
$form->addElement ( new XoopsFormText ( _MD_AUDIO_SEARCH_TITLE, 'title', 25, 255, $title ) );

//recherche par cat�gorie
$criteria = new CriteriaCompo ();
$criteria->setSort ( 'cat_weight ASC, cat_title' );
$criteria->setOrder ( 'ASC' );
$criteria->add ( new Criteria ( 'cat_cid', '(' . implode ( ',', $categories ) . ')', 'IN' ) );

/*$cat_select = new XoopsFormSelect(_MD_AUDIO_SEARCH_CATEGORIES . ' ', 'cat', $cat); $cat_select->addOption(0,_MD_AUDIO_SEARCH_ALL2); $cat_select->addOptionArray($downloadscat_Handler->getList($criteria )); $form->addElement($cat_select);*/

$downloadscat_arr = $downloadscat_Handler->getall ( $criteria );
$mytree = new XoopsObjectTree ( $downloadscat_arr, 'cat_cid', 'cat_pid' );
$form->addElement ( new XoopsFormLabel ( _AM_AUDIO_FORMINCAT, $mytree->makeSelBox ( 'cat', 'cat_title', '--', $cat, true ) ) );

//recherche champ sup.
$downloadsfield_Handler = & xoops_getModuleHandler ( 'audio_field', 'audio' );
$criteria = new CriteriaCompo ();
$criteria->add ( new Criteria ( 'search', 1 ) );
$criteria->add ( new Criteria ( 'status', 1 ) );
$criteria->setSort ( 'weight ASC, title' );
$criteria->setOrder ( 'ASC' );
$downloads_field = $downloadsfield_Handler->getall ( $criteria );
$arguments = '';

foreach ( array_keys ( $downloads_field ) as $i ) {
	$title_sup = '';
	$contenu_arr = array ();
	$lid_arr = array ();
	$nom_champ = 'champ' . $downloads_field [$i]->getVar ( 'fid' );
	$criteria = new CriteriaCompo ();
	if (isset ( $_REQUEST [$nom_champ] )) {
		$_REQUEST [$nom_champ] != 999 ? $champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] = $_REQUEST [$nom_champ] : $champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] = 999;
		$arguments .= $nom_champ . '=' . $_REQUEST [$nom_champ] . '&amp;';
	} else {
		$champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] = 999;
		$arguments .= $nom_champ . '=&amp;';
	}

	if ($downloads_field [$i]->getVar ( 'status_def' ) == 1) {

		$criteria->add ( new Criteria ( 'status', 0, '!=' ) );
		if ($downloads_field [$i]->getVar ( 'fid' ) == 1) {
			//page d'accueil			$title_sup = _AM_AUDIO_FORMHOMEPAGE;
			$criteria->setSort ( 'homepage' );
			$nom_champ_base = 'homepage';
		}

		if ($downloads_field [$i]->getVar ( 'fid' ) == 2) {
			//version			$title_sup = _AM_AUDIO_FORMVERSION;
			$criteria->setSort ( 'version' );
			$nom_champ_base = 'version';
		}

		if ($downloads_field [$i]->getVar ( 'fid' ) == 3) {
			//taille du fichier			$title_sup = _AM_AUDIO_FORMSIZE;
			$criteria->setSort ( 'size' );
			$nom_champ_base = 'size';
		}

		if ($downloads_field [$i]->getVar ( 'fid' ) == 4) {
			//platform			$title_sup = _AM_AUDIO_FORMPLATFORM;
			$platform_array = explode ( '|', $xoopsModuleConfig ['plateform'] );
			foreach ( $platform_array as $platform ) {
				$contenu_arr [$platform] = $platform;
			}
			if ($champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] != 999) {
				$criteria_2->add ( new Criteria ( 'platform', '%' . $champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] . '%', 'LIKE' ) );
			}

		} else {
			$criteria->setOrder ( 'ASC' );
			$audio_arr = $downloads_Handler->getall ( $criteria );
			foreach ( array_keys ( $audio_arr ) as $j ) {
				$contenu_arr [$audio_arr [$j]->getVar ( $nom_champ_base )] = $audio_arr [$j]->getVar ( $nom_champ_base );
			}
			if ($champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] != 999) {
				$criteria_2->add ( new Criteria ( $nom_champ_base, $champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] ) );
			}
		}
	} else {
		$title_sup = $downloads_field [$i]->getVar ( 'title' );
		$criteria->add ( new Criteria ( 'fid', $downloads_field [$i]->getVar ( 'fid' ) ) );
		$criteria->setSort ( 'data' );
		$criteria->setOrder ( 'ASC' );
		$audio_arr = $downloadsfielddata_Handler->getall ( $criteria );
		foreach ( array_keys ( $audio_arr ) as $j ) {
			$contenu_arr [$audio_arr [$j]->getVar ( 'data', 'n' )] = $audio_arr [$j]->getVar ( 'data' );
		}
		if ($champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] != '') {
			$criteria_1 = new CriteriaCompo ();
			$criteria_1->add ( new Criteria ( 'data', $champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] ) );
			$data_arr = $downloadsfielddata_Handler->getall ( $criteria_1 );
			foreach ( array_keys ( $data_arr ) as $k ) {
				$lid_arr [] = $data_arr [$k]->getVar ( 'lid' );
			}
		}
		$form->addElement ( $select_sup );
	}

	if (count ( $lid_arr ) > 0) {
		$criteria_2->add ( new Criteria ( 'lid', '(' . implode ( ',', $lid_arr ) . ')', 'IN' ) );
	}
	$select_sup = new XoopsFormSelect ( $title_sup, $nom_champ, $champ_contenu [$downloads_field [$i]->getVar ( 'fid' )] );
	$select_sup->addOption ( 999, _MD_AUDIO_SEARCH_ALL1 );
	$select_sup->addOptionArray ( $contenu_arr );
	$form->addElement ( $select_sup );
	unset ( $select_sup );
	$xoopsTpl->append ( 'field', $downloads_field [$i]->getVar ( 'title' ) );
}

//bouton validation
$button_tray = new XoopsFormElementTray ( '', '' );
$button_tray->addElement ( new XoopsFormButton ( '', 'submit', _MD_AUDIO_SEARCH_BT, 'submit' ) );
$form->addElement ( $button_tray );
if ($title != '') {
	$criteria_2->add ( new Criteria ( 'title', '%' . $title . '%', 'LIKE' ) );
	$arguments .= 'title=' . $title . '&amp;';
}
if ($cat != 0) {
	$criteria_2->add ( new Criteria ( 'cid', $cat ) );
	$arguments .= 'cat=' . $cat . '&amp;';
}

$tblsort = array ();
$tblsort [1] = 'date';
$tblsort [2] = 'date';
$tblsort [3] = 'hits';
$tblsort [4] = 'hits';
$tblsort [5] = 'rating';
$tblsort [6] = 'rating';
$tblsort [7] = 'title';
$tblsort [8] = 'title';

$tblorder = array ();
$tblorder [1] = 'DESC';
$tblorder [2] = 'ASC';
$tblorder [3] = 'DESC';
$tblorder [4] = 'ASC';
$tblorder [5] = 'DESC';
$tblorder [6] = 'ASC';
$tblorder [7] = 'DESC';
$tblorder [8] = 'ASC';

$sort = isset ( $xoopsModuleConfig ['searchorder'] ) ? $xoopsModuleConfig ['searchorder'] : 1;
$order = isset ( $xoopsModuleConfig ['searchorder'] ) ? $xoopsModuleConfig ['searchorder'] : 1;
$criteria_2->setSort ( $tblsort [$sort] );
$criteria_2->setOrder ( $tblorder [$order] );
$numrows = $downloads_Handler->getCount ( $criteria_2 );
if (isset ( $_REQUEST ['limit'] )) {
	$criteria_2->setLimit ( $_REQUEST ['limit'] );
	$limit = $_REQUEST ['limit'];
} else {
	$criteria_2->setLimit ( $xoopsModuleConfig ['perpageliste'] );
	$limit = $xoopsModuleConfig ['perpageliste'];
}

if (isset ( $_REQUEST ['start'] )) {
	$criteria_2->setStart ( $_REQUEST ['start'] );
	$start = $_REQUEST ['start'];
} else {
	$criteria_2->setStart ( 0 );
	$start = 0;
}

//pour faire une jointure de table
$downloads_Handler->table_link = $downloads_Handler->db->prefix ( "audio_cat" ); // Nom de la table en jointure
$downloads_Handler->field_link = "cat_cid"; // champ de la table en jointure
$downloads_Handler->field_object = "cid"; // champ de la table courante
$audio_arr = $downloads_Handler->getByLink ( $criteria_2 );
if ($numrows > $limit) {
	$pagenav = new XoopsPageNav ( $numrows, $limit, $start, 'start', $arguments );
	$pagenav = $pagenav->renderNav ( 4 );
} else {
	$pagenav = '';
}

$xoopsTpl->assign ( 'lang_thereare', sprintf ( _MD_AUDIO_SEARCH_THEREARE, $downloads_Handler->getCount ( $criteria_2 ) ) );
$xoopsTpl->assign ( 'pagenav', $pagenav );
$keywords = '';

foreach ( array_keys ( $audio_arr ) as $i ) {
	$audio_tab ['lid'] = $audio_arr [$i]->getVar ( 'lid' );
	$audio_tab ['cid'] = $audio_arr [$i]->getVar ( 'cid' );
	$audio_tab ['title'] = $audio_arr [$i]->getVar ( 'title' );
	$audio_tab ['cat'] = $audio_arr [$i]->getVar ( 'cat_title' );
	$audio_tab ['imgurl'] = $uploadurl . $audio_arr [$i]->getVar ( 'cat_imgurl' );
	$audio_tab ['date'] = formatTimeStamp ( $audio_arr [$i]->getVar ( 'date' ), 'd/m/Y' );
	$audio_tab ['rating'] = number_format ( $audio_arr [$i]->getVar ( 'rating' ), 0 );
	$audio_tab ['hits'] = $audio_arr [$i]->getVar ( 'hits' );
	$contenu = '';
	foreach ( array_keys ( $downloads_field ) as $j ) {
		if ($downloads_field [$j]->getVar ( 'status_def' ) == 1) {
			if ($downloads_field [$j]->getVar ( 'fid' ) == 1) {
				//page d'accueil				$contenu = $audio_arr [$i]->getVar ( 'homepage' );
			}
			if ($downloads_field [$j]->getVar ( 'fid' ) == 2) {
				//version				$contenu = $audio_arr [$i]->getVar ( 'version' );
			}
			if ($downloads_field [$j]->getVar ( 'fid' ) == 3) {
				//taille du fichier				$contenu = audio_trans_size ( $audio_arr [$i]->getVar ( 'size' ) );
			}
			if ($downloads_field [$j]->getVar ( 'fid' ) == 4) {
				//plateforme				$contenu = $audio_arr [$i]->getVar ( 'platform' );
			}
		} else {
			$criteria = new CriteriaCompo ();
			$criteria->add ( new Criteria ( 'lid', $audio_arr [$i]->getVar ( 'lid' ) ) );
			$criteria->add ( new Criteria ( 'fid', $downloads_field [$j]->getVar ( 'fid' ) ) );
			$downloadsfielddata = $downloadsfielddata_Handler->getall ( $criteria );
			if (count ( $downloadsfielddata ) > 0) {
				foreach ( array_keys ( $downloadsfielddata ) as $k ) {
					$contenu = $downloadsfielddata [$k]->getVar ( 'data', 'n' );
				}
			} else {
				$contenu = '';
			}
		}
		$audio_tab ['fielddata'] [$j] = $contenu;
		unset ( $contenu );
	}
	$xoopsTpl->append ( 'downloads', $audio_tab );
	$keywords .= $audio_arr [$i]->getVar ( 'title' ) . ',';
}

$xoopsTpl->assign ( 'searchForm', $form->render () );
// r�f�rencement
// titre de la page
$titre = _MD_AUDIO_SEARCH_PAGETITLE . ' - ' . $xoopsModule->name ();
$xoopsTpl->assign ( 'xoops_pagetitle', $titre );
//description
$xoTheme->addMeta ( 'meta', 'description', strip_tags ( $xoopsModule->name () ) );
//keywords
$keywords = substr ( $keywords, 0, - 1 );
$xoTheme->addMeta ( 'meta', 'keywords', strip_tags ( $keywords ) );
include XOOPS_ROOT_PATH . '/footer.php';
?>