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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Additional fields provider class for usage with the Scheduler's test task
 */
class WriteXMLRefsAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface
{
    /**
     * This method is used to define new fields for adding or editing a task
     * In this case, it adds an limit field
     *
     * @param array $taskInfo Reference to the array containing the info used in the add/edit form
     * @param AbstractTask|NULL $task When editing, reference to the current task. NULL when adding.
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject Reference to the calling object (Scheduler's BE module)
     * @return array Array containing all the information pertaining to the additional fields
     */
    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        // Initialize extra field value
        if ( empty( $task->storagePid ) ) {
                $taskInfo['storagePid'] = 0;
        } else {
            // Otherwise set an empty value, as it will not be used anyway
            $taskInfo['storagePid'] = $task->storagePid;
        }
        // Write the code for the field
        $fieldID = 'storagePid';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[storagePid]" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo['storagePid']) . '" size="30">';
        $additionalFields = [];
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'Storage PID',
            'cshKey' => '_MOD_system_txschedulerM2',
            'cshLabel' => $fieldID
        ];
        return $additionalFields;
    }

    /**
     * This method checks any additional data that is relevant to the specific task
     * If the task class is not relevant, the method is expected to return TRUE
     *
     * @param array	 $submittedData Reference to the array containing the data submitted by the user
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject Reference to the calling object (Scheduler's BE module)
     * @return bool TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
     */
    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject)
    {
        $submittedData['storagePid'] = trim($submittedData['storagePid']);
        if (empty($submittedData['storagePid'])) {
//            $parentObject->addMessage($GLOBALS['LANG']->sL('Keine Storage PID angegeben!'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            $result = false;
        }  else {
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
    }
}
