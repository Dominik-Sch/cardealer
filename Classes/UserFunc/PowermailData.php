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

namespace EXOTEC\Cardealer\UserFunc;

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PowermailData - Car Data Array
 */
class PowermailData {

    /**
     * @param string $content
     * @param array $configuration
     * @return string
     */
    public function main(string $content, array $configuration = [])
    {

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        /** @var \EXOTEC\Cardealer\Domain\Repository\CarRepository $carRepository */
        $carRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\CarRepository');

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setStoragePageIds(GeneralUtility::trimExplode(',', $storagePid));
        $carRepository->setDefaultQuerySettings($querySettings);

        $_GP = GeneralUtility::_GP('tx_cardealer_pi1');

        /** @var \EXOTEC\Cardealer\Domain\Model\Car $car */
        $car = $carRepository->findByUidAsArray($_GP['car']);

        $result = $car[0][GeneralUtility::camelCaseToLowerCaseUnderscored($configuration['param'])];

        switch ($configuration['param']) {
            case 'detailsPageLink':

                /** @var UriBuilder $uriBuilder */
                $uriBuilder = $objectManager->get(UriBuilder::class);
                // array of plugin arguments
                // will become something like: &tx_extension_plugin['foo']=bar
                $pluginArgs = [
                    'car' => $car[0]['uid'],
                    'make' => $car[0]['make'],
                    'identifier' => $car[0]['add_key']
                ];

                /** @var UriBuilder $uriBuilder */
                $uri = $uriBuilder
                    ->reset()
                    ->setTargetPageUid($GLOBALS['TSFE']->id)
                    ->setCreateAbsoluteUri(true)
                    ->setUseCacheHash(false)
                    ->uriFor('show', $pluginArgs, 'Standard', 'cardealer', 'Pi1');
                
                return $uri;
                break;
                
            case 'make':
                /** @var \EXOTEC\Cardealer\Domain\Repository\MakeRepository $makeRepository */
                $makeRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\MakeRepository');
                $makeRepository->setDefaultQuerySettings($querySettings);
                /** @var \EXOTEC\Cardealer\Domain\Model\Make $make */
                $make = $makeRepository->findByUid($result);
                return $make->getTitle();
                break;

            case 'model':
                /** @var \EXOTEC\Cardealer\Domain\Repository\ModelRepository $modelRepository */
                $modelRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\ModelRepository');
                $modelRepository->setDefaultQuerySettings($querySettings);
                /** @var \EXOTEC\Cardealer\Domain\Model\Make $make */
                $model = $modelRepository->findByUid($result);
                return $model->getTitle();
                break;

            case 'category':
                /** @var \EXOTEC\Cardealer\Domain\Repository\CategoryRepository $categoryRepository */
                $categoryRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\CategoryRepository');
                $categoryRepository->setDefaultQuerySettings($querySettings);
                /** @var \EXOTEC\Cardealer\Domain\Model\Category $category */
                $category = $categoryRepository->findByUid($result);
                if($category) {
                    return ' '.$category->getTitle();
                }
                break;

            case 'carclass':
                /** @var \EXOTEC\Cardealer\Domain\Repository\CarclassRepository $carclassRepository */
                $carclassRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\CarclassRepository');
                $carclassRepository->setDefaultQuerySettings($querySettings);
                /** @var \EXOTEC\Cardealer\Domain\Model\Carclass $carclass */
                $carclass = $carclassRepository->findByUid($result);
                return ' '.$carclass->getTitle();
                break;

            case 'carcondition':
                /** @var \EXOTEC\Cardealer\Domain\Repository\CarconditionRepository $carconditionRepository */
                $carconditionRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\CarconditionRepository');
                $carconditionRepository->setDefaultQuerySettings($querySettings);
                /** @var \EXOTEC\Cardealer\Domain\Model\Carclass $carclass */
                $carcondition = $carconditionRepository->findByUid($result);
                return ' '.$carcondition->getTitle();
                break;

            case 'firstRegistration':
                return ' '.date('m/Y', $result);
                break;

            case 'price':
                setlocale(LC_MONETARY, 'de_DE');
                return ' '.money_format('%.0i', $result);
                break;




            default:
                $out = ' '.$result;
        }

        return $out;
    }




}