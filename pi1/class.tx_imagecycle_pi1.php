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

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Image Cycle' for the 'imagecycle' extension.
 *
 * @author	Juergen Furrer <juergen.furrer@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_imagecycle
 */
class tx_imagecycle_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_imagecycle_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_imagecycle_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'imagecycle';	// The extension key.
	var $pi_checkCHash = true;
	var $lConf = array();
	var $templateFile = null;
	var $contentKey = null;
	var $jsFiles = array();
	var $js = array();
	var $cssFiles = array();
	var $css = array();
	var $images = array();
	var $hrefs = array();
	var $captions = array();
	var $imageDir = 'uploads/tx_imagecycle/';

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

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

		// add the CSS file
		$this->addCssFile($this->conf['cssFile']);

		// define the key of the element
		$this->contentKey = "imagecycle_c" . $this->cObj->data['uid'];

		// define th images
		if ($this->lConf['images']) {
			$this->images = t3lib_div::trimExplode(',', $this->lConf['images']);
		}
		// define the hrefs
		if ($this->lConf['hrefs']) {
			$this->hrefs = t3lib_div::trimExplode(chr(10), $this->lConf['hrefs']);
		}

		// define the captions
		if ($this->lConf['captions'] && $this->lConf['showcaption']) {
			$this->captions = t3lib_div::trimExplode(chr(10), $this->lConf['captions']);
		}

		// define the jQuery mode and function
		if ($this->conf['jQueryNoConflict']) {
			$jQueryNoConflict = "jQuery.noConflict();";
			$jQuery = "jQuery";
		} else {
			$jQueryNoConflict = "";
			$jQuery = "$";
		}

		// The template
		if ($this->conf['templateFile']) {
			$this->templateFile = $this->cObj->fileResource($this->conf['templateFile']);
		} else {
			return "<p>NO TEMPLATE FOUND!</p>";
		}

		// define the js files
		$this->addJsFile("EXT:imagecycle/res/jquery/js/jquery.cycle.all.min.js");

		// get the options from flexform
		$options = array();
		if (! $this->lConf['imagewidth']) {
			$this->lConf['imagewidth'] = ($this->conf['imagewidth'] ? $this->conf['imagewidth'] : "200c");
		}
		if (! $this->lConf['imageheight']) {
			$this->lConf['imageheight'] = ($this->conf['imageheight'] ? $this->conf['imageheight'] : "200c");
		}

		if ($this->lConf['type']) {
			$options[] = "fx: '{$this->lConf['type']}'";
		}

		if ($this->lConf['transitiondir'] && $this->lConf['transition']) {
			$options[] = "easing: 'ease{$this->lConf['transitiondir']}{$this->lConf['transition']}'";
		}

		if ($this->lConf['transitionduration']) {
			$options[] = "speed: '{$this->lConf['transitionduration']}'";
		}

		if ($this->lConf['displayduration']) {
			$options[] = "timeout: '{$this->lConf['displayduration']}'";
		}

		$options[] = "sync: ".($this->lConf['sync'] ? 'true' : 'false');
		$options[] = "random: ".($this->lConf['random'] ? 'true' : 'false');

		$this->addJS(
$jQueryNoConflict . "
{$jQuery}(document).ready(function() {
	{$jQuery}('#{$this->contentKey}').cycle(".(count($options) ? "{\n		".implode(",\n		", $options)."\n	}" : "").");
});");

		preg_match("/^([0-9]*)/i", $this->lConf['imagewidth'], $reg_width);
		preg_match("/^([0-9]*)/i", $this->lConf['imageheight'], $reg_height);

		// Add the ressources
		$this->addResources();

		// Render the Template
		$markerArray = array();
		// get the template
		$templateCode = $this->cObj->getSubpart($this->templateFile, "###TEMPLATE_CYCLE###");
		// Get the images template
		$imagesCode = $this->cObj->getSubpart($templateCode, "###IMAGES###");
		// Replace default values
		$markerArray["KEY"] = $this->contentKey;
		$markerArray["CLASS"] = $skin_class;
		$templateCode = $this->cObj->substituteMarkerArray($templateCode, $markerArray, '###|###', 0);
		if (count($this->images) < 1) {
			return '<p>NOTHING TO DISPLAY</p>';
		} else {
			foreach ($this->images as $key => $image) {
				$markerArray = array();
				$image_config = array();
				// render the image to the gifen size
				$image_config['img'] = 'IMAGE';
				if (! $this->hrefs[$key]) {
					$image_config['img.'] = $GLOBALS['TSFE']->tmpl->setup['tt_content.']['image.']['20.']['1.'];
					unset($image_config['img.']['file.']['import.']);
					unset($image_config['img.']['altText.']);
					unset($image_config['img.']['titleText.']);
					unset($image_config['img.']['file.']['width.']);
				}
				$image_config['img.']['file'] = $this->imageDir . $image;
				$image_config['img.']['file.']['width']  = $this->lConf['imagewidth'];
				$image_config['img.']['file.']['height'] = $this->lConf['imageheight'];
				$image_config['img.']['altText'] = $this->captions[$key];
				$image_config['img.']['altText.']['stripHtml'] = 1;
				$image_config['img.']['titleText'] = $this->captions[$key];
				$image_config['img.']['titleText.']['stripHtml'] = 1;
				$image = $this->cObj->IMAGE($image_config['img.']);
				if ($this->hrefs[$key]) {
					$link_config = array(
						'parameter' => $this->hrefs[$key],
						'title' =>     $this->captions[$key],
					);
					$markerArray["IMAGE"] = $this->cObj->typolink($image, $link_config);
				} else {
					$markerArray["IMAGE"] = $image;
				}
				$images .= $this->cObj->substituteMarkerArray($imagesCode, $markerArray, '###|###', 0);
			}
			$return_string = $templateCode;
			$return_string = $this->cObj->substituteSubpart($return_string, '###IMAGES###', $images, 0);
		}

		return $this->pi_wrapInBaseClass($return_string);
	}


	/**
	 * Include all defined resources (JS / CSS)
	 *
	 * @return void
	 */
	function addResources() {
		// checks if t3jquery is loaded
		if (T3JQUERY === true) {
			tx_t3jquery::addJqJS();
		} else {
			$this->addJsFile($this->conf['jQueryLibrary'], true);
			$this->addJsFile("EXT:imagecycle/res/jquery/js/jquery.easing-1.3.js");
		}
		// add all defined JS files
		if (count($this->jsFiles) > 0) {
			foreach ($this->jsFiles as $jsToLoad) {
				// Add script only once
				if (! preg_match("/".preg_quote($this->getPath($jsToLoad), "/")."/", $GLOBALS['TSFE']->additionalHeaderData['jsFile_'.$this->extKey])) {
					$GLOBALS['TSFE']->additionalHeaderData['jsFile_'.$this->extKey] .= ($this->getPath($jsToLoad) ? '<script src="'.$this->getPath($jsToLoad).'" type="text/javascript"></script>'.chr(10) :'');
				}
			}
		}
		// add all defined JS Script
		if (count($this->js) > 0) {
			foreach ($this->js as $jsToPut) {
				$temp_js .= $jsToPut;
			}
			$GLOBALS['TSFE']->additionalHeaderData['js_'.$this->extKey] .= t3lib_div::wrapJS($temp_js, true);
		}
		// add all defined CSS files
		if (count($this->cssFiles) > 0) {
			foreach ($this->cssFiles as $cssToLoad) {
				// Add script only once
				if (! preg_match("/".preg_quote($this->getPath($cssToLoad), "/")."/", $GLOBALS['TSFE']->additionalHeaderData['cssFile_'.$this->extKey])) {
					$GLOBALS['TSFE']->additionalHeaderData['cssFile_'.$this->extKey] .= ($this->getPath($cssToLoad) ? '<link rel="stylesheet" href="'.$this->getPath($cssToLoad).'" type="text/css" />'.chr(10) :'');
				}
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

	/**
	 * Return the webbased path
	 * 
	 * @param string $path
	 * return string
	 */
	function getPath($path="")
	{
		return $GLOBALS['TSFE']->tmpl->getFileName($path);
	}

	/**
	 * Add additional JS file
	 * 
	 * @param string $script
	 * @param boolean $first
	 * @return void
	 */
	function addJsFile($script="", $first=false)
	{
		$script = t3lib_div::fixWindowsFilePath($script);
		if ($this->getPath($script) && ! in_array($script, $this->jsFiles)) {
			if ($first === true) {
				$this->jsFiles = array_merge(array($script), $this->jsFiles);
			} else {
				$this->jsFiles[] = $script;
			}
		}
	}

	/**
	 * Add JS to header
	 * 
	 * @param string $script
	 * @return void
	 */
	function addJS($script="")
	{
		if (! in_array($script, $this->js)) {
			$this->js[] = $script;
		}
	}

	/**
	 * Add additional CSS file
	 * 
	 * @param string $script
	 * @return void
	 */
	function addCssFile($script="")
	{
		$script = t3lib_div::fixWindowsFilePath($script);
		if ($this->getPath($script) && ! in_array($script, $this->cssFiles)) {
			$this->cssFiles[] = $script;
		}
	}

	/**
	 * Add CSS to header
	 * 
	 * @param string $script
	 * @return void
	 */
	function addCSS($script="")
	{
		if (! in_array($script, $this->css)) {
			$this->css[] = $script;
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/pi1/class.tx_imagecycle_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/pi1/class.tx_imagecycle_pi1.php']);
}

?>