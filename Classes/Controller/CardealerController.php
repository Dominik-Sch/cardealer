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

namespace EXOTEC\Cardealer\Controller;

/***
 *
 * This file is part of the "TYPO3 - mobile.de API" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Alexander Weber <weber@exotec.de>, exotec
 *
 ***/

use TYPO3\CMS\Core\Messaging\FlashMessage;
use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CardealerController
 */
class CardealerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * library
     *
     * @var \EXOTEC\Cardealer\Utilities\CardealerLibrary
     * */
    protected $library = NULL;

    /**
     * carRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\CarRepository
     * */
    protected $carRepository = null;

    /**
     * cacheInstance
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    protected $cacheInstance = null;



    /**
     * @return void
     */
    public function initializeAction ()
    {
        // get the actual cache
        $this->cacheInstance = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('cardealer_cache');
    }

    public function injectCarRepository (\EXOTEC\Cardealer\Domain\Repository\CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function injectLibrary (\EXOTEC\Cardealer\Utilities\CardealerLibrary $library)
    {
        $this->library = $library;
    }


    /**
     *  NewbeesAction
     */
    public function newbeesAction ()
    {
        $_GP = GeneralUtility::_GP('tx_cardealer_pi1');
        $args = $this->getDefaultArguments($_GP);

        // remove identifier from POST/GET
        unset($args['identifier']);
        $args['limit'][0] = 10;
        $args['action'] = 'show';
        // sorted arguments are important!!!
        ksort($args);

        // create unique cache identifier
        $identifier = sha1('pid_' . (string)$GLOBALS['TSFE']->id . \GuzzleHttp\json_encode($args));

        $timeAgo = strtotime("-".(int)$this->settings['time_ago']." days");
        $limit = (int)$this->settings['limit'];
        $cars = $this->carRepository->findNewbees($timeAgo, $limit);

        // set fluid vars
        $variables = array(
            'cars' => $cars,
            'identifier' => $identifier,
        );

        // render the view
        $content = $this->getTemplateHtml('Cardealer', 'Newbees', $variables);
        return $content;
    }


    /**
     *  SelectionAction
     */
    public function selectionAction ()
    {
        $_GP = GeneralUtility::_GP('tx_cardealer_pi1');
        $args = $this->getDefaultArguments($_GP);

        // remove identifier from POST/GET
        unset($args['identifier']);
        $args['limit'][0] = 10;
        $args['action'] = 'show';
        // sorted arguments are important!!!
        ksort($args);

        // create unique cache identifier
        $identifier = sha1('pid_' . (string)$GLOBALS['TSFE']->id . \GuzzleHttp\json_encode($args));

        $uids = $this->settings['uids'];
        $cars = $this->carRepository->findByUids($uids);

        // set fluid vars
        $variables = array(
            'cars' => $cars,
            'identifier' => $identifier,
        );

        // render the view
        $content = $this->getTemplateHtml('Cardealer', 'Selection', $variables);
        return $content;
    }


    /**
     *  RandomAction
     */
    public function randomAction ()
    {
        $_GP = GeneralUtility::_GP('tx_cardealer_pi1');
        $args = $this->getDefaultArguments($_GP);

        // remove identifier from POST/GET
        unset($args['identifier']);
        $args['limit'][0] = 10;
        $args['action'] = 'show';
        // sorted arguments are important!!!
        ksort($args);

        // create unique cache identifier
        $identifier = sha1('pid_' . (string)$GLOBALS['TSFE']->id . \GuzzleHttp\json_encode($args));

        $limit = (int)$this->settings['limit'];
        $cars = $this->carRepository->findRandom($limit);

        // set fluid vars
        $variables = array(
            'cars' => $cars,
            'identifier' => $identifier,
        );

        // render the view
        $content = $this->getTemplateHtml('Cardealer', 'Random', $variables);
        return $content;
    }


    /**
     *  AjaxAction
     */
    public function ajaxAction ()
    {

    }


    /**
     * The Details
     *
     * @param \EXOTEC\Cardealer\Domain\Model\Car $car
     * @return string
     */
    public function showAction (\EXOTEC\Cardealer\Domain\Model\Car $car)
    {
        // POST/GET Params
        $_GP = GeneralUtility::_GP('tx_cardealer_pi1');

        // prepare args for caching
        // get list cache data to build clean backlink to previous cached listpage
        if($_GP['identifier']) {
            $listArgs = $this->cacheInstance->get($_GP['identifier']);
            $args = $listArgs['args'];
            $args['format'] = $_GP['format'];
        } else {
            // if no list args identifier available, set needed defaults
            $args['action'] = 'list';
            $args['controller'] = 'Standard';
            $args['sortOrder'][0] = 'ASC';
            $args['limit'][0] = 10;
            $args['sortBy'] = 'price';
            $args['page'] = '1';
            $args['format'] = $_GP['format'];
        }

        // sorted arguments are important!!!
        if($args) {
            ksort($args);
        }

        // create an unique cache identifier
        $identifier = 'show_' . sha1($car->getUid().\GuzzleHttp\json_encode($args));

        // get cache for the identifier
        $actCache = $this->cacheInstance->get($identifier);

        // if no cache available, set fluid vars, build the details view and save into cache
        if ($actCache === false) {

            // set fluid vars
            $variables = array(
                'car' => $car,
                'args' => $args,
                'identifier' => $_GP['identifier'],
                'settings' => $this->settings,
            );

            // render view
            $details = $this->getTemplateHtml('Cardealer', 'Show', $variables);

            // add to cache
            $lifetime = strtotime('+1 day', time());
            $this->cacheInstance->set($identifier, $details, array('cardealer_show'), $lifetime);

        } else {
            // get view from cache
            $details = $actCache;

        }

        return $details;
    }


    /**
     * The Resultslist
     *
     * @return string
     */
    public function listAction ()
    {

        // POST/GET Params
        $_GP = GeneralUtility::_GP('tx_cardealer_pi1');
        $args = $this->getDefaultArguments($_GP);

        // if an cache identifier in url available
        if($_GP['identifier']) {
            $listArgs = $this->cacheInstance->get($_GP['identifier']);
            $args['filter'] = $listArgs['args']['filter'];
        }

        // prepare args for caching
        // make filter array entries unique
        if($args['filter']) {
            foreach ($args['filter'] as $key => $value) {
                if($value) {
                    $args['filter'][$key]= array_unique($value);
                }

            }
        }

        // remove identifier from POST/GET
        unset($args['identifier']);
        // sorted arguments are important!!!
        ksort($args);

        // create unique cache identifier
        $identifier = sha1('pid_' . (string)$GLOBALS['TSFE']->id . \GuzzleHttp\json_encode($args));

        // get cache
        $cache = $this->cacheInstance->get($identifier);
        // if no cache available, get data, build resultsList and save the resultsList into the cache
        if ($cache === false) {

            // render list content
            $list = $this->listContentAction($args, $identifier);

            // prepare cache data
            $lifetime = strtotime('+1 day', time());
            $list = array(
                'args' => $args,
                'html' => $list
            );
            $this->cacheInstance->set($identifier, $list, array('cardealer_list'), $lifetime);

        } else {
            // get list from cache
            $list = $this->cacheInstance->get($identifier);
        }
        return $list['html'];
    }



    /**
     * @param array $args
     * @param string $identifier
     * @return string
     */
    protected function listContentAction ($args, $identifier): string
    {
        // get results
        $cars = $this->carRepository->filterAndSortBy($args, $args['limit'][0], $args['sortOrder'][0], $args['sortBy'],
            $args['page']);

        // reset paginator page if needed
        // (eg. you start a search on page 6, but the actual searchresult have only 15 entries and we have max 2 pages in paginator)
        $args['page'] = $cars['page'];

        // paginator
        $paginator = $this->library->getPaginator($cars, $args);

        // set fluid vars
        $variables = array(
            'args' => $args,
            'cars' => $cars,
            'settings' => $this->settings,
            'identifier' => $identifier,
            'paginator' => $paginator,
        );

        // render the list view html
        $content = $this->getTemplateHtml('Cardealer', 'List', $variables);
        return $content;
    }



    /**
     * The Searchform
     *
     * @return string
     */
    public function filterAction ()
    {

        $template = GeneralUtility::trimExplode('/', $this->settings['filter']['template']);
        $templateName = GeneralUtility::trimExplode('.',end($template));


        // POST/GET Params // getArguments() do not work because it returns cached form values
        $_GP = GeneralUtility::_GP('tx_cardealer_pi1');
        $args = $this->getDefaultArguments($_GP);

        // if an cache identifier in url available (paginator or backlink)
        if($_GP['identifier']) {
            $listArgs = $this->cacheInstance->get($_GP['identifier']);
            $args['filter'] = $listArgs['args']['filter'];
        }

        // prepare args for caching
        // make filter array entries unique
        #DebuggerUtility::var_dump($args['filter']);
        if($args['filter']) {
            foreach ($args['filter'] as $key => $value) {
                if($value) {
                    $args['filter'][$key]= array_unique($value);
                }

            }
        }
        // sorted arguments are important!!!
        unset($args['identifier']);
        ksort($args);

        // create an unique cache identifier
        $identifier = 'filter_' . sha1('pid_' . (string)$GLOBALS['TSFE']->id . \GuzzleHttp\json_encode($args));

        // get cache for the identifier
        $cache = $this->cacheInstance->get($identifier);

        // if no cache available, get data from mysql, build filterForm and save the form into the cache
        if ($cache === false) {

            // render the filter view content
            $filterForm = $this->filterContentAction($args,$templateName[0]);

            // add the whole searchform to cache
            $lifetime = strtotime('+1 day', time());
            $this->cacheInstance->set($identifier, $filterForm, array('cardealer_filter'), $lifetime);

        } else {
            // get searchform from cache
            $filterForm = $this->cacheInstance->get($identifier);
        }

        return $filterForm;
    }


    /**
     * @param array $args
     * @param string $templateFile
     * @return string
     */
    protected function filterContentAction ($args,$templateName="Filter"): string
    {

        if(!$templateName) {
            $templateName = 'Filter';
        }

        $cars = $this->carRepository->filterAndSortBy($args);
        $args['page'] = $page = $this->library->pageValue($args['limit'][0], $args['page'], $cars['count']);

        // build fluid vars
        $fieldVars = $this->library->buildFilterFluidVars($args, $this->settings);
        $variables = array(
            'args' => $args,
            'settings' => $this->settings,
            'fields' => $fieldVars,
            'count' => $cars['count']
        );

        // render the searchform
        $filterForm = $this->getTemplateHtml('Cardealer', $templateName, $variables);
        return $filterForm;
    }


    /**
     * @param $controllerName
     * @param $templateName
     * @param array $variables
     * @return string
     */
    public function getTemplateHtml ($controllerName, $templateName, array $variables = array())
    {
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $tempView */
        $tempView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');

        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        $layoutRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(end($extbaseFrameworkConfiguration['view']['layoutRootPaths']));
        $tempView->setLayoutRootPaths(array($layoutRootPath));

        $partialRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(end($extbaseFrameworkConfiguration['view']['partialRootPaths']));
        $tempView->setPartialRootPaths(array($partialRootPath));



        $templateRootPath = GeneralUtility::getFileAbsFileName(end($extbaseFrameworkConfiguration['view']['templateRootPaths']));
        $templatePathAndFilename = $templateRootPath . $controllerName . '/' . $templateName . '.html';

        if( !file_exists($templatePathAndFilename) ) {
            $templateRootPath = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPaths'][0]);
            $templatePathAndFilename = $templateRootPath . $controllerName . '/' . $templateName . '.html';
        }

        // Print Version
        if(GeneralUtility::_GP('type')=='98') {
            $templatePathAndFilename = $templateRootPath . $controllerName . '/' . $templateName . '.print';
        }

        $tempView->setTemplatePathAndFilename($templatePathAndFilename);
        $tempView->assignMultiple($variables);

        // this makes the <f:translation tags working
        $extensionName = $this->request->getControllerExtensionName();
        $tempView->getRequest()->setControllerExtensionName($extensionName);

        $tempHtml = $tempView->render();

        return $tempHtml;
    }


    /**
     * @return mixed
     */
    protected function getDefaultArguments ($args)
    {

        $args['action'] = 'list';
        $args['controller'] = 'Standard';

        if (!$args['page']) {
            $args['page'] = '1';
        }

        if(!$args['sortOrder'][0]){
            $args['sortOrder'][0] = 'ASC';
        }

        if(!$args['sortBy']){
            $args['sortBy'] = 'price';
        }

        if(!$args['limit'][0]) {
            $args['limit'][0] = '10';
        }

        if (!$args['filter']) {
            foreach ($this->settings['filterFields'] as $property => $value) {
                $args['filter'][$property][0] = '0';
            }
        }

        return $args;
    }




}
