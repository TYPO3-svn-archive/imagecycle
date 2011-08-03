<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Juergen Furrer <juergen.furrer@gmail.com>
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

require_once (PATH_t3lib . 'class.t3lib_page.php');

/**
 * 'itemsProcFunc' for the 'imagecycle' extension.
 *
 * @author     Juergen Furrer <juergen.furrer@gmail.com>
 * @package    TYPO3
 * @subpackage tx_imagecycle
 */
class tx_imagecycle_itemsProcFunc
{
	/**
	 * Get defined Effects for dropdown
	 * @return array
	 */
	function getEffects($config, $item)
	{
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imagecycle']);
		$availableEffects = t3lib_div::trimExplode(",", $confArr['effects'], true);
		if (count($availableEffects) < 1) {
			$availableEffects = array('none','blindX','blindY','blindZ','cover','curtainX','curtainY','fade','fadeout','fadeZoom','growX','growY','scrollUp','scrollDown','scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','slideX','slideY','toss','turnUp','turnDown','turnLeft','turnRight','uncover','wipe','zoom','all');
		}
		$pageTS = t3lib_BEfunc::getPagesTSconfig($config['row']['pid']);
		$imagecycleEffects = t3lib_div::trimExplode(",", $pageTS['mod.']['imagecycle.']['effects'], true);
		$optionList = array();
		if (count($imagecycleEffects) > 0) {
			foreach ($availableEffects as $key => $availableEffect) {
				if (in_array(trim($availableEffect), $imagecycleEffects)) {
					$optionList[] = array(
						trim($availableEffect),
						trim($availableEffect),
					);
				}
			}
		} else {
			foreach ($availableEffects as $key => $availableEffect) {
				$optionList[] = array(
					trim($availableEffect),
					trim($availableEffect),
				);
			}
		}
		$config['items'] = array_merge($config['items'], $optionList);
		return $config;
	}

	/**
	 * Get defined Effects for dropdown
	 * @return array
	 */
	function getEffectsCoin($config, $item)
	{
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imagecycle']);
		$availableEffects = t3lib_div::trimExplode(",", $confArr['effectsCoin'], true);
		if (count($availableEffects) < 1) {
			$availableEffects = array('random','swirl','rain','straight');
		}
		$pageTS = t3lib_BEfunc::getPagesTSconfig($config['row']['pid']);
		$imagecycleEffects = t3lib_div::trimExplode(",", $pageTS['mod.']['imagecycle.']['effectsCoin'], true);
		$optionList = array();
		if (count($imagecycleEffects) > 0) {
			foreach ($availableEffects as $key => $availableEffect) {
				if (in_array(trim($availableEffect), $imagecycleEffects)) {
					$optionList[] = array(
						trim($availableEffect),
						trim($availableEffect),
					);
				}
			}
		} else {
			foreach ($availableEffects as $key => $availableEffect) {
				$optionList[] = array(
					trim($availableEffect),
					trim($availableEffect),
				);
			}
		}
		$config['items'] = array_merge($config['items'], $optionList);
		return $config;
	}

	/**
	 * Get defined Effects for dropdown
	 * @return array
	 */
	function getEffectsNivo($config, $item)
	{
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['imagecycle']);
		$availableEffects = t3lib_div::trimExplode(",", $confArr['effectsNivo'], true);
		if (count($availableEffects) < 1) {
			$availableEffects = array('random','sliceDown','sliceDownLeft','sliceUp','sliceUpLeft','sliceUpDown','sliceUpDownLeft','fold','fade','slideInRight','slideInLeft', 'boxRandom', 'boxRain', 'boxRainReverse', 'boxRainGrow', 'boxRainGrowReverse');
		}
		$pageTS = t3lib_BEfunc::getPagesTSconfig($config['row']['pid']);
		$imagecycleEffects = t3lib_div::trimExplode(",", $pageTS['mod.']['imagecycle.']['effectsNivo'], true);
		$optionList = array();
		if (count($imagecycleEffects) > 0) {
			foreach ($availableEffects as $key => $availableEffect) {
				if (in_array(trim($availableEffect), $imagecycleEffects)) {
					$optionList[] = array(
						trim($availableEffect),
						trim($availableEffect),
					);
				}
			}
		} else {
			foreach ($availableEffects as $key => $availableEffect) {
				$optionList[] = array(
					trim($availableEffect),
					trim($availableEffect),
				);
			}
		}
		$config['items'] = array_merge($config['items'], $optionList);
		return $config;
	}

	/**
	 * Get all modes for image selection
	 * @return array
	 */
	function getModes($config, $item)
	{
		$optionList = array();
		$optionList[] = array(
			$GLOBALS['LANG']->sL('LLL:EXT:imagecycle/locallang_db.xml:tt_content.pi_flexform.mode.I.upload'),
			"upload",
			"EXT:imagecycle/mode_upload.gif"
		);
		$optionList[] = array(
			$GLOBALS['LANG']->sL('LLL:EXT:imagecycle/locallang_db.xml:tt_content.pi_flexform.mode.I.rte'),
			"uploadRTE",
			"EXT:imagecycle/mode_rte.gif"
		);
		if (t3lib_extMgm::isLoaded("dam")) {
			$optionList[] = array(
				$GLOBALS['LANG']->sL('LLL:EXT:imagecycle/locallang_db.xml:tt_content.pi_flexform.mode.I.dam'),
				"dam",
				"EXT:imagecycle/mode_dam.gif"
			);
			if (t3lib_extMgm::isLoaded("dam_catedit")) {
				$optionList[] = array(
					$GLOBALS['LANG']->sL('LLL:EXT:imagecycle/locallang_db.xml:tt_content.pi_flexform.mode.I.dam_catedit'),
					"dam_catedit",
					"EXT:imagecycle/mode_damcat.gif"
				);
			}
		}
		$config['items'] = array_merge($config['items'], $optionList);
		return $config;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/lib/class.tx_imagecycle_itemsProcFunc.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/lib/class.tx_imagecycle_itemsProcFunc.php']);
}
?>