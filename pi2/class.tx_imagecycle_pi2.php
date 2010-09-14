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
 * Plugin 'Image Cycle' for the 'imagecycle' extension.
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
		$this->setContentKey("imagecycle");

		// set the system language
		$this->sys_language_uid = $GLOBALS['TSFE']->sys_language_content;

		if ($this->cObj->data['list_type'] == $this->extKey.'_pi2') {
			$this->type = 'normal';
			// It's a content, al data from flexform
			// Set the Flexform information
			$this->pi_initPIflexForm();
			$piFlexForm = $this->cObj->data['pi_flexform'];
			foreach ($piFlexForm['data'] as $sheet => $data) {
				foreach ($data as $lang => $value) {
					foreach ($value as $key => $val) {
						$this->lConf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
					}
				}
			}

			// define the key of the element
			$this->setContentKey("imagecycle_c" . $this->cObj->data['uid']);

			// define the images
			switch ($this->lConf['mode']) {
				case "" : {}
				case "folder" : {}
				case "upload" : {
					$this->setDataUpload();
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
			if ($this->lConf['imagewidth']) {
				$this->conf['imagewidth'] = $this->lConf['imagewidth'];
			}
			if ($this->lConf['imageheight']) {
				$this->conf['imageheight'] = $this->lConf['imageheight'];
			}
			if ($this->lConf['type']) {
				$this->conf['type'] = $this->lConf['type'];
			}
			if ($this->lConf['transition']) {
				$this->conf['transition'] = $this->lConf['transition'];
			}
			if ($this->lConf['transitiondir']) {
				$this->conf['transitionDir'] = $this->lConf['transitiondir'];
			}
			if ($this->lConf['transitionduration']) {
				$this->conf['transitionDuration'] = $this->lConf['transitionduration'];
			}
			if ($this->lConf['displayduration']) {
				$this->conf['displayDuration'] = $this->lConf['displayduration'];
			}
			if (is_numeric($this->lConf['delayduration']) && $this->lConf['delayduration'] != 0) {
				$this->conf['delayDuration'] = $this->lConf['delayduration'];
			}
			$this->conf['showcaption'] = $this->lConf['showcaption'];
			$this->conf['showControl'] = $this->lConf['showControl'];
			$this->conf['stopOnMousover'] = $this->lConf['stoponmousover'];
			$this->conf['pausedBegin'] = $this->lConf['pausedBegin'];
			$this->conf['sync'] = $this->lConf['sync'];
			$this->conf['random'] = $this->lConf['random'];
			$this->conf['options'] = $this->lConf['options'];
			$this->conf['showPager'] = $this->lConf['showPager'];
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
							$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_imagecycle_images, tx_imagecycle_hrefs, tx_imagecycle_captions','pages_language_overlay','pid='.intval($pageID).' AND sys_language_uid='.$this->sys_language_uid,'','',1);
							$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
							if (trim($used_page['tx_imagecycle_effect'])) {
								$this->conf['type'] = $row['tx_imagecycle_effect'];
							}
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
			$data[$key]['caption'] = ($this->conf['showcaption'] ? $this->captions[$key] : '');
		}

		return $this->pi_wrapInBaseClass($this->parseTemplate($data));
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
			$this->setContentKey("imagecycle_key");
		}

		// define the jQuery mode and function
		if ($this->conf['jQueryNoConflict']) {
			$jQueryNoConflict = "jQuery.noConflict();";
		} else {
			$jQueryNoConflict = "";
		}

		$options = array();

		if (! $this->conf['imagewidth']) {
			$this->conf['imagewidth'] = ($this->conf['imagewidth'] ? $this->conf['imagewidth'] : "200c");
		}
		if (! $this->conf['imageheight']) {
			$this->conf['imageheight'] = ($this->conf['imageheight'] ? $this->conf['imageheight'] : "200c");
		}
		if ($this->conf['type']) {
			$options['fx'] = "fx: '{$this->conf['type']}'";
		}
		if ($this->conf['transitionDir'] && $this->conf['transition']) {
			$options['easing'] = "easing: 'ease{$this->conf['transitionDir']}{$this->conf['transition']}'";
		}
		if ($this->conf['transitionDuration'] > 0) {
			$options['speed'] = "speed: '{$this->conf['transitionDuration']}'";
		}
		if ($this->conf['displayDuration'] > 0) {
			$options['timeout'] = "timeout: '{$this->conf['displayDuration']}'";
		}
		if (is_numeric($this->conf['delayDuration']) && $this->conf['delayDuration'] != 0) {
			$options['delay'] = "delay: {$this->conf['delayDuration']}";
		}
		if ($this->conf['stopOnMousover']) {
			$options['pause'] = "pause: true";
		}
		$options['sync'] = "sync: ".($this->conf['sync'] ? 'true' : 'false');
		$options['random'] = "random: ".($this->conf['random'] ? 'true' : 'false');

		// add caption
		if ($this->conf['showcaption']) {
			// define the animation for the caption
			$fx = array();
			if (! $this->conf['captionAnimate']) {
				$before = "jQuery('span', this).css('display', 'none');";
				$after  = "jQuery('span', this).css('display', 'block');";
			} else {
				if ($this->conf['captionTypeOpacity']) {
					$fx[] = "opacity: 'show'";
				}
				if ($this->conf['captionTypeHeight']) {
					$fx[] = "height: 'show'";
				}
				if ($this->conf['captionTypeWidth']) {
					$fx[] = "width: 'show'";
				}
				// if no effect is choosen, opacity is the fallback
				if (count($fx) < 1) {
					$fx[] = "opacity: 'show'";
				}
				if (! is_numeric($this->conf['captionSpeed'])) {
					$this->conf['captionSpeed'] = 200;
				}
				$before = "jQuery('span', this).css('display', 'none');";
				$after  = "jQuery('span', this).animate({".(implode(",", $fx))."},{$this->conf['captionSpeed']});";
			}
			if ($this->conf['captionSync']) {
				$options['before'] = "before: function() {".$before."".$after."}";
			} else {
				$options['before'] = "before: function() {".$before."}";
				$options['after'] = "after:  function() {".$after."}";
			}
		}

		// overwrite all options if set
		if (trim($this->conf['options'])) {
			$options = array($this->conf['options']);
		}

		// define the js file
		$this->addJsFile($this->conf['jQueryCycle']);

		// define the css file
		$this->addCssFile($this->conf['cssFile']);

		// The template for JS
		if (! $this->templateFileJS = $this->cObj->fileResource($this->conf['templateFileJS'])) {
			$this->templateFileJS = $this->cObj->fileResource("EXT:imagecycle/res/tx_imagecycle_pi2.js");
		}
		// get the Template of the Javascript
		if (! $templateCode = trim($this->cObj->getSubpart($this->templateFileJS, "###TEMPLATE_JS###"))) {
			$templateCode = "alert('Template TEMPLATE_JS is missing')";
		}
		// set the key
		$markerArray = array();
		$markerArray["KEY"] = $this->getContentKey();
		$templateCode = $this->cObj->substituteMarkerArray($templateCode, $markerArray, '###|###', 0);
		// define the control
		if ($this->conf['showControl']) {
			$templateControl = trim($this->cObj->getSubpart($templateCode, "###CONTROL###"));
			$templateControlAfter = trim($this->cObj->getSubpart($templateCode, "###CONTROL_AFTER###"));
			$options[] = trim($this->cObj->getSubpart($templateCode, "###CONTROL_OPTIONS###"));
		} else {
			$templateControl = null;
		}
		$templateCode = $this->cObj->substituteSubpart($templateCode, '###CONTROL###', $templateControl, 0);
		$templateCode = $this->cObj->substituteSubpart($templateCode, '###CONTROL_AFTER###', $templateControlAfter, 0);
		$templateCode = $this->cObj->substituteSubpart($templateCode, '###CONTROL_OPTIONS###', '', 0);
		// define the play class
		if ($this->conf['pausedBegin']) {
			$templatePaused = $this->cObj->getSubpart($templateCode, "###PAUSED###");
			$templatePausedBegin = $this->cObj->getSubpart($templateCode, "###PAUSED_BEGIN###");;
		} else {
			$templatePaused = null;
			$templatePausedBegin = null;
		}
		$templateCode = $this->cObj->substituteSubpart($templateCode, '###PAUSED###', $templatePaused, 0);
		$templateCode = $this->cObj->substituteSubpart($templateCode, '###PAUSED_BEGIN###', $templatePausedBegin, 0);
		// define the pager
		if ($this->conf['showPager']) {
			$templatePager = $this->cObj->getSubpart($templateCode, "###PAGER###");
		} else {
			$templatePager = null;
		}
		$templateCode = $this->cObj->substituteSubpart($templateCode, '###PAGER###', $templatePager, 0);
		// define the markers
		$markerArray = array();
		$markerArray["OPTIONS"] = implode(",\n		", $options);
		// set the markers
		$templateCode = $this->cObj->substituteMarkerArray($templateCode, $markerArray, '###|###', 0);

		$this->addJS($jQueryNoConflict . $templateCode);

		// Add the ressources
		$this->addResources();

		if ($onlyJS === true) {
			return true;
		}

		$return_string = null;
		$images = null;
		$pager = null;
		$GLOBALS['TSFE']->register['key'] = $this->getContentKey();
		$GLOBALS['TSFE']->register['imagewidth']  = $this->conf['imagewidth'];
		$GLOBALS['TSFE']->register['imageheight'] = $this->conf['imageheight'];
		$GLOBALS['TSFE']->register['showcaption'] = $this->conf['showcaption'];
		$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = 0;
		if (count($data) > 0) {
			foreach ($data as $key => $item) {
				$image = null;
				$imgConf = $this->conf['cycle.'][$this->type.'.']['image.'];
				$totalImagePath = $dir . $item['image'];
				$GLOBALS['TSFE']->register['file']    = $totalImagePath;
				$GLOBALS['TSFE']->register['href']    = $item['href'];
				$GLOBALS['TSFE']->register['caption'] = $item['caption'];
				$GLOBALS['TSFE']->register['CURRENT_ID'] = $GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] + 1;
				if ($this->hrefs[$key]) {
					$imgConf['imageLinkWrap.'] = $imgConf['imageHrefWrap.'];
				}
				$image = $this->cObj->IMAGE($imgConf);
				$image = $this->cObj->typolink($image, $imgConf['imageLinkWrap.']);
				if ($item['caption'] && $this->conf['showcaption']) {
					$image = $this->cObj->stdWrap($image, $this->conf['cycle.'][$this->type.'.']['captionWrap.']);
				}
				$image = $this->cObj->stdWrap($image, $this->conf['cycle.'][$this->type.'.']['itemWrap.']);
				$images .= $image;
				// create the pager
				if ($this->conf['showPager']) {
					$pager .= trim($this->cObj->cObjGetSingle($this->conf['cycle.'][$this->type.'.']['pager'], $this->conf['cycle.'][$this->type.'.']['pager.']));
				}
				$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] ++;
			}
			$markerArray['PAGER'] = $this->cObj->stdWrap($pager, $this->conf['cycle.'][$this->type.'.']['pagerWrap.']);
			// the stdWrap
			$images = $this->cObj->stdWrap($images, $this->conf['cycle.'][$this->type.'.']['stdWrap.']);
			$return_string = $this->cObj->substituteMarkerArray($images, $markerArray, '###|###', 0);
		}
		return $return_string;
	}

	/**
	 * Include all defined resources (JS / CSS)
	 *
	 * @return void
	 */
	protected function addResources()
	{
		// checks if t3jquery is loaded
		if (T3JQUERY === true) {
			tx_t3jquery::addJqJS();
		} else {
			$this->addJsFile($this->conf['jQueryLibrary'], true);
			$this->addJsFile($this->conf['jQueryEasing']);
		}
		// Fix moveJsFromHeaderToFooter (add all scripts to the footer)
		if ($GLOBALS['TSFE']->config['config']['moveJsFromHeaderToFooter']) {
			$allJsInFooter = true;
		} else {
			$allJsInFooter = false;
		}
		// add all defined JS files
		if (count($this->jsFiles) > 0) {
			foreach ($this->jsFiles as $jsToLoad) {
				if (T3JQUERY === true) {
					tx_t3jquery::addJS('', array('jsfile' => $jsToLoad));
				} else {
					// Add script only once
					$hash = md5($this->getPath($jsToLoad));
					if ($allJsInFooter) {
						$GLOBALS['TSFE']->additionalFooterData['jsFile_'.$this->extKey.'_'.$hash] = ($this->getPath($jsToLoad) ? '<script src="'.$this->getPath($jsToLoad).'" type="text/javascript"></script>'.chr(10) : '');
					} else {
						$GLOBALS['TSFE']->additionalHeaderData['jsFile_'.$this->extKey.'_'.$hash] = ($this->getPath($jsToLoad) ? '<script src="'.$this->getPath($jsToLoad).'" type="text/javascript"></script>'.chr(10) : '');
					}
				}
			}
		}
		// add all defined JS script
		if (count($this->js) > 0) {
			foreach ($this->js as $jsToPut) {
				$temp_js .= $jsToPut;
			}
			if ($this->conf['jsMinify']) {
				$temp_js = t3lib_div::minifyJavaScript($temp_js);
			}
			$conf = array();
			$conf['jsdata'] = $temp_js;
			if (T3JQUERY === true && t3lib_div::int_from_ver($this->getExtensionVersion('t3jquery')) >= 1002000) {
				$conf['tofooter'] = ($this->conf['jsInFooter']);
				tx_t3jquery::addJS('', $conf);
			} else {
				// Add script only once
				$hash = md5($temp_js);
				if ($this->conf['jsInFooter'] || $allJsInFooter) {
					$GLOBALS['TSFE']->additionalFooterData['js_'.$this->extKey.'_'.$hash] = t3lib_div::wrapJS($temp_js, true);
				} else {
					$GLOBALS['TSFE']->additionalHeaderData['js_'.$this->extKey.'_'.$hash] = t3lib_div::wrapJS($temp_js, true);
				}
			}
		}
		// add all defined CSS files
		if (count($this->cssFiles) > 0) {
			foreach ($this->cssFiles as $cssToLoad) {
				// Add script only once
				$hash = md5($this->getPath($cssToLoad));
				$GLOBALS['TSFE']->additionalHeaderData['cssFile_'.$this->extKey.'_'.$hash] = ($this->getPath($cssToLoad) ? '<link rel="stylesheet" href="'.$this->getPath($cssToLoad).'" type="text/css" />'.chr(10) :'');
			}
		}
		// add all defined CSS Script
		if (count($this->css) > 0) {
			foreach ($this->css as $cssToPut) {
				$temp_css .= $cssToPut;
			}
			$GLOBALS['TSFE']->additionalHeaderData['css_'.$this->extKey] .= '
<style type="text/css">
' . $temp_css . '
</style>';
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/pi2/class.tx_imagecycle_pi2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/pi2/class.tx_imagecycle_pi2.php']);
}

?>