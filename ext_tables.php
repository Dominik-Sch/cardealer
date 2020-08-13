<?php
/**
 * Copyright (c) 2018. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

//        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
//            'EXOTEC.Cardealer',
//            'Pi1',
//            'Cardealer - mobile.de API'
//        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'EXOTEC.Cardealer',
            'filter',
            'Cardealer - Filter Formular'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'EXOTEC.Cardealer',
            'list',
            'Cardealer - Fahrzeug Liste'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'EXOTEC.Cardealer',
            'show',
            'Cardealer - Details'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'EXOTEC.Cardealer',
            'newbees',
            'Cardealer - Neue Fahrzeuge (Slick Slider)'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'EXOTEC.Cardealer',
            'random',
            'Cardealer - Zufällige Fahrzeuge (Slick Slider)'
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'EXOTEC.Cardealer',
            'selection',
            'Cardealer - Selektierte Fahrzeuge (Slick Slider)'
        );
    }
);

$_EXTKEY = 'cardealer';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Cardealer - Mobile.de API');


$_EXTKEY = 'cardealer';
// register the tasks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\EXOTEC\Cardealer\Task\WriteXMLRefs::class] = array(
    'extension' => $_EXTKEY,
    'title' => '(Step 1) Import der XML Referenzen',
    'description' => 'Importiert die XML Referenzen',
    'additionalFields' => \EXOTEC\Cardealer\Task\WriteXMLRefsAdditionalFieldProvider::class
);

// register the tasks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\EXOTEC\Cardealer\Task\WriteModelsAndMakes::class] = array(
    'extension' => $_EXTKEY,
    'title' => '(Step 2) Import der Hersteller und Modelle',
    'description' => 'Importiert die Hersteller und Modelle',
    'additionalFields' => \EXOTEC\Cardealer\Task\WriteModelsAndMakesAdditionalFieldProvider::class
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\EXOTEC\Cardealer\Task\WriteCars::class] = array(
    'extension' => $_EXTKEY,
    'title' => '(Step 3) Import der Fahrzeuge',
    'description' => 'Importiert die Fahrzeuge',
    'additionalFields' => \EXOTEC\Cardealer\Task\WriteCarsAdditionalFieldProvider::class
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['EXOTEC\Cardealer\Task\CopyImages'] = array(
    'extension' => $_EXTKEY,
    'title' => '(Step 4) Import der Bilder',
    'description' => 'Importiert die Bilder',
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\EXOTEC\Cardealer\Task\CreateFileReferences::class] = array(
    'extension' => $_EXTKEY,
    'title' => '(Step 5) Erzeuge Image File References',
    'description' => '',
    'additionalFields' => \EXOTEC\Cardealer\Task\CreateFileReferencesAdditionalFieldProvider::class
);



$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\EXOTEC\Cardealer\Task\CreateSlugs::class] = array(
    'extension' => $_EXTKEY,
    'title' => '(Step 6) Slug Generator',
    'description' => 'Generiert die Speaking URL Slug\'s und aktualisiert die sites/config.yaml Datei',
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\EXOTEC\Cardealer\Task\ClearCaches::class] = array(
    'extension' => $_EXTKEY,
    'title' => '(Step 7) Lösche Cardealer Caches',
    'description' => 'Löscht die Cardealer Caches',
);
