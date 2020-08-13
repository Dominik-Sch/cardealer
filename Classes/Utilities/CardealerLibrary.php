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

namespace EXOTEC\Cardealer\Utilities;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \EXOTEC\Cardealer\Utilities\Paginator;

class CardealerLibrary  implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * carRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\CarRepository
     * */
    protected $carRepository = null;

    /**
     * fuelRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\FuelRepository
     * */
    protected $fuelRepository = null;

    /**
     * gearboxRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\GearboxRepository
     * */
    protected $gearboxRepository = null;

    /**
     * climatisationRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\ClimatisationRepository
     * */
    protected $climatisationRepository = null;

    /**
     * conditionRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\CarconditionRepository
     * */
    protected $carconditionRepository = null;

    /**
     * usagetypeRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\UsagetypeRepository
     * */
    protected $usagetypeRepository = null;

    /**
     * featureRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\FeatureRepository
     * */
    protected $featureRepository = null;

    /**
     * airbagRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\AirbagRepository
     * */
    protected $airbagRepository = null;

    /**
     * interiortypeRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\InteriortypeRepository
     * */
    protected $interiortypeRepository = null;

    /**
     * interiorcolorRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\InteriorcolorRepository
     * */
    protected $interiorcolorRepository = null;

    /**
     * exteriorcolorRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\ExteriorcolorRepository
     * */
    protected $exteriorcolorRepository = null;

    /**
     * countryversionRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\CountryversionRepository
     * */
    protected $countryversionRepository = null;

    /**
     * parkingassistantRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\ParkingassistantRepository
     * */
    protected $parkingassistantRepository = null;

    /**
     * emissionstickerRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\EmissionstickerRepository
     * */
    protected $emissionstickerRepository = null;

    /**
     * emissionclassRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\EmissionclassRepository
     * */
    protected $emissionclassRepository = null;

    /**
     * doorcountRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\DoorcountRepository
     * */
    protected $doorcountRepository = null;

    /**
     * makeRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\MakeRepository
     * */
    protected $makeRepository = null;

    /**
     * modelRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\ModelRepository
     * */
    protected $modelRepository = null;

    /**
     * categoryRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\CategoryRepository
     * */
    protected $categoryRepository = null;



    public function injectCarRepository (
        \EXOTEC\Cardealer\Domain\Repository\CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function injectAirbagRepository (
        \EXOTEC\Cardealer\Domain\Repository\AirbagRepository $airbagRepository)
    {
        $this->airbagRepository = $airbagRepository;
    }

    public function injectCarconditionRepository (
        \EXOTEC\Cardealer\Domain\Repository\CarconditionRepository $carconditionRepository
    ) {
        $this->carconditionRepository = $carconditionRepository;
    }

    public function injectCategoryRepository (
        \EXOTEC\Cardealer\Domain\Repository\CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function injectClimatisationRepository (
        \EXOTEC\Cardealer\Domain\Repository\ClimatisationRepository $climatisationRepository
    ) {
        $this->climatisationRepository = $climatisationRepository;
    }

    public function injectCountryversionRepository (
        \EXOTEC\Cardealer\Domain\Repository\CountryversionRepository $countryversionRepository
    ) {
        $this->countryversionRepository = $countryversionRepository;
    }

    public function injectDoorcountRepository (
        \EXOTEC\Cardealer\Domain\Repository\DoorcountRepository $doorcountRepository
    ) {
        $this->doorcountRepository = $doorcountRepository;
    }

    public function injectEmissionclassRepository (
        \EXOTEC\Cardealer\Domain\Repository\EmissionclassRepository $emissionclassRepository
    ) {
        $this->emissionclassRepository = $emissionclassRepository;
    }

    public function injectEmissionstickerRepository (
        \EXOTEC\Cardealer\Domain\Repository\EmissionstickerRepository $emissionstickerRepository
    ) {
        $this->emissionstickerRepository = $emissionstickerRepository;
    }

    public function injectExteriorcolorRepository (
        \EXOTEC\Cardealer\Domain\Repository\ExteriorcolorRepository $exteriorcolorRepository
    ) {
        $this->exteriorcolorRepository = $exteriorcolorRepository;
    }

    public function injectFeatureRepository (
        \EXOTEC\Cardealer\Domain\Repository\FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function injectFuelRepository (
        \EXOTEC\Cardealer\Domain\Repository\FuelRepository $fuelRepository)
    {
        $this->fuelRepository = $fuelRepository;
    }

    public function injectGearboxRepository (
        \EXOTEC\Cardealer\Domain\Repository\GearboxRepository $gearboxRepository)
    {
        $this->gearboxRepository = $gearboxRepository;
    }

    public function injectInteriorcolorRepository (
        \EXOTEC\Cardealer\Domain\Repository\InteriorcolorRepository $interiorcolorRepository
    ) {
        $this->interiorcolorRepository = $interiorcolorRepository;
    }

    public function injectInteriortypeRepository (
        \EXOTEC\Cardealer\Domain\Repository\InteriortypeRepository $interiortypeRepository
    ) {
        $this->interiortypeRepository = $interiortypeRepository;
    }

    public function injectMakeRepository (
        \EXOTEC\Cardealer\Domain\Repository\MakeRepository $makeRepository)
    {
        $this->makeRepository = $makeRepository;
    }

    public function injectModelRepository (
        \EXOTEC\Cardealer\Domain\Repository\ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    public function injectParkingassistantRepository (
        \EXOTEC\Cardealer\Domain\Repository\ParkingassistantRepository $parkingassistantRepository
    ) {
        $this->parkingassistantRepository = $parkingassistantRepository;
    }

    public function injectUsagetypeRepository (
        \EXOTEC\Cardealer\Domain\Repository\UsagetypeRepository $usagetypeRepository
    ) {
        $this->usagetypeRepository = $usagetypeRepository;
    }

    /**
     * carclassRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\CarclassRepository
     * */
    protected $carclassRepository = null;

    public function injectCarclassRepository (\EXOTEC\Cardealer\Domain\Repository\CarclassRepository $carclassRepository) {
        $this->carclassRepository = $carclassRepository;
    }


    /**
     * @param string $property
     * @param array $filter
     * @return array
     */
    public function findOptionsFor($property, $filter) {
        switch ($property) {
            case 'carclass':
                $data = $this->carclassRepository->findAll();
                break;
            case 'carcondition':
                $data = $this->carconditionRepository->findAll();
                break;
            case 'category':
                $data = $this->categoryRepository->findAll();
                break;
            case 'usagetype':
                $data = $this->usagetypeRepository->findAll();
                break;
            case 'make':
                $data = $this->makeRepository->findAll();
                break;
            case 'fuel':
                $data = $this->fuelRepository->findAll();
                break;
            case 'gearbox':
                $data = $this->gearboxRepository->findAll();
                break;
            case 'climatisation':
                $data = $this->climatisationRepository->findAll();
                break;
            case 'feature':
                $data = $this->featureRepository->findAll();
                break;
            case 'airbag':
                $data = $this->airbagRepository->findAll();
                break;
            case 'interiorType':
                $data = $this->interiortypeRepository->findAll();
                break;
            case 'interiorColor':
                $data = $this->interiorcolorRepository->findAll();
                break;
            case 'exteriorColor':
                $data = $this->exteriorcolorRepository->findAll();
                break;
            case 'countryVersion':
                $data = $this->countryversionRepository->findAll();
                break;
            case 'parkingAssistant':
                $data = $this->parkingassistantRepository->findAll();
                break;
            case 'emissionSticker':
                $data = $this->emissionstickerRepository->findAll();
                break;
            case 'emissionClass':
                $data = $this->emissionclassRepository->findAll();
                break;
            case 'doorCount':
                $data = $this->doorcountRepository->findAll();
                break;

        }

        $out = $this->buildOptions($data, $property, $filter);

        return $out;
    }



    /**
     * Prepare entries
     *
     * @param $entries
     * @param string $property
     * @param $args
     * @return array
     */
    public function buildOptions ($entries, $property = '', $args)
    {

        if($entries) {
            foreach ($entries as $i => $entry) {
                if( $property == 'location' ) {
                    $counter = $this->carRepository->countCarsByFilter($args, $property, $entry->getKeyId());
                } else {
                    $counter = $this->carRepository->countCarsByFilter($args, $property, $entry->getUid());
                }

                $options[$i]['counter'] = $counter;
                $options[$i]['uid'] = $entry->getUid();
                if( $property == 'location' ) {
                    $options[$i]['uid'] = $entry->getKeyId();
                }
                $options[$i]['title'] = $entry->getTitle();

                if($args[$property]) {
                    foreach ($args[$property] as $arg) {
                            if ($property == 'location') {
                                if ($arg == $entry->getKeyId()) {
                                    $options[$i]['selected'] = 'selected';
                                }
                            } else {
                                if ($arg == $entry->getUid()) {
                                    $options[$i]['selected'] = 'selected';
                                }
                            }
                    }
                }

                if ($counter == 0) {
                    $options[$i]['disabled'] = 'disabled';
                    $options[$i]['selected'] = '';
                }

                $counterArr[$i] += $counter;
            }
        }

        // counter first options (-- Alle ... ($countAll) --)
        switch ($property) {
            // get only the highest value of loop
            case 'feature':
            case 'firstRegistration':
            case 'mileage':
            case 'price':
                if(!is_null($counterArr)) {
                    $countAll = max($counterArr);
                    break;
                }
            // get sum of all loop values
            default:
                if($counterArr) {
                    $countAll = array_sum($counterArr);
                }
                if($countAll < 1) {
                    $countAll = '?';
                }
        }

        $options['countAll'] = $countAll;
        return $options;
    }



    /**
     * @param string $property
     * @param array $values
     * @return array
     */
    public function setValuesFor ($property, $values): array
    {

        foreach ($values as $value) {
            switch ($property) {
                case 'price':
                    $tmpObj = new \EXOTEC\Cardealer\Domain\Model\Price();
                    break;

                case 'location':
                    $tmpObj = new \EXOTEC\Cardealer\Domain\Model\Location();
                    break;

                case 'mileage':
                    $tmpObj = new \EXOTEC\Cardealer\Domain\Model\Mileage();
                    break;

                case 'firstRegistration':
                    $tmpObj = new \EXOTEC\Cardealer\Domain\Model\FirstRegistration();
                    break;

            }

            $tmpObj->setTitle($value);
            $tmpObj->setKeyId($value);
            $tmpObj->setUid($value);
            $results[] = $tmpObj;

        }

        return $results;
    }



    /**
     * @param $cars
     * @param $args
     * @return Paginator
     */
    public function getPaginator ($cars, $args): Paginator
    {

        $totalItems = $cars['count'];
        $itemsPerPage = $args['limit'][0];
        $currentPage = $args['page'];
        $urlPattern = '/' . ($args['page'] + 1);
        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        $paginator->getPages();
        $paginator->setMaxPagesToShow(7);
        $paginator->getNextPage();
        $paginator->getPrevPage();
        $paginator->getNumPages();
        $paginator->getPrevUrl();
        $paginator->getNextUrl();
        return $paginator;
    }



    /**
     * build fluid variables array for filter view
     *
     * @param array $args
     * @param array $settings
     * @return mixed
     */
    public function buildFilterFluidVars ($args, $settings)
    {
        // search form fields
        $fields = $settings['filterFields'];

        foreach ($fields as $property => $partialName) {
            switch ($property) {
                case 'model':                // Models
                    if ($args['filter']['make'][0] > 0) {
                        $allModels = $this->modelRepository->findByMakes($args['filter']['make']);
                    } else {
                        $allModels = '';
                    }
                    $result[$property] = $this->buildOptions($allModels, 'model', $args['filter']);
                    $result[$property]['partialName'] = $partialName;
                break;
                case 'firstRegistration':
//                    todo: getUniquevaluesFor would be nice, but is to slow
//                    $values = $this->carRepository->getUniquevaluesFor('first_registration');
                    $values = explode(',', $settings['firstRegistrations']);
                    $allFirstRegistrations = $this->setValuesFor('firstRegistration', $values);
                    $result[$property] = $this->buildOptions($allFirstRegistrations, 'firstRegistration', $args['filter']);
                    $result[$property]['partialName'] = $partialName;
                break;
                case 'mileage':
                    $values = explode(',', $settings['mileages']);
                    $allFirstRegistrations = $this->setValuesFor('mileage', $values);
                    $result[$property] = $this->buildOptions($allFirstRegistrations, 'mileage', $args['filter']);
                    $result[$property]['partialName'] = $partialName;
                    break;
                case 'location':
                    $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cardealer');
                    $values = explode(',', $extConf['config']['locations']);
                    $allValues = $this->setValuesFor($property, $values);
                    $result[$property] = $this->buildOptions($allValues, $property, $args['filter']);
                    $result[$property]['partialName'] = $partialName;
                break;
                case 'price':
                    $values = explode(',', $settings[$property . 's']);
                    $allValues = $this->setValuesFor($property, $values);
                    $result[$property] = $this->buildOptions($allValues, $property, $args['filter']);
                    $result[$property]['partialName'] = $partialName;
                break;
                default:
                    $result[$property] = $this->findOptionsFor($property, $args['filter']);
                    $result[$property]['partialName'] = $partialName;
            }

        }

        return $result;
    }


    /**
     * @param $limit
     * @param $page
     * @param $count
     * @return int
     */
    public function pageValue ($limit, $page, $count): int
    {
        $highestPosiblePaginatorPage = ceil($count / $limit);
        if ($highestPosiblePaginatorPage <= $page) {
            $page = (int)$highestPosiblePaginatorPage;
        }
        return $page;
    }



}