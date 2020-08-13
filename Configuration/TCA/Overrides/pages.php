<?php

/*
 * This file is part of the package bk2k/bootstrap-package.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

/***************
 * Temporary variables
 */
$extensionKey = 'cardealer';

/***************
 * Register PageTS
 */



// Powermail CSS Classes
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/pageTSconfig.txt',
    'Cardealer: Layouts for Powermail Forms in Details View'
);


