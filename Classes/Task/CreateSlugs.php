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

namespace EXOTEC\Cardealer\Task;


use EXOTEC\Cardealer\Domain\Model\Car;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Configuration\SiteConfiguration;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use EXOTEC\Cardealer\Utilities\TaskLibrary;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class CreateSlugs extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

    protected $table = 'tx_cardealer_domain_model_car';

    protected $fieldName = 'slug';

    /**
     * @return bool
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function execute() {

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        /** @var \EXOTEC\Cardealer\Domain\Repository\CarRepository $carRepository */
        $carRepository = $objectManager->get(\EXOTEC\Cardealer\Domain\Repository\CarRepository::class);

        /** @var Typo3QuerySettings $querySettings */
        $querySettings = $objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $carRepository->setDefaultQuerySettings($querySettings);

        $slugHelper = $objectManager->get(SlugHelper::class, 'tx_cardealer_domain_model_make', 'slug', array());

        $cars = $carRepository->findAll();
        /** @var Car $car */
        foreach ($cars as $k => $car) {
            $descriptionsArray[] = $car->getModelDescription();

            // hier werden noch KEINE unique slugs erzeugt
            list($modelDescription, $slug) = $this->rmoveIllegalChars($car, $slugHelper);
            $car->setSlug($slug);
            $carRepository->update($car);
            $objectManager->get(PersistenceManager::class)->persistAll();
        }

        // build duplicates array
        $checkArr = [];
        foreach ($descriptionsArray as $description) {
            if( in_array($description, $checkArr)  ) {
                $doublettesArr[] = $description;
            }
            $checkArr[] = $description;
        }

        // update slugs
        foreach ($doublettesArr as $description) {
            $doublettes = $carRepository->findByModelDescription($description);
            $x = 0;
            foreach ($doublettes as $doublette) {
                $i = $x++;
                if($i > 0) {

                    $car = $carRepository->findByUid($doublette['uid']);
                    // hier werden unique slugs erzeugt
                    list($modelDescription, $slug) = $this->rmoveIllegalChars($car, $slugHelper);
                    $car->setSlug($slug.'-'.$i);

                    $carRepository->update($car);
                    $objectManager->get(PersistenceManager::class)->persistAll();
                }
            }
        }

        return true;

    }

    /**
     * @param $car
     * @param $slugHelper
     * @return array
     */
    protected function rmoveIllegalChars ($car, $slugHelper)
    {
        $modelDescription = str_replace('/', '-', $car->getModelDescription());
        $modelDescription = str_replace('&apos;', '', $modelDescription);
        $modelDescription = str_replace('&amp;', '+', $modelDescription);
        $slug = $slugHelper->sanitize($modelDescription);
        return array($modelDescription, $slug);
    }

}
