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

class GetinteriordataViewHelper extends AbstractViewHelper {
	
    /**
     * addRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\CarRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $addRepository = NULL;

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
		if($climatisationObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Climatisation) {
			$climatisation = $climatisationObj->current()->getTitle();
		}

		$featuresObj = $add->getFeature();

		foreach($featuresObj as $featureObj) {
			
			$keyId = $featureObj->getKeyId();
			
			if($keyId == 'LEATHER_SEATS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'SUNROOF') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'CENTRAL_LOCKING') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ELECTRIC_WINDOWS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'NAVIGATION_SYSTEM') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'SPORT_SEATS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'CD_MULTICHANGER') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'BLUETOOTH') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ON_BOARD_COMPUTER') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'CD_PLAYER') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ELECTRIC_ADJUSTABLE_SEATS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'HEAD_UP_DISPLAY') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'HANDS_FREE_PHONE_SYSTEM') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'MP3_INTERFACE') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'TUNER') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'PARKING_SENSORS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'POWER_ASSISTED_STEERING') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'ELECTRIC_HEATED_SEATS') {
				$feature[] = '<li>'.$featureObj->getTitle().'</li>';
			}
			if($keyId == 'CRUISE_CONTROL') {
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


