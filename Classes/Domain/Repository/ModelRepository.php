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

use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;


/**
 * The repository for Models
 */
class ModelRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * makeRepository
     *
     * @var \EXOTEC\Cardealer\Domain\Repository\MakeRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $makeRepository = null;


    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    ];


    /**
     * @param array $uids
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByMakes ($uids = array())
    {
        $query = $this->createQuery();
        $constraints[] = $query->logicalOr(
            $query->in('make', $uids)
        );

        $query->matching($query->logicalAnd($constraints));
        return $query->execute();
    }


}
