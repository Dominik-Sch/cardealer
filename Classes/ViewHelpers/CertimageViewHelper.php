<?php
/**
 * Copyright (c) 2018.  Alexander Weber <weber@exotec.de> - exotec - TYPO3 Services
 *
 * All rights reserved
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 */

namespace EXOTEC\Cardealer\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CertimageViewHelper extends AbstractViewHelper {
  
  public function initializeArguments()
  {
      $this->registerArgument('addCondition', 'string', 'Description', false);
      $this->registerArgument('addMileage', 'integer', 'Description', false);
      $this->registerArgument('addFirstRegistration', 'integer', 'Description', false);
  }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed|string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {

		$EZdate = strtotime($arguments['addFirstRegistration']);
		$years = 252462808;
		$now = time();
		$nowMinuYears = time()-$years;

		if($arguments['addCondition'] =="Gebrauchtfahrzeug" && $arguments['addMileage'] < 160000 && $EZdate > $nowMinuYears) {
			$certImg = '
				<a href="https://static.classistatic.de/vcs/pages/seal_info/opel/3285865623index.html" target="_blank">
					<img src="http://static.classistatic.de/res/images/shared/icons/usedCarSeals/1393875143opel_full.gif" class="certImg" alt="" />
				</a>
			';
		} else {
			$certImg = '';
		}

		return $certImg;
	}
	
}


