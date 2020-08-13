<?php

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cardealer_newbees'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'cardealer_newbees',
    'FILE:EXT:cardealer/Configuration/FlexForms/Newbees.xml'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cardealer_random'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'cardealer_random',
    'FILE:EXT:cardealer/Configuration/FlexForms/Random.xml'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cardealer_selection'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'cardealer_selection',
    'FILE:EXT:cardealer/Configuration/FlexForms/Selection.xml'
);