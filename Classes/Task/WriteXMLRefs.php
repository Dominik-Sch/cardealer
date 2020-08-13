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

use EXOTEC\Cardealer\Utilities\TaskLibrary;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class WriteXMLRefs extends \TYPO3\CMS\Scheduler\Task\AbstractTask  implements \TYPO3\CMS\Scheduler\ProgressProviderInterface {

    var $tables = array(
        'classes/Car/features' => 'tx_cardealer_domain_model_feature',
        'classes' => 'tx_cardealer_domain_model_carclass',
        'categories' => 'tx_cardealer_domain_model_category',
        'fuels' => 'tx_cardealer_domain_model_fuel',
        'gearboxes' => 'tx_cardealer_domain_model_gearbox',
        'climatisations' => 'tx_cardealer_domain_model_climatisation',
        'doorcounts' => 'tx_cardealer_domain_model_doorcount',
        'emissionclasses' => 'tx_cardealer_domain_model_emissionclass',
        'emissionstickers' => 'tx_cardealer_domain_model_emissionsticker',
        'colors' => 'tx_cardealer_domain_model_exteriorcolor',
        'interiorColors' => 'tx_cardealer_domain_model_interiorcolor',
        'interiorTypes' => 'tx_cardealer_domain_model_interiortype',
        'airbags' => 'tx_cardealer_domain_model_airbag',
        'countryVersion' => 'tx_cardealer_domain_model_countryversion',
        'parkingassistants' => 'tx_cardealer_domain_model_parkingassistant',
        'conditions' => 'tx_cardealer_domain_model_carcondition',
        'usagetypes' => 'tx_cardealer_domain_model_usagetype'
    );

    public function getProgress ()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
        $statement = $queryBuilder
            ->select('finisheditems')
            ->from('tx_cardealer_task_queue')
            ->where(
                $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
            )->execute();
        $row = $statement->fetch();

        if(!is_integer($row['finisheditems'])) {
            $percent = 0;
        } else {
            $percent = $row['finisheditems'] / count($this->tables) * 100;
        }


        return $percent;
    }

    /**
     * @return array|bool
     */
    public function execute ()
    {

        // clean progressbar queue
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_cardealer_task_queue')
            ->truncate('tx_cardealer_task_queue');

        try {
            $x = 1;
            foreach ($this->tables as $key => $table) {
                $i = $x++;
                $xmlRefURL = 'https://services.mobile.de/refdata/' . $key;
                $result = $this->writeRefs($xmlRefURL, $table, $this->storagePid);
                $messageTitle[] = $key;



                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
                // instead of update we delete and insert new
                $queryBuilder->delete('tx_cardealer_task_queue')->where(
                    $queryBuilder->expr()->eq('taskid', $this->getTaskUid())
                )->execute();
                $queryBuilder->insert('tx_cardealer_task_queue')
                    ->values(
                        array(
                            'taskid' => $this->getTaskUid(),
                            'totalitems' => count($this->tables),
                            'finisheditems' => $i,
                        ))
                    ->execute();
            }
        }
        catch(\Exception $exception) {
            // send info mail
            $subject = 'Fehler beim Import der XML Referenzen';
            $conf = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('cardealer');
//                unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cardealer']);
            TaskLibrary::sendInfoMail($conf['infoEmail'], $subject, $exception);

            $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### DER IMPORT DER TABELLE "'.$table.'" IST LEIDER FEHLGESCHLAGEN #####
            ............................................................'.$exception->getMessage().'');
            throw $ex;
        }

        return $result;

    }


    /**
     * @param $url
     * @param $table
     * @param $storagePid
     * @param string $prefKeys
     * @return mixed
     */
    function writeRefs ($url, $table, $storagePid)
    {

        $extConf = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('cardealer');
//            unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cardealer']);



        // send german language header
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: de\r\n"
            )
        );
        $context = stream_context_create($opts);
        $xml = file_get_contents($url, false, $context);


        if (!$xml) {
            // falls fehlgeschlagen kurz warten dann erneut versuchen
            sleep(5);
            $xml = file_get_contents($url, false, $context);
        }

        $xmlArr = TaskLibrary::XMLtoArray($xml);


        switch ($table) {
            case 'tx_cardealer_domain_model_carclass':
                $prefKeys = $extConf['carclasss'];
                break;
            case 'tx_cardealer_domain_model_usagetype':
                $prefKeys = $extConf['usagetypes'];
                break;
            case 'tx_cardealer_domain_model_category':
                $prefKeys = $extConf['categories'];
                break;
            case 'tx_cardealer_domain_model_feature':
                $prefKeys = $extConf['features'];
                break;
            default:
                $prefKeys = '*';
        }
        // prefered values to import
        $prefKeysArr = explode(',', $prefKeys);

        // TRUNCATE $table
        GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->truncate($table);

        foreach ($xmlArr['REFERENCE:REFERENCE']['REFERENCE:ITEM'] as $i => $feature) {
            $key = $feature['KEY'];
            $title = $feature['RESOURCE:LOCAL-DESCRIPTION'][$i]['content'];
            if ($table == 'tx_cardealer_domain_model_countryversion') {
                $title = $feature['KEY'];
            }

            $values = array(
                'pid' => $storagePid,
                'key_id' => $key,
                'title' => $title
            );
            if ($prefKeys != '*' && !in_array($key, $prefKeysArr)) {
                unset($values);
            } else {
                $result = TaskLibrary::insertInto($table, $values);
            }
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
        return 'StoragePid='.$this->storagePid ;
    }


}
