<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "imagecycle".
 *
 * Auto generated 18-03-2014 21:29
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Image Cycle',
	'description' => 'Insert a slideshow into your page or template. Manage the images, captions and hrefs recursively in the pagetree and show it in a jQuery-Cycle, Coin-Slider, Nivo-Slider or Cross-Slider. Add media from DAM and DAM-Category. Use t3jquery for better integration with other jQuery extensions.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '2.7.7',
	'dependencies' => 'cms,jftcaforms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => 'uploads/tx_imagecycle',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Juergen Furrer',
	'author_email' => 'juergen.furrer@gmail.com',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '5.0.0-0.0.0',
			'typo3' => '4.3.0-6.2.99',
			'jftcaforms' => '0.2.1-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:128:{s:23:"class.tx_imagecycle.php";s:4:"20bb";s:21:"ext_conf_template.txt";s:4:"5dc2";s:12:"ext_icon.gif";s:4:"7990";s:17:"ext_localconf.php";s:4:"ec0c";s:14:"ext_tables.php";s:4:"40ac";s:14:"ext_tables.sql";s:4:"a00c";s:13:"locallang.xml";s:4:"f195";s:16:"locallang_db.xml";s:4:"6e27";s:12:"mode_dam.gif";s:4:"999b";s:15:"mode_damcat.gif";s:4:"2596";s:13:"mode_data.gif";s:4:"f004";s:15:"mode_folder.gif";s:4:"9d05";s:12:"mode_rte.gif";s:4:"2ded";s:15:"mode_upload.gif";s:4:"fecd";s:12:"t3jquery.txt";s:4:"8847";s:24:"compat/flashmessages.css";s:4:"4e2c";s:20:"compat/gfx/error.png";s:4:"e4dd";s:26:"compat/gfx/information.png";s:4:"3750";s:21:"compat/gfx/notice.png";s:4:"a882";s:17:"compat/gfx/ok.png";s:4:"8bfe";s:22:"compat/gfx/warning.png";s:4:"c847";s:14:"doc/manual.sxw";s:4:"3a95";s:38:"lib/class.tx_imagecycle_cms_layout.php";s:4:"520a";s:41:"lib/class.tx_imagecycle_itemsProcFunc.php";s:4:"c966";s:40:"lib/class.tx_imagecycle_pagerenderer.php";s:4:"bf28";s:35:"lib/class.tx_imagecycle_TCAform.php";s:4:"cd3a";s:35:"lib/class.tx_imagecycle_tceFunc.php";s:4:"03d5";s:39:"lib/class.tx_imagecycle_tsparserext.php";s:4:"151b";s:15:"pi1/ce_icon.gif";s:4:"7990";s:14:"pi1/ce_wiz.gif";s:4:"7667";s:31:"pi1/class.tx_imagecycle_pi1.php";s:4:"870a";s:39:"pi1/class.tx_imagecycle_pi1_wizicon.php";s:4:"9a80";s:19:"pi1/flexform_ds.xml";s:4:"8752";s:17:"pi1/locallang.xml";s:4:"48b7";s:15:"pi2/ce_icon.gif";s:4:"a304";s:14:"pi2/ce_wiz.gif";s:4:"18be";s:31:"pi2/class.tx_imagecycle_pi2.php";s:4:"f224";s:39:"pi2/class.tx_imagecycle_pi2_wizicon.php";s:4:"7c8e";s:19:"pi2/flexform_ds.xml";s:4:"de2e";s:17:"pi2/locallang.xml";s:4:"89af";s:15:"pi3/ce_icon.gif";s:4:"fe29";s:14:"pi3/ce_wiz.gif";s:4:"8c4b";s:31:"pi3/class.tx_imagecycle_pi3.php";s:4:"bbcd";s:39:"pi3/class.tx_imagecycle_pi3_wizicon.php";s:4:"94a4";s:19:"pi3/flexform_ds.xml";s:4:"6161";s:17:"pi3/locallang.xml";s:4:"c159";s:15:"pi4/ce_icon.gif";s:4:"d9d5";s:14:"pi4/ce_wiz.gif";s:4:"d451";s:31:"pi4/class.tx_imagecycle_pi4.php";s:4:"2684";s:39:"pi4/class.tx_imagecycle_pi4_wizicon.php";s:4:"5481";s:19:"pi4/flexform_ds.xml";s:4:"3449";s:17:"pi4/locallang.xml";s:4:"1806";s:15:"pi5/ce_icon.gif";s:4:"a4db";s:14:"pi5/ce_wiz.gif";s:4:"6f80";s:31:"pi5/class.tx_imagecycle_pi5.php";s:4:"9435";s:39:"pi5/class.tx_imagecycle_pi5_wizicon.php";s:4:"6a56";s:19:"pi5/flexform_ds.xml";s:4:"8118";s:17:"pi5/locallang.xml";s:4:"9fa2";s:20:"res/tx_imagecycle.js";s:4:"4368";s:17:"res/css/style.css";s:4:"130d";s:28:"res/css/nivoslider/style.css";s:4:"3b5c";s:33:"res/css/nivoslider/bar/arrows.png";s:4:"09b2";s:34:"res/css/nivoslider/bar/bullets.png";s:4:"acc6";s:34:"res/css/nivoslider/bar/loading.gif";s:4:"dd6b";s:32:"res/css/nivoslider/bar/style.css";s:4:"bcea";s:34:"res/css/nivoslider/dark/arrows.png";s:4:"4d2e";s:35:"res/css/nivoslider/dark/bullets.png";s:4:"208e";s:35:"res/css/nivoslider/dark/loading.gif";s:4:"dd6b";s:33:"res/css/nivoslider/dark/style.css";s:4:"df96";s:37:"res/css/nivoslider/default/arrows.png";s:4:"09b2";s:38:"res/css/nivoslider/default/bullets.png";s:4:"acc6";s:38:"res/css/nivoslider/default/loading.gif";s:4:"dd6b";s:36:"res/css/nivoslider/default/style.css";s:4:"569c";s:35:"res/css/nivoslider/light/arrows.png";s:4:"246c";s:36:"res/css/nivoslider/light/bullets.png";s:4:"8be8";s:36:"res/css/nivoslider/light/loading.gif";s:4:"dd6b";s:34:"res/css/nivoslider/light/style.css";s:4:"d023";s:35:"res/css/nivoslider/orman/arrows.png";s:4:"1429";s:36:"res/css/nivoslider/orman/bullets.png";s:4:"637a";s:36:"res/css/nivoslider/orman/loading.gif";s:4:"c339";s:35:"res/css/nivoslider/orman/readme.txt";s:4:"6643";s:35:"res/css/nivoslider/orman/ribbon.png";s:4:"9972";s:35:"res/css/nivoslider/orman/slider.png";s:4:"88e0";s:34:"res/css/nivoslider/orman/style.css";s:4:"c3b1";s:37:"res/css/nivoslider/pascal/bullets.png";s:4:"8680";s:40:"res/css/nivoslider/pascal/controlnav.png";s:4:"47f7";s:38:"res/css/nivoslider/pascal/featured.png";s:4:"dd34";s:37:"res/css/nivoslider/pascal/loading.gif";s:4:"c339";s:36:"res/css/nivoslider/pascal/readme.txt";s:4:"5884";s:36:"res/css/nivoslider/pascal/ribbon.png";s:4:"dd34";s:36:"res/css/nivoslider/pascal/slider.png";s:4:"54d9";s:35:"res/css/nivoslider/pascal/style.css";s:4:"f17b";s:26:"res/css/slicebox/style.css";s:4:"6c26";s:31:"res/css/slicebox/images/nav.png";s:4:"67d4";s:35:"res/css/slicebox/images/options.png";s:4:"c36d";s:34:"res/css/slicebox/images/shadow.png";s:4:"e924";s:28:"res/img/controller-first.gif";s:4:"f421";s:27:"res/img/controller-last.gif";s:4:"11ea";s:27:"res/img/controller-next.gif";s:4:"2eec";s:28:"res/img/controller-pause.gif";s:4:"afd4";s:27:"res/img/controller-prev.gif";s:4:"8965";s:22:"res/img/controller.png";s:4:"6873";s:34:"res/jquery/js/jquery-1.10.2.min.js";s:4:"6280";s:40:"res/jquery/js/jquery.coinslider-1.0.a.js";s:4:"921a";s:44:"res/jquery/js/jquery.coinslider-1.0.a.min.js";s:4:"2b41";s:44:"res/jquery/js/jquery.crossslide-0.6.2.min.js";s:4:"4ec5";s:39:"res/jquery/js/jquery.cycle.all-3.0.3.js";s:4:"7e17";s:43:"res/jquery/js/jquery.cycle.all-3.0.3.min.js";s:4:"6c56";s:34:"res/jquery/js/jquery.easing-1.3.js";s:4:"6516";s:38:"res/jquery/js/jquery.nivoslider-3.2.js";s:4:"d335";s:42:"res/jquery/js/jquery.nivoslider-3.2.min.js";s:4:"cfef";s:38:"res/jquery/js/jquery.slicebox-1.1.0.js";s:4:"cf17";s:42:"res/jquery/js/jquery.slicebox-1.1.0.min.js";s:4:"aefe";s:39:"res/jquery/js/modernizr.custom.28481.js";s:4:"a985";s:39:"res/jquery/js/modernizr.custom.46884.js";s:4:"1de5";s:20:"static/constants.txt";s:4:"9107";s:16:"static/setup.txt";s:4:"0105";s:31:"static/coinslider/constants.txt";s:4:"6713";s:27:"static/coinslider/setup.txt";s:4:"5d3a";s:31:"static/crossslide/constants.txt";s:4:"20b7";s:27:"static/crossslide/setup.txt";s:4:"b448";s:31:"static/nivoslider/constants.txt";s:4:"b8d8";s:27:"static/nivoslider/setup.txt";s:4:"d561";s:29:"static/slicebox/constants.txt";s:4:"1bed";s:25:"static/slicebox/setup.txt";s:4:"1a06";s:27:"static/tt_content/setup.txt";s:4:"ecd0";s:24:"static/tt_news/setup.txt";s:4:"077a";s:35:"static/tt_news/nivoslider/setup.txt";s:4:"f172";}',
	'suggests' => array(
	),
);

?>