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
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class CopyImages extends \TYPO3\CMS\Scheduler\Task\AbstractTask  implements \TYPO3\CMS\Scheduler\ProgressProviderInterface {


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

    /**
     * @return bool|string
     */
    public function execute ()
    {

        // delete all images
        $path = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/uploads/tx_cardealer/';
        $files = GeneralUtility::getFilesInDir($path, 'jpg,wbmp');
        foreach ($files as $file) { // iterate files
            if (is_file($path . $file)) {
                unlink($path . $file);
            } // delete file
        }

        $addsInDB = $this->getAllAddsInDB();
//        DebuggerUtility::var_dump($addsInDB);
//        exit;

        // progressBar
        foreach ($addsInDB as $add) {
            $itemsTotal += count( GeneralUtility::trimExplode(',', $add['images_as_csv']) );
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_task_queue');
        $queryBuilder->insert('tx_cardealer_task_queue')
            ->values(
                array(
                    'taskid' => $this->getTaskUid(),
                    'totalitems' => $itemsTotal,
                    'finisheditems' => '0',
                ))
            ->execute();


        // get Images
        foreach ($addsInDB as $i => $value) {
            $output = $this->getImages($path, $value);

            // progressBar
            $finisheditems += count(GeneralUtility::trimExplode(',', $value['images_as_csv']));
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
                        'finisheditems' => $finisheditems,
                    ))
                ->execute();

        }
        return $output;

    }


    /**
     * @return mixed
     */
    function getAllAddsInDB ()
    {
        $table = 'tx_cardealer_domain_model_car';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $statement = $queryBuilder
            ->select('uid', 'add_key', 'images_urls', 'image_count')
            ->from($table)
            ->execute();
        while ($row = $statement->fetch()) {
            $adds[] = $row;
        }

        return $adds;
    }


    /**
     * @param $filename
     */
    function setMemoryLimit ($filename)
    {
        set_time_limit(50);
        $maxMemoryUsage = 258;
        $width = 0;
        $height = 0;
        $size = ini_get('memory_limit');

        list($width, $height) = getimagesize($filename);
        $size = $size + floor(($width * $height * 4 * 1.5 + 1048576) / 1048576);

        if ($size > $maxMemoryUsage) {
            $size = $maxMemoryUsage;
        }

        ini_set('memory_limit', $size . 'M');

    }


    /**
     * @param $path
     * @param $add
     * @return string
     */
    function getImages ($path, $add)
    {
        set_time_limit(0);
        $mh = curl_multi_init(); // init the curl Multi
        $aCurlHandles = array(); // create an array for the individual curl handles
        $imageNames = array();
        $imageUrl = array();
        $aURLs = explode(',', $add['images_urls']);

        foreach ($aURLs as $id => $url) { //add the handles for each url

            $imageNames[$id] = $add['add_key'] . '_' . ($id+1) . '.jpg';
            $imageUrl[$id] = $url;

            $aCurlHandles[$id] = curl_init($url); // init curl, and then setup your options

            curl_setopt($aCurlHandles[$id], CURLOPT_URL, $url);
            curl_setopt($aCurlHandles[$id], CURLOPT_HEADER , 0);
            curl_setopt($aCurlHandles[$id], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($aCurlHandles[$id], CURLOPT_FOLLOWLOCATION, true); # optional
            curl_setopt($aCurlHandles[$id], CURLOPT_VERBOSE, false);
            //curl_setopt($aCurlHandles[$id], CURLOPT_TIMEOUT, -1); # optional: -1 = unlimited, 3600 = 1 hour
            //curl_setopt($aCurlHandles[$id], CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($aCurlHandles[$id], CURLOPT_SSL_VERIFYPEER, false);

            curl_multi_add_handle ($mh, $aCurlHandles[$id]);


        }


        $active = null;
        //execute the handles
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) == -1) {
                usleep(100);
            }

            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }


        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
        $FullTS = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT, 'cardealer', 'tx_cardealer_pi1_list');
        $outputInner = '';
        $outputError = '';

        foreach ($aCurlHandles as $id => $ch) {

            $path_to_file = $path . $imageNames[$id];
            $fh = fopen($path_to_file, 'w') or die("Can't create file");
            $bytesWritten = file_put_contents($path_to_file, curl_multi_getcontent($ch));

            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            $outputInner .= '<div style="white-space:nowrap;">Image: ';
            $outputInner .= '<a href="' . substr($FullTS['config.']['baseURL'], 0, -1) . '/uploads/tx_cardealer/' . $imageNames[$id] . '" target="_blank">' . $imageNames[$id] . '</a>';
            $outputInner .= ' / <a href="' . $imageUrl[$id] . '" target="_blank">';

            // Check if image was downloaded successfully
            if($bytesWritten && $imagetype = $this->imagetype($path_to_file)) {

                // If image is no jpg
                if($imagetype !== 2) {

                    // cut file extension
                    if(($cutoff = mb_strrpos($imageNames[$id], '.'))) {
                        $imageNames[$id] = mb_substr($imageNames[$id], 0, $cutoff);
                    }

                    // set extension
                    switch($imagetype) {
                        case 1: $imageNames[$id] .= '.gif'; break;
                        case 3: $imageNames[$id] .= '.png'; break;
                        case 4: $imageNames[$id] .= '.swf'; break;
                        case 5: $imageNames[$id] .= '.psd'; break;
                        case 6: $imageNames[$id] .= '.bmp'; break;
                        case 7: $imageNames[$id] .= '.tif'; break;
                        case 8: $imageNames[$id] .= '.tif'; break;
                        case 9: $imageNames[$id] .= '.jpc'; break;
                        case 10: $imageNames[$id] .= '.jp2'; break;
                        case 11: $imageNames[$id] .= '.jpx'; break;
                        case 12: $imageNames[$id] .= '.jb2'; break;
                        case 13: $imageNames[$id] .= '.swc'; break;
                        case 14: $imageNames[$id] .= '.iff'; break;
                        case 15: $imageNames[$id] .= '.wbmp'; break;
                        case 16: $imageNames[$id] .= '.xbm'; break;
                        case 17: $imageNames[$id] .= '.ico'; break;
                        case 18: $imageNames[$id] .= '.webp'; break;
                    }

                    // rename image file
                    if(rename($path_to_file, $path . $imageNames[$id])) {
                        $path_to_file = $path . $imageNames[$id];
                    }

                }

                $this->setMemoryLimit($path_to_file);

                $outputInner .= 'Original';

            } else {

                $outputInner .= '<strong style="color:#cc3300;">Download failed</strong>';
                $outputError .= '<div><a href="' . $imageUrl[$id] . '" target="_blank" style="color:#cc3300;font-weight:bold;">No&nbsp;image:&nbsp;' . $imageNames[$id] . '</a></div>';

                // Delete empty file
                if(is_file($path_to_file)) {
                    unlink($path_to_file);
                }

                unset($imageNames[$id]);

            }

            $outputInner .= '</a></div>';

        }

        // Build the Import Data Array
        $cars[$actAdd]['add_key'] = $add['add_key'];
        $cars[$actAdd]['images_as_csv'] = implode(',', $imageNames);
        $cars[$actAdd]['image_count'] = count($imageNames);

        // write to DB
        $this->updateCarsInDB($cars);

        /* End of the relevant bit */
        curl_multi_close($mh); // close the curl multi handler


        return true;
    }


    /**
     * @param $cars
     * @return bool
     */
    function updateCarsInDB ($cars)
    {
        foreach ($cars as $key => $value) {
            $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
            $queryBuilder
                ->update('tx_cardealer_domain_model_car')
                ->where($queryBuilder->expr()->eq('add_key', $cars[$key]['add_key']))
                ->set('images_as_csv', $cars[$key]['images_as_csv'])
                ->set('image_count', $cars[$key]['image_count'])
                ->execute();
        }
        return true;
    }

    function imagetype ( $image )
    {
        if ( function_exists( 'exif_imagetype' ) )
            return exif_imagetype( $image);

        $r = getimagesize( $image );
        return $r[2];
    }
}
