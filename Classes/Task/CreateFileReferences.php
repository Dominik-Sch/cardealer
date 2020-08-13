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

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;

class CreateFileReferences extends \TYPO3\CMS\Scheduler\Task\AbstractTask  implements \TYPO3\CMS\Scheduler\ProgressProviderInterface {

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;


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
     * @return bool
     * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
     */
    public function execute() {

        // clean sys_file
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file')
            ->delete('sys_file')
            ->where('identifier LIKE "%tx_cardealer_pi1%"')
            ->execute();

        // clean sys_file_reference
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference')
            ->delete('sys_file_reference')
            ->where('tablenames = "tx_cardealer_domain_model_car"')
            ->execute();

        $storagePid = $this->storagePid;
        $storageFolder = $this->storageFolder;

        $path = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/fileadmin/'.$storageFolder;

        // cleanup images
        $files = GeneralUtility::getFilesInDir($path, 'jpg');
        foreach ($files as $file) { // iterate files
            if (is_file($path . $file)) {
                unlink($path . $file);
            } // delete file
        }

        // create image dir if not exists
        if(!is_dir($path)) {
            @mkdir($path, 0777, true);
        }

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        /** @var \EXOTEC\Cardealer\Domain\Repository\CarRepository $carRepository */
        $carRepository = $objectManager->get('EXOTEC\\Cardealer\\Domain\\Repository\\CarRepository');

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setStoragePageIds(GeneralUtility::trimExplode(',', $storagePid));
        $carRepository->setDefaultQuerySettings($querySettings);

        $cars = $carRepository->findAll();

        // progressBar
        /** @var \EXOTEC\Cardealer\Domain\Model\Car $add */
        foreach ($cars as $add) {
            $itemsTotal += count(GeneralUtility::trimExplode(',', $add->getImagesAsCsv()));
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


        /** @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
        $storageRepository = $objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        $storage = $storageRepository->findByUid('1');
        $targetFolder = new \TYPO3\CMS\Core\Resource\Folder($storage, $storageFolder.'/', $storageFolder);

        /** @var \EXOTEC\Cardealer\Domain\Model\Car $car */
        foreach ($cars as $car) {
            // images as csv
            $imagesNames = explode(',', $car->getImagesAsCsv());
            foreach ($imagesNames as $imagesName) {
                $originalFilePath = \TYPO3\CMS\Core\Core\Environment::getPublicPath().'/uploads/tx_cardealer/'.$imagesName;

                // create file references
                if (file_exists($originalFilePath) && pathinfo($originalFilePath, PATHINFO_EXTENSION) == 'jpg') {
                    // copy images to fileadmin
                    $storage->addFile($originalFilePath, $targetFolder, $imagesName, DuplicationBehavior::RENAME, false);

                    // create file references
                    $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
                    $file = $resourceFactory->getFileObjectFromCombinedIdentifier('1:/'.$targetFolder->getName().$imagesName);
                    $this->createFileReference($car->getUid(),$car->getPid(),$file);
                } else {
//                    DebuggerUtility::var_dump($originalFilePath);
                }
            }

            // progressBar
            $finisheditems += count($imagesNames);
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

        return true;
    }

    /**
     * @param $contentUid
     * @param $pid
     * @param File $file
     */
    protected function createFileReference($contentUid, $pid, File $file)
    {
        $data = [
            'sys_file_reference' => [
                StringUtility::getUniqueId('NEW') => [
                    'table_local' => 'sys_file',
                    'uid_local' => $file->getProperty('uid'),
                    'tablenames' => 'tx_cardealer_domain_model_car',
                    'uid_foreign' => $contentUid,
                    'fieldname' => 'image',
                    'pid' => $pid,
                ]
            ]
        ];
        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($data, []);
        $dataHandler->admin = true;
        $dataHandler->process_datamap();
    }


    /**
     * This method returns additional information about the task.
     * This additional information is used in the Scheduler's BE module.
     *
     * @return string Information to display
     */
    public function getAdditionalInformation()
    {
        return 'StoragePid="'.$this->storagePid . '", StorageFolder="'.$this->storageFolder.'"';
    }


}
