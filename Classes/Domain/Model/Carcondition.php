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

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Alexander Weber <weber@exotec.de>, exotec - TYPO3 Services
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Carcondition
 */
class Carcondition extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

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