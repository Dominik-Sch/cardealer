<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cardealer".
 *
 * Auto generated 10-08-2020 13:34
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Cardealer',
  'description' => 'TYPO3 mobile.de API including import tasks for multiple locations.',
  'category' => 'plugin',
  'author' => 'Alexander Weber',
  'author_email' => 'weber@exotec.de',
  'author_company' => 'www.exotec.de | Web-based business applications',
  'state' => 'stable',
  'clearCacheOnLoad' => 0,
  'version' => '10.4.2',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '10.4.0-10.4.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
      'scheduler' => '10.4.4-10.4.99',
      'powermail' => '8.1.1-8.1.99',
    ),
  ),
  'uploadfolder' => false,
  'clearcacheonload' => false,
);

