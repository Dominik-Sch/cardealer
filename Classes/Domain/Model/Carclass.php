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

namespace EXOTEC\Cardealer\Domain\Model;


/**
 * Car
 */
class Carclass extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Counter DUMMY Property to count entries
     *
     * @var int
     */
    protected $counter = 0;

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * keyId
     *
     * @var string
     */
    protected $keyId = '';

    /**
     * @return int
     */
    public function getCounter (): int
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     */
    public function setCounter (int $counter): void
    {
        $this->counter = $counter;
    }



    /**
     * @return string
     */
    public function getTitle (): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle (string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getKeyId (): string
    {
        return $this->keyId;
    }

    /**
     * @param string $keyId
     */
    public function setKeyId (string $keyId): void
    {
        $this->keyId = $keyId;
    }




}
