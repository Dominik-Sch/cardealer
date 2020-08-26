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

class GetextradataViewHelper extends AbstractViewHelper {

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
		$featuresObj = $add->getFeature();
		
		foreach($featuresObj as $featureObj) {
			
			$keyId = $featureObj->getKeyId();
			
			if($keyId == 'NONSMOKER_VEHICLE') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'TAXI') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'DISABLED_ACCESSIBLE') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'FULL_SERVICE_HISTORY') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'AUXILIARY_HEATING') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'COMPRESSOR') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'HU_AU_NEU') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'WARRANTY') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'DISABLED_CONVERSION') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'SECONDARY_AIR_CONDITIONING') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'PERFORMANCE_HANDLING_SYSTEM') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'SPORT_PACKAGE') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}	
		}
		if( is_array($feature) ) {
            asort($feature);
            return implode('', $feature);
		}		
        return '';
	}
}


