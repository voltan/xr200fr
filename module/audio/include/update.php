<?php
function xoops_module_update_audio() {
	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` CHANGE `top` `top` TINYINT( 1 ) NOT NULL DEFAULT '0' ;";
	$sql = "ALTER TABLE `" . $db->prefix('audio_cat') . "` ADD `cat_tab` TINYINT( 1 ) NOT NULL DEFAULT '0' ;";
	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` ADD `extra` TEXT NOT NULL;";
	$sql = "ALTER TABLE `" . $db->prefix('audio_downloads') . "` ADD `related` VARCHAR( 255 ) NOT NULL DEFAULT '' ;";
?>