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

/**
 * @author	Juergen Furrer <juergen.furrer@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_imagecycle
 */
class tx_imagecycle {
	var $cObj;

	function getSlideshow($content, $conf)
	{
		if ($this->cObj->data['tx_imagecycle_activate']) {
			require_once(t3lib_extMgm::extPath('imagecycle') . 'pi1/class.tx_imagecycle_pi1.php');
			$obj = t3lib_div::makeInstance('tx_imagecycle_pi1');
			$obj->contentKey = $obj->extKey . '_' . $this->cObj->data['uid'];
			$obj->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_imagecycle_pi1.'];
			// overwrite the width and height of the config
			$obj->conf['imagewidth'] = $GLOBALS['TSFE']->register['imagewidth'];
			$obj->conf['imageheight'] = $GLOBALS['TSFE']->register['imageheight'];
			if ($this->cObj->data['tx_imagecycle_duration'] > 0) {
				$obj->conf['displayDuration'] = $this->cObj->data['tx_imagecycle_duration'];
			}
			$obj->cObj = $this->cObj;
			$obj->type = 'content';
			$return_string = $obj->parseTemplate($data, 'uploads/pics/', true);
		}
		return $content;
	}
}

// XCLASS inclusion code
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/class.tx_imagecycle.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/imagecycle/class.tx_imagecycle.php']);
}
?>