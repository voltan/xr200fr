CREATE TABLE `lives_channel` (
	`channel_id` int(10) NOT NULL auto_increment,
	`channel_title` varchar(255) NOT NULL,
	`channel_alias` varchar(255) NOT NULL,
	`channel_text` text NOT NULL,
	`channel_img` varchar(255) NOT NULL,
	`channel_wms_pp` varchar(20) NOT NULL,
	`channel_low_pp` varchar(20) NOT NULL,
	`channel_medium_pp` varchar(20) NOT NULL,
	`channel_high_pp` varchar(20) NOT NULL,
	`channel_rtmp_url` varchar(255) NOT NULL,
	`channel_rtsp_url` varchar(255) NOT NULL,
	`channel_http_url` varchar(255) NOT NULL,
	`channel_mms_url` varchar(255) NOT NULL,
	`channel_order` int (10) NOT NULL,
	`channel_create` int (10) NOT NULL,
	`channel_hits` int (10) NOT NULL,
	`channel_status` tinyint(1) NOT NULL,
PRIMARY KEY  (`channel_id`),
UNIQUE KEY `channel_alias` (`channel_alias`),
KEY (`channel_title`)
) ENGINE=MyISAM;