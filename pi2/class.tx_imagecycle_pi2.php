<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Juergen Furrer <juergen.furrer@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(t3lib_extMgm::extPath('imagecycle').'pi1/class.tx_imagecycle_pi1.php');

/**
 * Plugin 'Coin-Slider' for the 'imagecycle' extension.
 *
 * @author	Juergen Furrer <juergen.furrer@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_imagecycle
 */
class tx_imagecycle_pi2 extends tx_imagecycle_pi1
{
	public $prefixId      = 'tx_imagecycle_pi2';
	public $scriptRelPath = 'pi2/class.tx_imagecycle_pi2.php';
	public $extKey        = 'imagecycle';
	public $pi_checkCHash = true;
	public $images        = array();
	public $hrefs         = array();
	public $captions      = array();
	public $type          = 'normal';
	protected $lConf      = array();
	protected $contentKey = null;
	protected $jsFiles    = array();
	protected $js         = array();
	protected $cssFiles   = array();
	protected $css        = array();
	protected $imageDir   = 'uploads/tx_imagecycle/';
	protected $templateFileJS = null;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	public function main($content, $conf)
	{
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		// define the key of the element
		$this->setContentKey("imagecycle-coin");

		// set the system language
		$this->sys_language_uid = $GLOBALS['TSFE']->sys_language_content;

		if ($this->cObj->data['list_type'] == $this->extKey.'_pi2') {
			$this->type = 'normal';

			// It's a content, all data from flexform

			$this->lConf['mode']          = $this->getFlexformData('general', 'mode');
			$this->lConf['images']        = $this->getFlexformData('general', 'images', ($this->lConf['mode'] == 'upload'));
			$this->lConf['hrefs']         = $this->getFlexformData('general', 'hrefs', ($this->lConf['mode'] == 'upload'));
			$this->lConf['captions']      = $this->getFlexformData('general', 'captions', ($this->lConf['mode'] == 'upload'));
			$this->lConf['damimages']     = $this->getFlexformData('general', 'damimages', ($this->lConf['mode'] == 'dam'));
			$this->lConf['damcategories'] = $this->getFlexformData('general', 'damcategories', ($this->lConf['mode'] == 'dam_catedit'));

			$imagesRTE = $this->getFlexformData('general', 'imagesRTE', ($this->lConf['mode'] == 'uploadRTE'));
			$this->lConf['imagesRTE'] = array();
			if (isset($imagesRTE['el']) && count($imagesRTE['el']) > 0) {
				foreach ($imagesRTE['el'] as $elKey => $el) {
					if (is_numeric($elKey)) {
						$this->lConf['imagesRTE'][] = array(
							"image"   => $el['data']['el']['image']['vDEF'],
							"href"    => $el['data']['el']['href']['vDEF'],
							"caption" => $this->pi_RTEcssText($el['data']['el']['caption']['vDEF']),
						);
					}
				}
			}

			$this->lConf['coinEffect']     = $this->getFlexformData('settings', 'coinEffect');
			$this->lConf['imagewidth']     = $this->getFlexformData('settings', 'imagewidth');
			$this->lConf['imageheight']    = $this->getFlexformData('settings', 'imageheight');
			$this->lConf['coinSpw']        = $this->getFlexformData('settings', 'coinSpw');
			$this->lConf['coinSph']        = $this->getFlexformData('settings', 'coinSph');
			$this->lConf['coinDelay']      = $this->getFlexformData('settings', 'coinDelay');
			$this->lConf['coinSDelay']     = $this->getFlexformData('settings', 'coinSDelay');
			$this->lConf['coinTitleSpeed'] = $this->getFlexformData('settings', 'coinTitleSpeed');
			$this->lConf['coinOpacity']    = $this->getFlexformData('settings', 'coinOpacity');
			$this->lConf['coinNavigation'] = $this->getFlexformData('settings', 'coinNavigation');
			$this->lConf['coinLinks']      = $this->getFlexformData('settings', 'coinLinks');
			$this->lConf['coinHoverPause'] = $this->getFlexformData('settings', 'coinHoverPause');

			$this->lConf['options']         = $this->getFlexformData('special', 'options');
			$this->lConf['optionsOverride'] = $this->getFlexformData('special', 'optionsOverride');

			// define the key of the element
			$this->setContentKey("imagecycle-coin_c" . $this->cObj->data['uid']);

			// define the images
			switch ($this->lConf['mode']) {
				case "" : {}
				case "folder" : {}
				case "upload" : {
					$this->setDataUpload();
					break;
				}
				case "uploadRTE" : {
					$this->setDataUploadRTE();
					break;
				}
				case "dam" : {
					$this->setDataDam(false, 'tt_content', $this->cObj->data['uid']);
					break;
				}
				case "dam_catedit" : {
					$this->setDataDam(true, 'tt_content', $this->cObj->data['uid']);
					break;
				}
			}
			// Override the config with flexform data
			if ($this->lConf['coinEffect']) {
				$this->conf['coinEffect'] = $this->lConf['coinEffect'];
			}
			if ($this->lConf['imagewidth']) {
				$this->conf['imagewidth'] = $this->lConf['imagewidth'];
			}
			if ($this->lConf['imageheight']) {
				$this->conf['imageheight'] = $this->lConf['imageheight'];
			}
			if ($this->lConf['coinSpw']) {
				$this->conf['coinSpw'] = $this->lConf['coinSpw'];
			}
			if ($this->lConf['coinSph']) {
				$this->conf['coinSph'] = $this->lConf['coinSph'];
			}
			if (is_numeric($this->lConf['coinDelay']) && $this->lConf['coinDelay'] != 0) {
				$this->conf['coinDelay'] = $this->lConf['coinDelay'];
			}
			if (is_numeric($this->lConf['coinSDelay']) && $this->lConf['coinSDelay'] != 0) {
				$this->conf['coinSDelay'] = $this->lConf['coinSDelay'];
			}
			if ($this->lConf['coinOpacity']) {
				$this->conf['coinOpacity'] = $this->lConf['coinOpacity'];
			}
			if (is_numeric($this->lConf['coinTitleSpeed']) && $this->lConf['coinTitleSpeed'] != 0) {
				$this->conf['coinTitleSpeed'] = $this->lConf['coinTitleSpeed'];
			}
			// Will be overridden, if not "from TS"
			if ($this->lConf['coinNavigation'] < 2) {
				$this->conf['coinNavigation'] = $this->lConf['coinNavigation'];
			}
			if ($this->lConf['coinLinks'] < 2) {
				$this->conf['coinLinks'] = $this->lConf['coinLinks'];
			}
			if ($this->lConf['coinHoverPause'] < 2) {
				$this->conf['coinHoverPause'] = $this->lConf['coinHoverPause'];
			}
			$this->conf['options'] = $this->lConf['options'];
		} else {
			$this->type = 'header';
			// It's the header
			$used_page = array();
			$pageID    = false;
			foreach ($GLOBALS['TSFE']->rootLine as $page) {
				if (! $pageID) {
					if (trim($page['tx_imagecycle_effect']) && ! $this->conf['disableRecursion']) {
						$this->conf['type'] = $page['tx_imagecycle_effect'];
					}
					if (
						(($page['tx_imagecycle_mode'] == 'upload' || ! $page['tx_imagecycle_mode']) && trim($page['tx_imagecycle_images']) != '') ||
						($page['tx_imagecycle_mode'] == 'dam'         && trim($page['tx_imagecycle_damimages']) != '') ||
						($page['tx_imagecycle_mode'] == 'dam_catedit' && trim($page['tx_imagecycle_damcategories']) != '') ||
						$this->conf['disableRecursion'] ||
						$page['tx_imagecycle_stoprecursion']
					) {
						$used_page = $page;
						$pageID    = $used_page['uid'];
						$this->lConf['mode']          = $used_page['tx_imagecycle_mode'];
						$this->lConf['damcategories'] = $used_page['tx_imagecycle_damcategories'];
					}
				}
			}
			if ($pageID) {
				if ($this->sys_language_uid) {
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_imagecycle_images, tx_imagecycle_hrefs, tx_imagecycle_captions','pages_language_overlay','pid='.intval($pageID).' AND sys_language_uid='.$this->sys_language_uid,'','',1);
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					if (trim($used_page['tx_imagecycle_effect'])) {
						$this->conf['type'] = $row['tx_imagecycle_effect'];
					}
				}
				// define the images
				switch ($this->lConf['mode']) {
					case "" : {}
					case "folder" : {}
					case "upload" : {
						$this->images   = t3lib_div::trimExplode(',',     $used_page['tx_imagecycle_images']);
						$this->hrefs    = t3lib_div::trimExplode(chr(10), $used_page['tx_imagecycle_hrefs']);
						$this->captions = t3lib_div::trimExplode(chr(10), $used_page['tx_imagecycle_captions']);
						// Language overlay
						if ($this->sys_language_uid) {
							if (trim($row['tx_imagecycle_images']) != '') {
								$this->images   = t3lib_div::trimExplode(',',     $row['tx_imagecycle_images']);
								$this->hrefs    = t3lib_div::trimExplode(chr(10), $row['tx_imagecycle_hrefs']);
								$this->captions = t3lib_div::trimExplode(chr(10), $row['tx_imagecycle_captions']);
							}
						}
						break;
					}
					case "dam" : {
						$this->setDataDam(false, 'pages', $pageID);
						break;
					}
					case "dam_catedit" : {
						$this->setDataDam(true, 'pages', $pageID);
						break;
					}
				}
			}
		}

		$data = array();
		foreach ($this->images as $key => $image) {
			$data[$key]['image']   = $image;
			$data[$key]['href']    = $this->hrefs[$key];
			$data[$key]['caption'] = $this->captions[$key];
		}

		return $this->parseTemplate($data);
	}

	/**
	 * Parse all images into the template
	 * @param $data
	 * @return string
	 */
	public function parseTemplate($data=array(), $dir='', $onlyJS=false)
	{
		// define the directory of images
		if ($dir == '') {
			$dir = $this->imageDir;
		}

		// Check if $data is array
		if (count($data) == 0 && $onlyJS === false) {
			return false;
		}

		// define the contentKey if not exist
		if ($this->getContentKey() == '') {
			$this->setContentKey("imagecycle-coin_key");
		}

		if (! $this->conf['coinEffect']) {
			$this->conf['coinEffect'] = "random";
		}
		if (! $this->conf['imagewidth']) {
			$this->conf['imagewidth'] = "200c";
		}
		if (! $this->conf['imageheight']) {
			$this->conf['imageheight'] = "200c";
		}

		// We have to build the images first to get the maximum width and height
		$returnString = null;
		$images = null;
		$maxWidth = 0;
		$maxHeight = 0;
		$no_script = null;
		$GLOBALS['TSFE']->register['key'] = $this->getContentKey();
		$GLOBALS['TSFE']->register['imagewidth']  = $this->conf['imagewidth'];
		$GLOBALS['TSFE']->register['imageheight'] = $this->conf['imageheight'];
		$GLOBALS['TSFE']->register['showcaption'] = $this->conf['showcaption'];
		$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = 0;
		$GLOBALS['TSFE']->register['IMAGE_COUNT'] = count($data);
		if (count($data) > 0) {
			foreach ($data as $key => $item) {
				$image = null;
				$imgConf = $this->conf['coin.'][$this->type.'.']['image.'];
				$totalImagePath = $dir . $item['image'];
				$GLOBALS['TSFE']->register['file']    = $totalImagePath;
				$GLOBALS['TSFE']->register['href']    = $item['href'];
				$GLOBALS['TSFE']->register['caption'] = $item['caption'];
				$GLOBALS['TSFE']->register['CURRENT_ID'] = $GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] + 1;
				if ($this->hrefs[$key]) {
					// this is not nice, the imageLinkWrap would be my 1st choice
					unset($imgConf['imageLinkWrap.']);
					$image = $this->cObj->IMAGE($imgConf);
					$imageWrap = $this->cObj->stdWrap($image, $this->conf['coin.'][$this->type.'.']['imageWrap.']);
					$imageLink = $this->cObj->typolink($imageWrap, $this->conf['coin.'][$this->type.'.']['imageLink.']);
				} else {
					$imageLink = $this->cObj->IMAGE($imgConf);
					if ($this->cObj->data['image_zoom'] != 1) {
						// if the image_zoom is activated, the caption have to be rendered
						$imageLink = $this->cObj->stdWrap($imageLink, $this->conf['coin.'][$this->type.'.']['imageWrap.']);
					}
				}
				$lastImageInfo = $GLOBALS['TSFE']->lastImageInfo;
				if ($lastImageInfo[0] > $maxWidth) {
					$maxWidth = $lastImageInfo[0];
				}
				if ($lastImageInfo[1] > $maxHeight) {
					$maxHeight = $lastImageInfo[1];
				}
				// Add the noscript wrap to the firs image
				if ($key == 0) {
					$no_script = $this->cObj->stdWrap($imageLink, $this->conf['coin.'][$this->type.'.']['noscriptWrap.']);
				}
				$images .= $imageLink;
				$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] ++;
			}
			// the stdWrap
			$returnString = $this->cObj->stdWrap($images, $this->conf['coin.'][$this->type.'.']['stdWrap.']);
			$returnString .= $no_script;
		}

		// The template for JS
		if (! $this->templateFileJS = $this->cObj->fileResource($this->conf['templateFileJS'])) {
			$this->templateFileJS = $this->cObj->fileResource("EXT:imagecycle/res/tx_imagecycle.js");
		}

		// define the jQuery mode and function
		if ($this->conf['jQueryNoConflict']) {
			$jQueryNoConflict = "jQuery.noConflict();";
		} else {
			$jQueryNoConflict = "";
		}

		$options = array();

		$options['effect'] = "effect: '{$this->conf['coinEffect']}'";
		$options['width']  = "width: '{$maxWidth}'";
		$options['height'] = "height: '{$maxHeight}'";

		$this->addCSS("
#c{$this->cObj->data['uid']} {
	width: {$maxWidth}px;
}");

		if ($this->conf['coinSpw'] > 0) {
			$options['spw'] = "spw: '{$this->conf['coinSpw']}'";
		}
		if ($this->conf['coinSph'] > 0) {
			$options['sph'] = "sph: '{$this->conf['coinSph']}'";
		}
		if (is_numeric($this->conf['coinDelay']) && $this->conf['coinDelay'] != 0) {
			$options['delay'] = "delay: {$this->conf['coinDelay']}";
		}
		if (is_numeric($this->conf['coinSDelay']) && $this->conf['coinSDelay'] != 0) {
			$options['sDelay'] = "sDelay: {$this->conf['coinSDelay']}";
		}
		if (is_numeric($this->conf['coinOpacity'])) {
			$options['opacity'] = "opacity: {$this->conf['coinOpacity']}";
		}
		if (is_numeric($this->conf['coinTitleSpeed']) && $this->conf['coinTitleSpeed'] != 0) {
			$options['titleSpeed'] = "titleSpeed: {$this->conf['coinTitleSpeed']}";
		}
		$options['navigation'] = "navigation: ".($this->conf['coinNavigation'] ? 'true' : 'false');
		$options['links']      = "links: ".($this->conf['coinLinks'] ? 'true' : 'false');
		$options['hoverPause'] = "hoverPause: ".($this->conf['coinHoverPause'] ? 'true' : 'false');
		$options['prev']       = "prev: '".$this->pi_getLL('prev')."'";
		$options['next']       = "next: '".$this->pi_getLL('next')."'";

		// overwrite all options if set
		if (trim($this->conf['options'])) {
			if ($this->conf['optionsOverride']) {
				$options = array($this->conf['options']);
			} else {
				$options['options'] = $this->conf['options'];
			}
		}

		// define the js file
		$this->addJsFile($this->conf['jQueryCoin']);

		// get the Template of the Javascript
		if (! $templateCode = trim($this->cObj->getSubpart($this->templateFileJS, "###TEMPLATE_COINSLIDER_JS###"))) {
			$templateCode = "alert('Template TEMPLATE_COINSLIDER_JS is missing')";
		}

		// define the markers
		$markerArray = array();
		$markerArray["KEY"]     = $this->getContentKey();
		$markerArray["OPTIONS"] = implode(",\n		", $options);

		// set the markers
		$templateCode = $this->cObj->substituteMarkerArray($templateCode, $markerArray, '###|###', 0);

		$this->addJS($jQueryNoConflict . $templateCode);

		// Add the ressources
		$this->addResources();

		if ($onlyJS === true) {
			return true;
		}

		return $returnString;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/pi2/class.tx_imagecycle_pi2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/pi2/class.tx_imagecycle_pi2.php']);
}

?>