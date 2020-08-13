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
 * Model
 */
class Model extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Just an <option> TaskLibrary Property
     *
     * @var int
     */
    protected $counter = 0;

    /**
     * Just an <option> TaskLibrary Property
     *
     * @var string
     */
    protected $selected = '';

    /**
     * Just an <option> TaskLibrary Property
     *
     * @var string
     */
    protected $disabled = '';

    /**
     * Uid
     *
     * @var int
     */
    protected $uid = 0;

    /**
     * Hersteller
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Make>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $make = null;


    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->make = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the make
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Make> $make
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Sets the make
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Make> $make
     * @return void
     */
    public function setMake(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $make)
    {
        $this->make = $make;
    }


    /**
     * Titel
     *
     * @var string
     */
    protected $title = '';

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
    public function getSelected (): string
    {
        return $this->selected;
    }

    /**
     * @param string $selected
     */
    public function setSelected (string $selected): void
    {
        $this->selected = $selected;
    }

    /**
     * @return string
     */
    public function getDisabled (): string
    {
        return $this->disabled;
    }

    /**
     * @param string $disabled
     */
    public function setDisabled (string $disabled): void
    {
        $this->disabled = $disabled;
    }





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
