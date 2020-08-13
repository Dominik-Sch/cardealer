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

class GetsecurityenvironmentViewHelper extends AbstractViewHelper {

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
			
			if($keyId == 'ABS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ESP') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'FOUR_WHEEL_DRIVE') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'IMMOBILIZER') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'BENDING_LIGHTS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'LIGHT_SENSOR') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'FRONT_FOG_LIGHTS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'PARTICULATE_FILTER_DIESEL') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'AUTOMATIC_RAIN_SENSOR') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'START_STOP_SYSTEM') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'DAYTIME_RUNNING_LIGHTS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'XENON_HEADLIGHTS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'CATALYTIC_CONVERTER') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'BIODIESEL_CONVERSION') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'BIODIESEL_SUITABLE') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'VEGETABLEOILFUEL_CONVERSION') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'VEGETABLEOILFUEL_SUITABLE') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'E10') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'E10_ENABLED') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'EBS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ISOFIX') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'TRACTION_CONTROL_SYSTEM') {
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

        $emptyArray = [];

        return $emptyArray;
		
	}
	
}






















