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
//use TYPO3\CMS\Core\Configuration\SiteConfiguration;
//use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use EXOTEC\Cardealer\Utilities\TaskLibrary;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class WriteCars extends \TYPO3\CMS\Scheduler\Task\AbstractTask  implements \TYPO3\CMS\Scheduler\ProgressProviderInterface {

    /**
     * @return float|int
     */
    public function getProgress ()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
        $finishedItems = $queryBuilder
            ->count('uid')
            ->from('tx_cardealer_domain_model_car')
            ->where(
                $queryBuilder->expr()->eq('pid', $this->storagePid)
            )
            ->execute()
            ->fetchColumn(0);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
        $statement = $queryBuilder
            ->select('totalitems')
            ->from('tx_cardealer_task_queue')
            ->where(
                $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
            )->execute();
        $row = $statement->fetch();

        if(!is_integer($row['totalitems'])) {
            $percent = 0;
        } else {
            $percent = $finishedItems / $row['totalitems'] * 100;
        }

        return $percent;
    }

    /**
     * @return bool|mixed
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     * @throws \TYPO3\CMS\Core\Exception
     */
    public function execute() {

        // clean progressbar queue
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
        $queryBuilder->delete('tx_cardealer_task_queue')->where(
            $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
        )->execute();

        // clean cars from sysFolder
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
        $queryBuilder->delete('tx_cardealer_domain_model_car')->where(
            $queryBuilder->expr()->eq('pid', $this->storagePid)
        )->execute();

        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cardealer');
        $conf = TaskLibrary::getTyposcriptConf('tx_cardealer_pi1');

        try {
            $result = $this->writeCars($extConf,$conf);
        }
        catch(\Exception $exception) {
            // send info mail
            $subject = 'Fehler beim Import der Fahrzeuge';
            TaskLibrary::sendInfoMail($extConf['infoEmail'], $extConf['baseURL'], $subject, $exception);

            $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### DER IMPORT DER FAHRZEUGE IST LEIDER FEHLGESCHLAGEN #####
            ............................................................'.$exception->getMessage().'');
            throw $ex;
        }

        return $result;
    }



    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return string Information to display
     */
    public function getAdditionalInformation()
    {
        return 'StoragePid='.$this->storagePid . ', Standort='.$this->location;
    }


    /**
     * @param $extConf
     * @param $conf
     * @return mixed
     */
    function writeCars($extConf,$conf) {

        $storagePid = $this->storagePid;

        $arrUsernames = GeneralUtility::trimExplode(',', $extConf['config']['usernames']);
        $arrPasswords = GeneralUtility::trimExplode(',', $extConf['config']['passwords']);
        $arrLocations = GeneralUtility::trimExplode(',', $extConf['config']['locations']);
        $arrEmails = GeneralUtility::trimExplode(',', $extConf['config']['emails']);
        foreach ($arrLocations as $index => $item) {
            $configArray[$item]['username'] = $arrUsernames[$index];
            $configArray[$item]['password'] = $arrPasswords[$index];
            $configArray[$item]['email'] = $arrEmails[$index];
        }

        $replace = trim($conf['settings.']['replace']);
        $replaceBy = trim($conf['settings.']['replaceBy']);
        $table = 'tx_cardealer_domain_model_car';

        if ($this->location != 'all') {
            $configuration[$this->location] = $configArray[$this->location];
        } else {
            $configuration = $configArray;
        }

        $addKeys = TaskLibrary::collectAddKeys($configuration, $this->location);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
        $queryBuilder->insert('tx_cardealer_task_queue')
            ->values(
                array(
                    'taskid' => $this->getTaskUid(),
                    'totalitems' => count($addKeys)
                ))
            ->execute();

        $i = 0;
        foreach ($addKeys as $key => $value) {
            $host = 'https://services.mobile.de/search-api/ad/' . $value['key'] . '';
            $addXmlArray = TaskLibrary::getXmlData( $value['username'], $value['password'], '', '', $host );
            
            foreach ($addXmlArray as $k => $add) {
                $carsImportArr = $this->createCarsImportArray($key, $add, $replace, $replaceBy, $value);
            }

            $results = $this->writeCarsToDB($table, $carsImportArr, $storagePid, $conf);


            unset($carsImportArr);
        }

        return $results;
    }


    /**
     * @param $table
     * @param $cars
     * @param $storagePid
     * @return mixed
     */
    function writeCarsToDB($table, $cars, $storagePid, $conf)
    {

        foreach ($cars as $key => $car) {
            foreach ($car as $field => $value) {
                $valuesArr[$key]['pid'] = $storagePid;
                $valuesArr[$key][$field] = $value;
            }
        }

        foreach ($valuesArr as $values) {

            try {
                $result = TaskLibrary::insertInto($table, $values);

            }
            catch(\TYPO3\CMS\Scheduler\FailedExecutionException $exception) {
                // send info mail
                $subject = 'Fehler beim Import der Fahrzeuge';
                TaskLibrary::sendInfoMail($conf['infoEmail'], $conf['baseURL'], $subject, $exception);

                $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### DER IMPORT DER FAHRZEUGE IST LEIDER FEHLGESCHLAGEN #####
            ............................................................'.$exception->getMessage().'');
                throw $ex;
            }


        }
        return $result;
    }


    /**
     * @param $key
     * @param $add
     * @param $replace
     * @param $replaceBy
     * @param $value
     * @return array
     */
    protected function createCarsImportArray ($key, $add, $replace, $replaceBy, $value): array
    {

        $x = $key;

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        // AD:IMAGES
        $images = array();
        if($add['AD:IMAGES']) {
            $imgCnt = count($add['AD:IMAGES']['AD:IMAGE']);
        }

        // mit nur einem bild sieht das array anders aus
        if ($imgCnt == 1) {
            $addImages = $add['AD:IMAGES'];
        } else {
            $addImages = $add['AD:IMAGES']['AD:IMAGE'];
        }


        if ($imgCnt > 0) {
            $a = 1;
            foreach ($addImages as $image) {
                $b = $a++;
                if ($imgCnt == 1) {
                    foreach ($add['AD:IMAGES']['AD:IMAGE']['AD:REPRESENTATION'] as $representation) {
                        if ($representation['SIZE'] == 'XL') {
                            $url = $representation['URL'];
                            $images[] = $url;
                        }
                    }
                } else {
                    foreach ($image['AD:REPRESENTATION'] as $repKey => $representation) {
                        if ($representation['SIZE'] == 'XL') {
                            $url = $representation['URL'];
                            $images[] = $url;
                        }
                    }
                }
            }
        }
        // AD:IMAGES END

        // AD:FEATURES
        if (is_array($add['AD:VEHICLE']['AD:FEATURES']['AD:FEATURE'])) {
            foreach ($add['AD:VEHICLE']['AD:FEATURES']['AD:FEATURE'] as $feature) {
                if (is_array($feature) && $feature['RESOURCE:LOCAL-DESCRIPTION']) {
                    $nextKey = array_keys($feature['RESOURCE:LOCAL-DESCRIPTION']);
                    $featureKey = $feature['RESOURCE:LOCAL-DESCRIPTION'][$nextKey[0]]['content'];
                    $featureUidArr[] = $this->getRefID('tx_cardealer_domain_model_feature', $featureKey, 'title');
                }
            }
        }

        // AD:MAKE
        $makeKey = $add['AD:VEHICLE']['AD:MAKE']['KEY'];
        $makeUid = $this->getRefID('tx_cardealer_domain_model_make', $makeKey, 'title');

        // AD:MODEL
        if (isset($add['AD:VEHICLE']['AD:MODEL']['KEY'])) {
            $modelKey = $add['AD:VEHICLE']['AD:MODEL']['KEY'];
            $modelUid = $this->getRefID('tx_cardealer_domain_model_model', $modelKey, 'title');
        } else {
            // kein AD:MODEL vorhanden. scheint nur bei Kasten/Kastenwagen der fall zu sein. hier verwnden wir daher die AD:MODEL-DESCRIPTION als wert für das model
            $modelDescriptionArr = explode(' ', $add['AD:VEHICLE']['AD:MODEL-DESCRIPTION']['VALUE']);
            $modelUid = $this->getRefID('tx_cardealer_domain_model_model', $modelDescriptionArr[0], 'title');
        }

        // AD:FUEL
        $fuelKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:FUEL']['KEY'];
        $fuelUid = $this->getRefID('tx_cardealer_domain_model_fuel', $fuelKey);

        // AD:COUNTRYVERSION
        $countryversionKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:COUNTRYVERSION']['KEY'];
        $countryversionUid = $this->getRefID('tx_cardealer_domain_model_countryversion', $countryversionKey);

        // AD:PARKING-ASSISTANTS
        $parking_assistanceKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:PARKING-ASSISTANTS']['AD:PARKING-ASSISTANT']['KEY'];
        $parking_assistanceUid = $this->getRefID('tx_cardealer_domain_model_parkingassistant', $parking_assistanceKey);

        // AD:AIRBAG
        $airbagKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:AIRBAG']['KEY'];
        $airbagUid = $this->getRefID('tx_cardealer_domain_model_airbag', $airbagKey);

        // AD:EMISSION-CLASS
        $emissionclassKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EMISSION-CLASS']['KEY'];
        $emissionclassUid = $this->getRefID('tx_cardealer_domain_model_emissionclass', $emissionclassKey);

        // AD:EMISSION-STICKER
        $emissionstickerKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EMISSION-STICKER']['KEY'];
        $emissionstickerUid = $this->getRefID('tx_cardealer_domain_model_emissionsticker', $emissionstickerKey);

        // AD:INTERIOR-TYPE
        $interiortypeKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:INTERIOR-TYPE']['KEY'];
        $interiortypeUid = $this->getRefID('tx_cardealer_domain_model_interiortype', $interiortypeKey);

        // AD:INTERIOR-COLOR
        $interiorcolorKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:INTERIOR-COLOR']['KEY'];
        $interiorcolorUid = $this->getRefID('tx_cardealer_domain_model_interiorcolor', $interiorcolorKey);

        // AD:DOOR-COUNT
        $doorCountKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:DOOR-COUNT']['KEY'];
        $doorCountUid = $this->getRefID('tx_cardealer_domain_model_doorcount', $doorCountKey);

        // AD:USAGE-TYPE
        $usagetypeKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:USAGE-TYPE']['KEY'];
        $usagetypeUid = $this->getRefID('tx_cardealer_domain_model_usagetype', $usagetypeKey);


        // AD:CONDITION
        $conditionKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:CONDITION']['KEY'];
        $conditionUid = $this->getRefID('tx_cardealer_domain_model_carcondition', $conditionKey);

        // AD:GEARBOX
        $gearboxKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:GEARBOX']['KEY'];
        $gearboxUid = $this->getRefID('tx_cardealer_domain_model_gearbox', $gearboxKey);

        // AD:EXTERIOR-COLOR
        $extColorKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EXTERIOR-COLOR']['KEY'];
        $extColorUid = $this->getRefID('tx_cardealer_domain_model_exteriorcolor', $extColorKey);

        // AD:CLIMATISATION
        $climatisationKey = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:CLIMATISATION']['KEY'];
        $climatisationUid = $this->getRefID('tx_cardealer_domain_model_climatisation', $climatisationKey);

        // AD:CLASS (pkw, lkw usw.)
        $addclassKey = $add['AD:VEHICLE']['AD:CLASS']['KEY'];
        $addclassUid = $this->getRefID('tx_cardealer_domain_model_carclass', $addclassKey);

        // AD:CATEGORY
        $addcategoryKey = $add['AD:VEHICLE']['AD:CATEGORY']['KEY'];
        $addcategoryUid = $this->getRefID('tx_cardealer_domain_model_category', $addcategoryKey);

        // AD:NUMBER-OF-PREVIOUS-OWNERS
        $number_owners = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:NUMBER-OF-PREVIOUS-OWNERS'];

        // AD:ENERGY-EFFICIENCY-CLASS
        $efficiency_class = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EMISSION-FUEL-CONSUMPTION']['ENERGY-EFFICIENCY-CLASS'];

        // AD:CO2-EMISSION
        $co2_emission = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EMISSION-FUEL-CONSUMPTION']['CO2-EMISSION'];

        // AD:INNER
        $inner_consumption = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EMISSION-FUEL-CONSUMPTION']['INNER'];

        // AD:OUTER
        $outer_consumption = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EMISSION-FUEL-CONSUMPTION']['OUTER'];

        // AD:COMBINED
        $combined_consumption = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:EMISSION-FUEL-CONSUMPTION']['COMBINED'];

        // AD:MILEAGE
        $mileage = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:MILEAGE']['VALUE'];

        // AD:GENERAL-INSPECTION
        $general_inspection_string = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:GENERAL-INSPECTION']['VALUE'];
        $general_inspection_obj = new \DateTime($general_inspection_string);
        $general_inspection = $general_inspection_obj->getTimestamp();

        // AD:POWER
        $power = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:POWER']['VALUE'];

        // AD:FIRST-REGISTRATION
        $first_registration_string = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:FIRST-REGISTRATION']['VALUE'];
        $first_registration_obj = new \DateTime($first_registration_string);
        $first_registration = $first_registration_obj->getTimestamp();

        // AD:CUBIC-CAPACITY
        $cubic_capacity = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:CUBIC-CAPACITY']['VALUE'];

        // AD:NUM-SEATS
        $num_seats = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:NUM-SEATS']['VALUE'];

        // AD:MODEL-DESCRIPTION
        $model_description = $add['AD:VEHICLE']['AD:MODEL-DESCRIPTION']['VALUE'];

        // AD:CREATION-DATE
        $creation_date_string = $add['AD:CREATION-DATE']['VALUE'];
        $creation_date_obj = new \DateTime($creation_date_string);
        $creation_date = $creation_date_obj->getTimestamp();


        // AD:DELIVERY-DATE
        $delivery_date_string = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:DELIVERY-DATE']['VALUE'];
        $delivery_date_obj = new \DateTime($delivery_date_string);
        $delivery_date = $delivery_date_obj->getTimestamp();

        // AD:DESCRIPTION
        if ($replace) {
            $description = str_replace($replace, $replaceBy, $add['AD:ENRICHEDDESCRIPTION']);
        } else {
            $description = $add['AD:ENRICHEDDESCRIPTION'];
        }

        // AD:HSN
        $hsn = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:KBA']['HSN'];
        if ($hsn == null) {
            $hsn = '';
        }

        // AD:TSN
        $tsn = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:KBA']['TSN'];
        if ($tsn == null) {
            $tsn = '';
        }

        // AD:SCHWACKE-CODE
        $schwacke = $add['AD:VEHICLE']['AD:SPECIFICS']['AD:SCHWACKE-CODE']['VALUE'];
        if ($schwacke == null) {
            $schwacke = '';
        }


        // AD:PRICE
        if ($add['AD:PRICE']['AD:DEALER-PRICE-AMOUNT']['VALUE']) {
            $priceTmp = $add['AD:PRICE']['AD:DEALER-PRICE-AMOUNT']['VALUE'];
        } elseif ($add['AD:PRICE']['AD:CONSUMER-PRICE-AMOUNT']['VALUE']) {
            $priceTmp = $add['AD:PRICE']['AD:CONSUMER-PRICE-AMOUNT']['VALUE'];
        }
        $price = ceil($priceTmp);
        $vatable = $add['AD:PRICE']['AD:VATABLE']['VALUE'];

        if($add['AD:VEHICLE']['AD:ACCIDENT-DAMAGED']['VALUE'] == 'true') {
            $cars[$x]['accidentdamaged'] = 1;
        } elseif($add['AD:VEHICLE']['AD:ACCIDENT-DAMAGED']['VALUE'] == 'false') {
            $cars[$x]['accidentdamaged'] = 0;
        } elseif(!$add['AD:VEHICLE']['AD:ACCIDENT-DAMAGED']['VALUE']) {
            $cars[$x]['accidentdamaged'] = 999;
        }

        if($add['AD:VEHICLE']['AD:DAMAGE-AND-UNREPAIRED']['VALUE'] == 'true') {
            $cars[$x]['damageunrepaired'] = 1;
        } elseif($add['AD:VEHICLE']['AD:DAMAGE-AND-UNREPAIRED']['VALUE'] == 'false') {
            $cars[$x]['damageunrepaired'] = 0;
        } elseif(!$add['AD:VEHICLE']['AD:DAMAGE-AND-UNREPAIRED']['VALUE']) {
            $cars[$x]['damageunrepaired'] = 999;
        }

        if($add['AD:VEHICLE']['AD:ROADWORTHY']['VALUE'] == 'true') {
            $cars[$x]['roadworthy'] = 1;
        } elseif($add['AD:VEHICLE']['AD:ROADWORTHY']['VALUE'] == 'false') {
            $cars[$x]['roadworthy'] = 0;
        } elseif(!$add['AD:VEHICLE']['AD:ROADWORTHY']['VALUE']) {
            $cars[$x]['roadworthy'] = 999;
        }

        // SELLER:SELLER
        $company_name = $add['SELLER:SELLER']['SELLER:COMPANY-NAME']['VALUE'];
        $address = $add['SELLER:SELLER']['SELLER:ADDRESS']['SELLER:STREET']['VALUE'];
        $zip = $add['SELLER:SELLER']['SELLER:ADDRESS']['SELLER:ZIPCODE']['VALUE'];
        $city = $add['SELLER:SELLER']['SELLER:ADDRESS']['SELLER:CITY']['VALUE'];
        if( is_array($add['SELLER:SELLER']['SELLER:PHONE'][0]) ) {
            $phone = $add['SELLER:SELLER']['SELLER:PHONE'][0]['AREA-CODE'] . '-' . $add['SELLER:SELLER']['SELLER:PHONE'][0]['NUMBER'];
        } else {
            $phone = $add['SELLER:SELLER']['SELLER:PHONE']['AREA-CODE'] . '-' . $add['SELLER:SELLER']['SELLER:PHONE']['NUMBER'];
        }

        // AD:COORDINATES
        $coordinates = $add['SELLER:SELLER']['SELLER:COORDINATES']['SELLER:LATITUDE'] . ',' . $add['SELLER:SELLER']['SELLER:COORDINATES']['SELLER:LONGITUDE'];
        if ($add['AD:SELLER-INVENTORY-KEY']['VALUE']) {
            $seller_inventory_key = $add['AD:SELLER-INVENTORY-KEY']['VALUE'];
        } else {
//            $seller_inventory_key = 'Keine Nummer vorhanden';
            $seller_inventory_key = '';
        }

        // Build the Import Data Array
        if (is_array($featureUidArr)) {
            $cars[$x]['feature'] = implode(',', $featureUidArr);
        }
        unset($featureUidArr);
        $cars[$x]['make'] = (int)$makeUid;
        $cars[$x]['model'] = (int)$modelUid;
        $cars[$x]['fuel'] = (int)$fuelUid;
        $cars[$x]['country_version'] = (int)$countryversionUid;
        $cars[$x]['parking_assistant'] = (int)$parking_assistanceUid;
        $cars[$x]['airbag'] = (int)$airbagUid;
        $cars[$x]['interior_type'] = (int)$interiortypeUid;
        $cars[$x]['interior_color'] = (int)$interiorcolorUid;
        $cars[$x]['emission_class'] = (int)$emissionclassUid;
        $cars[$x]['emission_sticker'] = (int)$emissionstickerUid;
        $cars[$x]['door_count'] = (int)$doorCountUid;
        $cars[$x]['exterior_color'] = (int)$extColorUid;
        $cars[$x]['climatisation'] = (int)$climatisationUid;
        $cars[$x]['gearbox'] = (int)$gearboxUid;
        $cars[$x]['add_key'] = $value['key'];
        $cars[$x]['uid'] = $value['key'];
        $cars[$x]['hsn'] = $hsn;
        $cars[$x]['tsn'] = $tsn;
        $cars[$x]['schwacke'] = $schwacke;
        $cars[$x]['carclass'] = (int)$addclassUid;
        if ((int)$usagetypeUid) {
            $cars[$x]['usagetype'] = (int)$usagetypeUid;
        }
        $cars[$x]['carcondition'] = (int)$conditionUid;
        $cars[$x]['category'] = (int)$addcategoryUid;
        $cars[$x]['model_description'] = $model_description;
        $cars[$x]['creation_date'] = $creation_date;
        if ($delivery_date) {
            $cars[$x]['delivery_date'] = $delivery_date;
        }
        if($description) {
            $cars[$x]['description'] = $description;
        } else {
            $cars[$x]['description'] = '';
        }
        $cars[$x]['seller_inventory_key'] = $seller_inventory_key;
        $cars[$x]['price'] = $price;
        $vatable = 0;
        if($vatable == 'true') {
            $vatable = 1;
        }
        $cars[$x]['vatable'] = $vatable;
        $cars[$x]['company_name'] = $company_name;
        $cars[$x]['address'] = $address;
        $cars[$x]['zip'] = $zip;
        $cars[$x]['city'] = $city;
        $cars[$x]['phone'] = $phone;
        $cars[$x]['email'] = $value['email'];
        $cars[$x]['coordinates'] = $coordinates;
        if ($mileage) {
            $cars[$x]['mileage'] = $mileage;
        }
        if ($power) {
            $cars[$x]['power'] = $power;
        }
        if ($num_seats) {
            $cars[$x]['num_seats'] = $num_seats;
        }
        if ($cubic_capacity) {
            $cars[$x]['cubic_capacity'] = $cubic_capacity;
        }
        if ($first_registration) {
            $cars[$x]['first_registration'] = $first_registration;
        }
        if ($number_owners) {
            $cars[$x]['number_owners'] = $number_owners;
        }
        if ($general_inspection) {
            $cars[$x]['general_inspection'] = $general_inspection;
        }
        if ($efficiency_class) {
            $cars[$x]['efficiency_class'] = $efficiency_class;
        }
        if ($co2_emission) {
            $cars[$x]['co2_emission'] = $co2_emission;
        }
        if ($inner_consumption) {
            $cars[$x]['inner_consumption'] = $inner_consumption;
        }
        if ($outer_consumption) {
            $cars[$x]['outer_consumption'] = $outer_consumption;
        }
        if ($combined_consumption) {
            $cars[$x]['combined_consumption'] = $combined_consumption;
        }
        $cars[$x]['location'] = $value['location'];

        $cars[$x]['images_urls'] = implode(',', $images);
        $cars[$x]['image_count'] = $imgCnt;
        $cars[$x]['images'] = 0;
        $cars[$x]['images_as_csv'] = '';

        return $cars;
    }



    function getRefID($table,$keyId,$field="key_id") {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $statement = $queryBuilder
            ->select('uid')
            ->from($table)
            ->where(''.$field.' LIKE "'.$keyId.'" ')
            ->execute();
        while ($row = $statement->fetch()) {
            $refUid = $row['uid'];
        }

        return $refUid;

    }



}
