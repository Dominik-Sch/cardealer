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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GetconsumptionsViewHelper extends AbstractViewHelper {

    public function initializeArguments()
    {
        $this->registerArgument('addUid', 'integer', 'Description', false);
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

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $addRepository = $objectManager->get(\EXOTEC\Cardealer\Domain\Repository\CarRepository::class);

		$add = $addRepository->findByUid($arguments['addUid']);

		$emissionClassObj = $add->getEmissionClass();
		if($emissionClassObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Emissionclass) {
			$emissionClass = $emissionClassObj->current()->getTitle();
		}
		
		$emissionStickerObj = $add->getEmissionClass();
		if($emissionStickerObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Emissionsticker) {
			$emissionSticker = $emissionStickerObj->current()->getTitle();
		}


		$co2Emission = $add->getCo2Emission();
		$innerConsumption = $add->getInnerConsumption();
		$outerConsumption = $add->getOuterConsumption();
		$combinedConsumption = $add->getCombinedConsumption();
		
		
		

		if($emissionClass) {		
			$consumptions .= '<dt class="ellipsis">Schadstoffklasse:</dt>';
			$consumptions .= '<dd>'.$emissionClass.'</dd>';
		}

		if($co2Emission) {		
			$consumptions .= '<dt class="ellipsis">CO<sup>2</sup>-Emissionen komb.:</dt>';
			$consumptions .= '<dd>'.$co2Emission.' g/km</dd>';
		}
		
		if($innerConsumption) {		
			$consumptions .= '<dt class="ellipsis">Kraftstoffverbr. innerorts:</dt>';
			$consumptions .= '<dd>'.number_format((float)$innerConsumption, 1, '.', '').' L/100 km</dd>';
		}
		
		if($outerConsumption) {		
			$consumptions .= '<dt class="ellipsis">Kraftstoffverbr. außerorts:</dt>';
			$consumptions .= '<dd>'.number_format((float)$outerConsumption, 1, '.', '').' L/100 km</dd>';
		}
		
		if($combinedConsumption) {		
			$consumptions .= '<dt class="ellipsis">Kraftstoffverbr. kombiniert:</dt>';
			$consumptions .= '<dd>'.number_format((float)$combinedConsumption, 1, '.', '').' L/100 km</dd>';
		}
		
		if($emissionSticker) {		
			$consumptions .= '<dt class="ellipsis">Umweltplakette:</dt>';
			$consumptions .= '<dd>'.$emissionSticker.' l/100 km</dd>';
		}



		
		
		
		return $consumptions;
		
	}
	
}


