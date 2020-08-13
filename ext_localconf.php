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

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'EXOTEC.Cardealer',
            'Pi1',
            [
                'Cardealer' => 'filter, list, show, ajax, newbees, selection, random'
            ],
            // non-cacheable actions
            [
                'Cardealer' => 'filter, list, show, ajax, newbees, selection, random'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'EXOTEC.Cardealer',
            'filter',
            [
                'Cardealer' => 'filter, ajax'
            ],
            // non-cacheable actions
            [
                'Cardealer' => 'filter, ajax'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'EXOTEC.Cardealer',
            'list',
            [
                'Cardealer' => 'list, ajax'
            ],
            // non-cacheable actions
            [
                'Cardealer' => 'ajax'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'EXOTEC.Cardealer',
            'show',
            [
                'Cardealer' => 'show, ajax'
            ],
            // non-cacheable actions
            [
                'Cardealer' => 'ajax'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'EXOTEC.Cardealer',
            'newbees',
            [
                'Cardealer' => 'newbees'
            ],
            // non-cacheable actions
            [
                'Cardealer' => ''
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'EXOTEC.Cardealer',
            'random',
            [
                'Cardealer' => 'random'
            ],
            // non-cacheable actions
            [
                'Cardealer' => 'random'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'EXOTEC.Cardealer',
            'selection',
            [
                'Cardealer' => 'selection'
            ],
            // non-cacheable actions
            [
                'Cardealer' => ''
            ]
        );


		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'cardealer-plugin-cardealer',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:cardealer/Resources/Public/Icons/user_plugin_cardealer.svg']
        );

        // Custom Routing Aspects Mapper
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['IdentifierValueMapper'] = \EXOTEC\Cardealer\Routing\Aspect\IdentifierValueMapper::class;

        // register own cache
        if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cardealer_cache'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cardealer_cache'] = array();
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cardealer_cache']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cardealer_cache']['backend'] = 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cardealer_cache']['options']['compression'] = 1;
        }
		
    }
);
