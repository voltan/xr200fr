<?php
function xoops_module_update_audio() {	$db =& Database::getInstance();	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` CHANGE `cid` `cat_cid` INT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT ;";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` CHANGE `pid` `cat_pid` INT( 5 ) UNSIGNED NOT NULL DEFAULT '0' ;";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` CHANGE `title` `cat_title` VARCHAR( 255 ) NOT NULL ;";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` CHANGE `imgurl` `cat_imgurl` VARCHAR( 255 ) NOT NULL ;";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` CHANGE `description_main` `cat_description_main` TEXT NOT NULL ;";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` CHANGE `weight` `cat_weight` INT( 11 ) NOT NULL DEFAULT '0' ;";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` ADD `paypal` VARCHAR( 255 ) NOT NULL;";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` CHANGE `size` `size` VARCHAR( 15 ) NOT NULL DEFAULT '';";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_mod') . "` CHANGE `size` `size` VARCHAR( 15 ) NOT NULL DEFAULT '';";	$db->query($sql);	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` ADD `duration` VARCHAR( 15 ) NOT NULL DEFAULT '';";	$db->query($sql);
	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` CHANGE `top` `top` TINYINT( 1 ) NOT NULL DEFAULT '0' ;";	$db->query($sql);
	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` ADD `cat_tab` TINYINT( 1 ) NOT NULL DEFAULT '0' ;";	$db->query($sql);
	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` ADD `extra` TEXT NOT NULL;";	$db->query($sql);
	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` ADD `related` VARCHAR( 255 ) NOT NULL DEFAULT '' ;";	$db->query($sql);	return true;}
?>