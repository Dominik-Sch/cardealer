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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use EXOTEC\Cardealer\Utilities\TaskLibrary;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\DataHandling\SlugHelper;


class WriteModelsAndMakes extends \TYPO3\CMS\Scheduler\Task\AbstractTask  implements \TYPO3\CMS\Scheduler\ProgressProviderInterface, \Countable {

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count() {

    }

    /**
     * @return float|int
     */
    public function getProgress ()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
        $statement = $queryBuilder
            ->select('*')
            ->from('tx_cardealer_task_queue')
            ->where(
                $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
            )->execute();
        $row = $statement->fetch();

        if(!is_integer($row['finisheditems'])) {
            $percent = 0;
        } else {
            $percent = $row['finisheditems'] / $row['totalitems'] * 100;
        }


        return $percent;
    }

    public function execute()
    {


        // clean tx_cardealer_domain_model_make
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_cardealer_domain_model_make')
            ->truncate('tx_cardealer_domain_model_make');

        // clean tx_cardealer_domain_model_model
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_cardealer_domain_model_model')
            ->truncate('tx_cardealer_domain_model_model');

        // clean tx_cardealer_domain_model_car
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_cardealer_domain_model_car')
            ->truncate('tx_cardealer_domain_model_car');

        // clean progressbar queue
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
        $queryBuilder->delete('tx_cardealer_task_queue')->where(
            $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
        )->execute();

        try {
            $result = $this->writeModelsAndMakesAction();
        }
        catch(\Exception $exception) {

            DebuggerUtility::var_dump($exception);

            // send info mail
            $subject = 'Fehler beim Import der Hersteller und Modelle';
            #$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cardealer']);
            $conf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cardealer');
            TaskLibrary::sendInfoMail($conf['infoEmail'],'seltenhofer.de' , $subject, $exception);


            $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### DER IMPORT DER HERSTELLER UND MODELLE IST LEIDER FEHLGESCHLAGEN #####
            ............................................................'.$exception->getMessage().'');
            throw $ex;
        }

        return $result;
    }


    /**
     * writeModelsAndMakesAction
     */
    function writeModelsAndMakesAction()
    {
        $storagePid = $this->storagePid;
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cardealer');

        $arrUsernames = GeneralUtility::trimExplode(',', $extConf['config']['usernames']);
        $arrPasswords = GeneralUtility::trimExplode(',', $extConf['config']['passwords']);
        $arrLocations = GeneralUtility::trimExplode(',', $extConf['config']['locations']);
        foreach ($arrLocations as $index => $item) {
            $configArray[$item]['username'] = $arrUsernames[$index];
            $configArray[$item]['password'] = $arrPasswords[$index];
        }

        $x = 0;
        foreach ($configArray as $location => $config) {


            $totalArr[$key] = TaskLibrary::getXmlData($config['username'], $config['password']);
            $total = $totalArr[$key]['SEARCH:SEARCH-RESULT']['SEARCH:TOTAL'];
            $steps = ceil($total / 100);

            for ($i = 1; $i <= $steps; $i++) {
                $pageNumber = '&page.number=' . $i . '';
                $xmlArray = TaskLibrary::getXmlData($config['username'], $config['password'], $pageNumber);

                if( is_array($xmlArray['SEARCH:SEARCH-RESULT']['SEARCH:ADS']['AD:AD']) ) {
                    foreach ($xmlArray['SEARCH:SEARCH-RESULT']['SEARCH:ADS']['AD:AD'] as $add) {
                        $y = $x++;

                        // only one result
                        if ($this->count($xmlArray['SEARCH:SEARCH-RESULT']['SEARCH:ADS']['AD:AD']) == 9) {
                            $nextKey = array(0 => 0);
                            if (count($add) == 8) {
                                // MAKE
                                $make = $add['AD:MAKE'];
                                $makeMem = $make . $makeMem;
                                // MODEL
                                if (is_array($add['AD:VEHICLE']['AD:MODEL'])) {
                                    $nextKeyModel = array_keys($add['AD:VEHICLE']['AD:MODEL']);
                                    $modelsAndMakesRawArr[$make][$y] = $add['AD:VEHICLE']['AD:MODEL'][$nextKeyModel[0]]['KEY'];
                                }
                            }
                        } else {
                            $xmlArray = $xmlArray['SEARCH:SEARCH-RESULT']['SEARCH:ADS']['AD:AD'];
                            $nextKey = array_keys($add['AD:VEHICLE']);
                            $make = $add['AD:VEHICLE'][$nextKey[0]]['AD:MAKE'][$nextKey[0]]['KEY'];

                            // MODELS
                            if (isset($add['AD:VEHICLE'][$nextKey[0]]['AD:MODEL'])) {
                                $nextKeyModel = array_keys($add['AD:VEHICLE'][$nextKey[0]]['AD:MODEL']);
                                if (is_int($nextKey)) {
                                    $modelsAndMakesRawArr[$make][] = $add['AD:VEHICLE'][$nextKey[0]]['AD:MODEL'][$nextKeyModel[0]]['KEY'];
                                }
                            } else {
                                // kein AD:MODEL vorhanden. scheint nur bei Kasten/Kastenwagen der fall zu sein. also nehmen wir die AD:MODEL-DESCRIPTION asl wert für das model
                                $modelDescriptionArr = explode(' ',
                                    $add['AD:VEHICLE'][$nextKey[0]]['AD:MODEL-DESCRIPTION']['VALUE']);
                                $modelsAndMakesRawArr[$make][] = $modelDescriptionArr[0];
                            }
                        }

                        $makesArr[] = $make;
                    }
                }
            }
        }

        // remove double Models
        foreach ($modelsAndMakesRawArr as $make => $model) {
            $models[$make] = array_unique($model);
            asort($models[$make]);
            $modelsAndMakesArr[$make] = array_values($models[$make]);
        }

        // empty make and model tables
        // tx_cardealer_domain_model_model
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_cardealer_domain_model_model')
            ->truncate('tx_cardealer_domain_model_model');

        // tx_cardealer_domain_model_make
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_cardealer_domain_model_make')
            ->truncate('tx_cardealer_domain_model_make');

        // progressBar count
        foreach ($modelsAndMakesArr as $modelArr) {
            $modelsCount += count($modelArr);
        }

        $itemsTotal = $modelsCount + count($modelsAndMakesArr);

        // write models to DB
        $x = 1;
        foreach ($modelsAndMakesArr as $makeTitle => $modelArr) {
            unset($modelUIDs);


            foreach ($modelArr as $model) {
                $i = $x++;
                $values = array(
                    'pid' => $storagePid,
                    'title' => $model
                );
                $lastInsertModelId = TaskLibrary::insertInto('tx_cardealer_domain_model_model', $values);

                unset($model);

                $modelUIDs .= $lastInsertModelId . ',' . $modelID;
                $finalModelsArr[$makeTitle] = $modelUIDs;

                // progressBar
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
                // instead of update we delete and insert new
                $queryBuilder->delete('tx_cardealer_task_queue')->where(
                    $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
                )->execute();
                $queryBuilder->insert('tx_cardealer_task_queue')
                    ->values(
                        array(
                            'taskid' => $this->getTaskUid(),
                            'totalitems' => $itemsTotal,
                            'finisheditems' => $i,
                        ))
                    ->execute();

            }


        }

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        /** @var \TYPO3\CMS\Extbase\Persistence\Repository $modelRepository */
        $modelRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\ModelRepository');

        $slugHelper = $objectManager->get(SlugHelper::class, 'tx_cardealer_domain_model_make', 'slug', array());

        // write makes to DB
        $x = $i+1;
        foreach ($finalModelsArr as $makeTitle => $modelUidList) {
            $i = $x++;
            $values = array(
                'pid' => $storagePid,
                'title' => $makeTitle,
                'slug' => $slugHelper->sanitize($makeTitle),
                'models' => $modelUidList
            );
            $lastInsertMakeId = TaskLibrary::insertInto('tx_cardealer_domain_model_make', $values);
            foreach (GeneralUtility::trimExplode(',', $modelUidList) as $uid) {
                if ($uid) {
                    /** @var \EXOTEC\Cardealer\Domain\Model\Model $model */
                    $model = $modelRepository->findByUid($uid);
                     if(TaskLibrary::updateTable('tx_cardealer_domain_model_model', 'make', $lastInsertMakeId, $model->getUid())) {
                         $result = true;
                     } else {
                         $result = false;
                     }

                }
            }
            unset($makeTitle);

            // progressBar
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
            // instead of update we delete and insert new
            $queryBuilder->delete('tx_cardealer_task_queue')->where(
                $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
            )->execute();
            $queryBuilder->insert('tx_cardealer_task_queue')
                ->values(
                    array(
                        'taskid' => $this->getTaskUid(),
                        'totalitems' => $itemsTotal,
                        'finisheditems' => $i,
                    ))
                ->execute();
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
        return 'StoragePid='.$this->storagePid;
    }



}
