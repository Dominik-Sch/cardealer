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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use EXOTEC\Cardealer\Utility\Helper;

class ClearCaches extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

    public function execute() {

        // clean cf_cardealer_cache
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('cf_cardealer_cache')
            ->truncate('cf_cardealer_cache');

        // clean cf_cardealer_cache_tags
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('cf_cardealer_cache_tags')
            ->truncate('cf_cardealer_cache_tags');


        return true;

    }

}
