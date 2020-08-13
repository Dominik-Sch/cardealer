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

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Additional fields provider class for usage with the Scheduler task
 */
class CreateFileReferencesAdditionalFieldProvider extends \TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface
{

    /**
     * @param array $taskInfo
     * @param AbstractTask $task
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject
     * @return array
     */
    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        // Initialize extra fields values
        $taskInfo['storagePid'] = $task->storagePid;
        $taskInfo['storageFolder'] = $task->storageFolder;

        // Write the code for the field
        $field_task_storagePid = 'task_storagePid';
        $field_task_storageFolder = 'task_storageFolder';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[storagePid]" id="' . $field_task_storagePid . '" value="' . htmlspecialchars($taskInfo['storagePid']) . '" size="30">';
        $fieldCode2 = '<input type="text" class="form-control" name="tx_scheduler[storageFolder]" id="' . $field_task_storageFolder . '" value="' . htmlspecialchars($taskInfo['storageFolder']) . '" size="30">';
        $additionalFields = [];
        $additionalFields[$field_task_storagePid] = [
            'code' => $fieldCode,
            'label' => 'Storage PID',
            'cshKey' => '_MOD_system_txschedulerM1',
            'cshLabel' => $field_task_storagePid
        ];
        $additionalFields[$field_task_storageFolder] = [
            'code' => $fieldCode2,
            'label' => 'Storage Folder',
            'cshKey' => '_MOD_system_txschedulerM1',
            'cshLabel' => $field_task_storageFolder
        ];
        return $additionalFields;
    }

    /**
     * @param array	 $submittedData Reference to the array containing the data submitted by the user
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject Reference to the calling object (Scheduler's BE module)
     * @return bool TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
     */
    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        $submittedData['storagePid'] = trim($submittedData['storagePid']);
        $submittedData['storageFolder'] = trim($submittedData['storageFolder']);
        if ( empty($submittedData['storagePid']) || empty($submittedData['storageFolder']) ) {
            if(empty($submittedData['storagePid'])) {
//                $this->addMessage($GLOBALS['LANG']->sL('Keine Storage PID angegeben!'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            } else {
//                $this->addMessage($GLOBALS['LANG']->sL('Kein Storage Folder angegeben!'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            }
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    /**
     * This method is used to save any additional input into the current task object
     * if the task class matches
     *
     * @param array $submittedData Array containing the data submitted by the user
     * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task Reference to the current task object
     * @return void
     */
    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task)
    {
        $task->storagePid = $submittedData['storagePid'];
        $task->storageFolder = $submittedData['storageFolder'];
    }
}
