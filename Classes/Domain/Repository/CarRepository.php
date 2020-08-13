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

namespace EXOTEC\Cardealer\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * The repository for the Cars
 */
class CarRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * library
     *
     * @var \EXOTEC\Cardealer\Utilities\CardealerLibrary
     * */
    protected $library = NULL;

    public function injectLibrary (
        \EXOTEC\Cardealer\Utilities\CardealerLibrary $library)
    {
        $this->library = $library;
    }

    /**
     * @param null $args
     * @param int $limit
     * @param string $sortOrder
     * @param string $sortBy
     * @param int $page
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function filterAndSortBy ($args, $limit=10, $sortOrder='ASC', $sortBy='price', $page=1)
    {

        if ($sortOrder == null) $sortOrder = 'ASC';
        if ($sortBy == null) $sortBy = 'price';

        $query = $this->createQuery();
        $limit = (int)$limit;

        // initial constraint
        $constraint[] = $query->greaterThan('uid', 0);
        // count by filter arguments
        if($args['filter']) {
            foreach ($args['filter'] as $prop => $value) {
                switch ($prop) {
                    case 'price':
                        if ($value[0] > 0) {
                            $constraint[] = $query->logicalOr(
                                $query->lessThanOrEqual('price', $value)
                            );
                        }
                        break;

                    case 'mileage':
                        if ($value[0] > 0) {
                            $constraint[] = $query->logicalOr(
                                $query->lessThanOrEqual('mileage', $value)
                            );
                        }
                        break;

                    case 'location':
                        if ($value[0]) {
                            foreach ($value as $item) {
                                $constraint1[] = $query->logicalOr(
                                    $query->like('location', $item)
                                );
                            }
                            $constraint[] = $query->logicalOr($constraint1);
                        }
                        break;

                    case 'feature':
                        if ($value[0] > 0) {
                            $constraint[] = $query->contains('feature', $value[0]);
                        }
                        break;

                    case 'firstRegistration':
                        if ($value[0]) {
                            foreach ($value as $uid) {
                                $date = new \DateTime();
                                $date->setDate($uid, 0, 0);
                                $constraint[] = $query->greaterThanOrEqual('firstRegistration', $date->getTimestamp());
                            }
                        }
                        break;

                    default:
                        if ($value[0] > 0) {
                            $constraint[] = $query->logicalAnd(
                                $query->in($prop, $value)
                            );
                        }

                }
            }
        }

        $query->matching(
            $query->logicalAnd($constraint)
        );

        // ordering
        list($sortBy, $sortOrder) = $this->getOrderings($sortBy, $sortOrder);
        $query->setOrderings([$sortBy => $sortOrder]);

        // first count entries
        $count = $query->count();

        // set limit
        $query->setLimit($limit);

        // reset paginator page if needed
        // (eg. you start a search on page 6, but the actual searchresult have only 15 entries and we have max 2 pages in paginator)
        $page = $this->library->pageValue($limit, $page, $count);

        // set offSet
        $offset = ($page-1) * $limit;
        if ($offset < 0) {
            $offset = 0;
        }
        $query->setOffset($offset);

        $entries = $query->execute();

        // output array
        $out = array(
            'entries' => $entries,
            'count' => $count,
            'offSet' => $offSet,
            'limit' => $limit,
            'page' => $page
        );

        return $out;
    }


    /**
     * @param array $filterArr
     * @param string $property
     * @param int $uid
     * @return int
     */
    public function countCarsByFilter (
        $filterArr = array(),
        $property = '',
        $uid = 0
    ) {

        $query = $this->createQuery();

        // initial contraints
        if ($property == 'price') {
            $constraint[] = $query->lessThanOrEqual('price', $uid);

        } elseif ($property == 'mileage') {
            $constraint[] = $query->lessThanOrEqual('mileage', $uid);

        } elseif ($property == 'location') {
            $constraint[] = $query->like('location', $uid);

        } elseif ($property == 'firstRegistration') {
            $date = new \DateTime();
            $date->setDate($uid, 0, 0);
            $constraint[] = $query->greaterThanOrEqual('firstRegistration', $date->getTimestamp());

        } else {
            $constraint[] = $query->contains($property, $uid);
        }

        // count by filter arguments
        if($filterArr) {
            foreach ($filterArr as $prop => $itemArr) {
                switch ($prop) {
                    case 'price':
                        if ($itemArr[0] > 0 && $property != $prop) {
                            $constraint[] = $query->logicalAnd(
                                $query->lessThanOrEqual('price', $itemArr)
                            );
                        }
                        break;

                    case 'mileage':
                        if ($itemArr[0] > 0 && $property != $prop) {
                            $constraint[] = $query->logicalAnd(
                                $query->lessThanOrEqual('mileage', $itemArr)
                            );
                        }
                        break;

                    case 'location':
                        if ($itemArr[0] && $property != $prop) {
                            foreach ($itemArr as $item) {
                                $constraint1[] = $query->logicalOr(
                                    $query->like('location', $item)
                                );
                            }
                            $constraint[] = $query->logicalOr($constraint1);
                        }
                        break;

                    case 'feature':
                        if ($itemArr[0] > 0) {
                            $constraint[] = $query->contains('feature', $itemArr[0]);
                        }
                        break;

                    case 'firstRegistration':
                        if ($itemArr[0] > 0 && $property != $prop) {
//                            DebuggerUtility::var_dump($filterArr['firstRegistration'][0]);
//                            exit;
                            $constraint[] = $query->greaterThanOrEqual('firstRegistration', $filterArr['firstRegistration'][0]);
//                            foreach ($filter['firstRegistration'] as $uid) {
//                                $date = new \DateTime();
//                                $date->setDate($uid, 0, 0);
//                                $constraint[] = $query->greaterThanOrEqual('firstRegistration', $date->getTimestamp());
//                            }
                        }
                        break;

                    default:
                        if ($itemArr[0] > 0 && $property != $prop) {
                            $constraint[] = $query->logicalAnd(
                                $query->in($prop, $itemArr)
                            );
                        }

                }
            }
        }

        $query->matching(
            $query->logicalAnd($constraint)
        );

        $result = $query->execute(true);
        return count($result);
    }


    /**
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     */
    protected function getOrderings ($sortBy, $sortOrder): array
    {

        if ($sortOrder == 'DESC') {
            $sortOrder = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
        } else {
            $sortOrder = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
        }

        return array($sortBy, $sortOrder);
    }



    public function findByUidAsArray ($uid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
        $statement = $queryBuilder
            ->select('*')
            ->from('tx_cardealer_domain_model_car')
            ->where('uid = '.$uid)
            ->execute();
        return $statement->fetchAll();
    }

    public function findByModelDescription ($value)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
        $statement = $queryBuilder
            ->select('uid')
            ->from('tx_cardealer_domain_model_car')
            ->where("model_description LIKE '".$value."' ")
            ->execute();
        return $statement->fetchAll();
    }


    /**
     * @param $uids
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByUids($uids) {
        $uidArray = explode(",", $uids);
        $query = $this->createQuery();
        foreach ($uidArray as $key => $value) {
            $constraints[] =  $query->equals('uid', $value);
        }
        return $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $constraints
                )
            )
        )->execute();
    }


    /**
     * @param $tstamp
     * @param $limit
     * @return array
     */
    public function findNewbees($tstamp, $limit) {
        /** @var QueryInterface# $query */
        $query = $this->createQuery();
        $constraint[] = $query->greaterThanOrEqual('creation_date', $tstamp);
        $query->matching(
            $query->logicalAnd($constraint)
        );
        $query->setLimit($limit);
        return $query->execute();
    }


    /**
     * @param int $limit
     * @return array
     */
    public function findRandom($limit) {
        $query = $this->createQuery();
        $cars_array = $query->execute()->toArray();
        shuffle($cars_array);
        if(intval($limit)>0){
            $cars = array_slice($cars_array,0,intval($limit));
        }else{
            $cars = $cars_array;
        }
        return $cars;
    }

    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAll()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()
            ->setRespectStoragePage(false)
            ->setRespectSysLanguage(false);
        return $query->execute();
    }


//    public function findAllFast ($field)
//    {
//        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cardealer_domain_model_car');
//        $statement = $queryBuilder
//            ->select($field)
//            ->from('tx_cardealer_domain_model_car')
//            ->execute();
//        return $statement->fetchAll();
//    }

// todo: getUniquevaluesFor would be nice, but is to slow :-(
//    /**
//     * Get unique data
//     * @var string $table
//     * @return array
//     */
//    public function getUniquevaluesFor ($field)
//    {
//
//        $values = $this->findAllFast($field);
//        /** @var \EXOTEC\Cardealer\Domain\Model\Car $car */
//        foreach ($values as $value) {
//            switch ($field) {
//                case 'first_registration':
//                    $val = date("Y", $value['first_registration']);
//                    $vals[] = $val;
//                    asort($vals);
//                break;
//                case 'price':
//                    $vals[] = $value['price'];
//                    arsort($vals, SORT_NATURAL | SORT_FLAG_CASE);
//                break;
//            }
//        }
//
//        $uniqueValues = array_unique($vals);
//        $values = array_reverse($uniqueValues, true);
//
//        return $values;
//    }


}
