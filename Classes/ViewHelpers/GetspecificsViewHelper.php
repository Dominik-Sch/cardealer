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

class GetspecificsViewHelper extends AbstractViewHelper {

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

        /** @var \EXOTEC\Cardealer\Domain\Model\Car $add */
        $add = $addRepository->findByUid($arguments['addUid']);


        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $interiorTypeObj */
        $interiorTypeObj = $add->getInteriorType();
		if($interiorTypeObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Interiortype) {
			$interiorType = $interiorTypeObj->current()->getTitle();
		}

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $interiorColorObj */
		$interiorColorObj = $add->getInteriorColor();
		if($interiorColorObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Interiorcolor) {
			$interiorColor = $interiorColorObj->current()->getTitle();
		}
		
		$exteriorColorObj = $add->getExteriorColor();
		if($exteriorColorObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Exteriorcolor) {
			$exteriorColor = $exteriorColorObj->current()->getTitle();
		}
		
		$generalInspection = $add->getGeneralInspection();
		
		$doorCountObj = $add->getDoorCount();
		if($doorCountObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Doorcount) {
			$doorCount = $doorCountObj->current()->getTitle();
		}
		
		$parkingAssistantObj = $add->getParkingAssistant();
		if($parkingAssistantObj->current() instanceof \EXOTEC\Cardealer\Domain\Model\Parkingassistant) {
			$parkingAssistant = $parkingAssistantObj->current()->getTitle();
		}
		
		$numSeats = $add->getNumSeats();
		$cubicCapacity = $add->getCubicCapacity();
		$numberOwners = $add->getNumberOwners();


		if($interiorType) {		
			$specs .= '<dt class="ellipsis">Innenausstattung:</dt>';
			$specs .= '<dd>'.$interiorType.'</dd>';
		}

		if($interiorColor) {		
			$specs .= '<dt class="ellipsis">Farbe innen:</dt>';
			$specs .= '<dd>'.$interiorColor.'</dd>';
		}

		if($exteriorColor) {		
			$specs .= '<dt class="ellipsis">Farbe außen:</dt>';
			$specs .= '<dd>'.$exteriorColor.'</dd>';
		}
		
		if($generalInspection) {
			$specs .= '<dt class="ellipsis">HU:</dt>';
			$specs .= '<dd>'.date('m-Y', $generalInspection).'</dd>';
		}

		if($doorCount) {		
			$specs .= '<dt class="ellipsis">Anzahl der Türen:</dt>';
			$specs .= '<dd>'.$doorCount.'</dd>';
		}
		
		if($numSeats) {
			$specs .= '<dt class="ellipsis">Sitzplätze:</dt>';
			$specs .= '<dd>'.$numSeats.'</dd>';			
		}

		
		if($cubicCapacity) {
			$specs .= '<dt class="ellipsis">Hubraum:</dt>';
			$specs .= '<dd>'.$cubicCapacity.' cm<sup>3</sup></dd>';			
		}

		
		if($numberOwners) {
				$specs .= '<dt class="ellipsis">Vorbesitzer:</dt>';
				$specs .= '<dd>'.$numberOwners.'</dd>';	
		}
		
		if($parkingAssistant) {
			$specs .= '<dt class="ellipsis">Parkassistent:</dt>';
			$specs .= '<dd>'.$parkingAssistant.'</dd>';			
		}


		
		
		
		return $specs;
		
	}
	
}


