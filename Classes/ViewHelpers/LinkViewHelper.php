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

use EXOTEC\Cardealer\Domain\Model\Car;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class LinkViewHelper extends AbstractViewHelper
{

    /** @var $cObj ContentObjectRenderer */
    protected $cObj;

    public function initializeArguments()
    {
        $this->registerArgument('car', Car::class, 'cars item', true);
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
        /** @var Car $car */
        $car = $arguments['car'];

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $configurationManager = $objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class);

        $tsSettings = $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'Cardealer',
            'Pi1'
        );

//        DebuggerUtility::var_dump($tsSettings);

        $this->init();

        $configuration['useCacheHash'] = 0;
        $configuration['parameter'] = 90;
        $configuration['additionalParams'] .= '&tx_cardealer_pi1[car]=' . $car->getUid();
        $configuration['additionalParams'] .= '&tx_cardealer_pi1[controller]=Cardealer&tx_cardealer_pi1[action]=show';
//        DebuggerUtility::var_dump($configuration);

        $url = $this->cObj->typoLink_URL($configuration);
        $this->tag->setTagName('a');
        $this->tag->addAttribute('href', $url);
        $this->tag->addAttribute('class', 'ajaxLink history show');
        $this->tag->setContent('test from viewhelper');
        $tag = $this->tag->render();
//        DebuggerUtility::var_dump($tag);
        return $tag;

    }

    /**
     * Initialize properties
     *
     */
    protected function init()
    {
        $this->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
    }

}