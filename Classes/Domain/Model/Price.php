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
 * Price
 */
class Price extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * uid
     *
     * @var int
     */
    protected $uid = 0;

    /**
     * keyId
     *
     * @var string
     */
    protected $keyId = '';
    
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * @return int
     */
    public function getUid (): int
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid (int $uid): void
    {
        $this->uid = $uid;
    }


    
    /**
     * Returns the keyId
     *
     * @return string $keyId
     */
    public function getKeyId()
    {
        return $this->keyId;
    }
    
    /**
     * Sets the keyId
     *
     * @param string $keyId
     * @return void
     */
    public function setKeyId($keyId)
    {
        $this->keyId = $keyId;
    }
    
    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

}