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

class GetexteriordataViewHelper extends AbstractViewHelper {

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

		$climatisationObj = $add->getClimatisation();
		if($climatisationObj->current() instanceof  \EXOTEC\Cardealer\Domain\Model\Climatisation) {
			$climatisation = $climatisationObj->current()->getTitle();
		}

		$featuresObj = $add->getFeature();
		
/*
		echo '<pre>';
		print_r($featuresObj);
		echo '<pre/>';
*/
		
	
		foreach($featuresObj as $featureObj) {
			
			$keyId = $featureObj->getKeyId();
			
			if($keyId == 'TRAILER_COUPLING') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ALLOY_WHEELS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ROOF_BARS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'PANORAMIC_GLASS_ROOF') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'SKI_BAG') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ROOF_RAILS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ELECTRIC_EXTERIOR_MIRRORS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
		}

		if($climatisation) {		
			$feature[] = '<li>'.$climatisation.'</li>';
		}

        if (is_array($feature)) {
            asort($feature);
            return implode('', $feature);
        }

        return '';

    }
	
}


