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
 * Make
 */
class Make extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Counter DUMMY Property to count entries
     *
     * @var int
     */
    protected $counter = 0;

    /**
     * Slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * Titel
     *
     * @var string
     */
    protected $title = '';

    /**
     * Modelle
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Model>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $models = null;


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
        $this->models = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

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
    public function getSlug ()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug ($slug)
    {
        $this->slug = $slug;
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

    /**
     * Adds a Model
     *
     * @param \EXOTEC\Cardealer\Domain\Model\Model $model
     * @return void
     */
    public function addModel(\EXOTEC\Cardealer\Domain\Model\Model $model)
    {
        $this->models->attach($model);
    }

    /**
     * Removes a Model
     *
     * @param \EXOTEC\Cardealer\Domain\Model\Model $modelToRemove The Model to be removed
     * @return void
     */
    public function removeModel(\EXOTEC\Cardealer\Domain\Model\Model $modelToRemove)
    {
        $this->models->detach($modelToRemove);
    }

    /**
     * Returns the models
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Model> $models
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Sets the models
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Model> $models
     * @return void
     */
    public function setModels(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $models)
    {
        $this->models = $models;
    }



}
