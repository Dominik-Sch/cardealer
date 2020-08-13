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
class Car extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var string
     */
    protected $slug;

    /**
     * MWSt. ausweisbar
     *
     * @var int
     */
    protected $vatable = 0;

    /**
     * Firmenname
     *
     * @var string
     */
    protected $companyName = '';

    /**
     * Adresse
     *
     * @var string
     */
    protected $address = '';

    /**
     * PLZ
     *
     * @var int
     */
    protected $zip = 0;

    /**
     * Stadt
     *
     * @var string
     */
    protected $city = '';

    /**
     * Telefon
     *
     * @var string
     */
    protected $phone = '';

    /**
     * Email
     *
     * @var string
     */
    protected $email = '';

    /**
     * Koordinaten
     *
     * @var string
     */
    protected $coordinates = '';

    /**
     * HU
     *
     * @var int
     */
    protected $generalInspection = 0;

    /**
     * Leistung
     *
     * @var int
     */
    protected $power = '';

    /**
     * Anzahl Sitze
     *
     * @var int
     */
    protected $numSeats = 0;

    /**
     * Hubraum
     *
     * @var int
     */
    protected $cubicCapacity = '';

    /**
     * Anzahl Vorbesitzer
     *
     * @var int
     */
    protected $numberOwners = '';

    /**
     * Effizienzklasse
     *
     * @var string
     */
    protected $efficiencyClass = '';

    /**
     * Co2 Emmission
     *
     * @var string
     */
    protected $co2Emission = '';

    /**
     * Verbrauch innerorts
     *
     * @var string
     */
    protected $innerConsumption = '';

    /**
     * Verbrauch außerorts
     *
     * @var string
     */
    protected $outerConsumption = '';

    /**
     * Verbrauch kombiniert
     *
     * @var string
     */
    protected $combinedConsumption = '';

    /**
     * Anzahl Türen
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Doorcount>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $doorCount = null;

    /**
     * Schadstoffklasse
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Emissionclass>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $emissionClass = null;

    /**
     * Umweltplakette
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Emissionsticker>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $emissionSticker = null;

    /**
     * Farbe Innenausstattung
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Interiorcolor>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $interiorColor = null;

    /**
     * Typ Innenausstattung
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Interiortype>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $interiorType = null;

    /**
     * Farbe außen
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Exteriorcolor>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $exteriorColor = null;

    /**
     * Airbag
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Airbag>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $airbag = null;


    /**
     * Länderversion
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Countryversion>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $countryVersion = null;

    /**
     * Parkassistent
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Parkingassistant>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $parkingAssistant = null;

    /**
     * Unfallschaden
     *
     * @var int
     */
    protected $accidentdamaged = '';

    /**
     * Fahrtauglich
     *
     * @var int
     */
    protected $roadworthy = '';

    /**
     * beschaedigtes Fahrzeug
     *
     * @var int
     */
    protected $damageunrepaired = '';

    /**
     * Lieferdatum
     *
     * @var int
     */
    protected $deliveryDate = '';


    /**
     * Modelbeschreibung
     *
     * @var string
     */
    protected $modelDescription = '';

    /**
     * Standort
     *
     * @var string
     */
    protected $location = '';

    /**
     * Preis
     *
     * @var int
     */
    protected $price = 0;

    /**
     * Kilometerstand
     *
     * @var int
     */
    protected $mileage;

    /**
     * HSN
     *
     * @var string
     */
    protected $hsn = '';

    /**
     * TSN
     *
     * @var string
     */
    protected $tsn = '';

    /**
     * Schwacke
     *
     * @var string
     */
    protected $schwacke = '';

    /**
     * CreationDate
     *
     * @var int
     */
    protected $creationDate = null;

    /**
     * AddKey
     *
     * @var string
     */
    protected $addKey = '';

    /**
     * SellerInventoryKey
     *
     * @var string
     */
    protected $sellerInventoryKey = '';

    /**
     * Erstzulassung
     *
     * @var int
     */
    protected $firstRegistration = 0;

    /**
     * Anzahl Fotos
     *
     * @var int
     */
    protected $imageCount = 0;

    /**
     * images
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $images = null;

    /**
     * Fotos as CSV
     *
     * @var string
     */
    protected $imagesAsCsv = '';

    /**
     * mobile.de Foto URLs
     *
     * @var string
     */
    protected $imagesUrls = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Hersteller
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Make>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $make = null;

    /**
     * Modell
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Model>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $model = null;

    /**
     * Carclass
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Carclass>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $carclass = null;

    /**
     * Category
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Category>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $category = null;

    /**
     * Fuel
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Fuel>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $fuel = null;

    /**
     * Gearbox
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Gearbox>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $gearbox = null;

    /**
     * Climatisation
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Climatisation>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $climatisation = null;

    /**
     * Carcondition
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Carcondition>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $carcondition = null;

    /**
     * Usagetype
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Usagetype>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $usagetype = null;

    /**
     * Feature
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Feature>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $feature = null;


    /**
     * __construct
     */
    public function __construct ()
    {
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
    protected function initStorageObjects ()
    {
        $this->make = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->model = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->carclass = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->category = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->fuel = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->gearbox = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->climatisation = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->carcondition = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->usagetype = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->feature = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->airbag = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->countryVersion = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->doorCount = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->emissionClass = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->emissionSticker = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->exteriorColor = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->interiorColor = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->interiorType = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->parkingAssistant = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }


    /**
     * Returns the make
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Make> $make
     */
    public function getMake ()
    {
        return $this->make;
    }

    /**
     * Sets the make
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Make> $make
     * @return void
     */
    public function setMake (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $make)
    {
        $this->make = $make;
    }


    /**
     * Returns the model
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Model> $model
     */
    public function getModel ()
    {
        return $this->model;
    }

    /**
     * Sets the model
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\EXOTEC\Cardealer\Domain\Model\Model> $model
     * @return void
     */
    public function setModel (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $model)
    {
        $this->model = $model;
    }


    /**
     * @return string
     */
    public function getModelDescription ()
    {
        return $this->modelDescription;
    }

    /**
     * @param string $modelDescription
     */
    public function setModelDescription ($modelDescription)
    {
        $this->modelDescription = $modelDescription;
    }

    /**
     * @return string
     */
    public function getLocation (): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation (string $location): void
    {
        $this->location = $location;
    }



    /**
     * @return int
     */
    public function getPrice (): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice (int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getHsn (): string
    {
        return $this->hsn;
    }

    /**
     * @param string $hsn
     */
    public function setHsn (string $hsn): void
    {
        $this->hsn = $hsn;
    }

    /**
     * @return string
     */
    public function getTsn (): string
    {
        return $this->tsn;
    }

    /**
     * @param string $tsn
     */
    public function setTsn (string $tsn): void
    {
        $this->tsn = $tsn;
    }

    /**
     * @return string
     */
    public function getSchwacke (): string
    {
        return $this->schwacke;
    }

    /**
     * @param string $schwacke
     */
    public function setSchwacke (string $schwacke): void
    {
        $this->schwacke = $schwacke;
    }

    /**
     * @return int
     */
    public function getCreationDate (): int
    {
        return $this->creationDate;
    }

    /**
     * @param int $creationDate
     */
    public function setCreationDate (int $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getAddKey (): string
    {
        return $this->addKey;
    }

    /**
     * @param string $addKey
     */
    public function setAddKey (string $addKey): void
    {
        $this->addKey = $addKey;
    }

    /**
     * @return string
     */
    public function getSellerInventoryKey (): string
    {
        return $this->sellerInventoryKey;
    }

    /**
     * @param string $sellerInventoryKey
     */
    public function setSellerInventoryKey (string $sellerInventoryKey): void
    {
        $this->sellerInventoryKey = $sellerInventoryKey;
    }





    /**
     * @return int
     */
    public function getMileage (): int
    {
        return $this->mileage;
    }

    /**
     * @param int $mileage
     */
    public function setMileage (int $mileage): void
    {
        $this->mileage = $mileage;
    }

    /**
     * @return int
     */
    public function getFirstRegistration (): int
    {
        return $this->firstRegistration;
    }

    /**
     * @param int $firstRegistration
     */
    public function setFirstRegistration (int $firstRegistration): void
    {
        $this->firstRegistration = $firstRegistration;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getImages (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->images;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function setImages (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images): void
    {
        $this->images = $images;
    }


    /**
     * Adds a Image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image) {
        $this->images->attach($image);
    }



    /**
     * @return string
     */
    public function getImagesAsCsv (): string
    {
        return $this->imagesAsCsv;
    }

    /**
     * @param string $imagesAsCsv
     */
    public function setImagesAsCsv (string $imagesAsCsv): void
    {
        $this->imagesAsCsv = $imagesAsCsv;
    }

    /**
     * @return string
     */
    public function getImagesUrls (): string
    {
        return $this->imagesUrls;
    }

    /**
     * @param string $imagesUrls
     */
    public function setImagesUrls (string $imagesUrls): void
    {
        $this->imagesUrls = $imagesUrls;
    }





    /**
     * @return string
     */
    public function getDescription (): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription (string $description): void
    {
        $this->description = $description;
    }


    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCarclass (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->carclass;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $carclass
     */
    public function setCarclass (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $carclass): void
    {
        $this->carclass = $carclass;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategory (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->category;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $category
     */
    public function setCategory (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $category): void
    {
        $this->category = $category;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getFuel (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->fuel;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $fuel
     */
    public function setFuel (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $fuel): void
    {
        $this->fuel = $fuel;
    }


    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getGearbox (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->gearbox;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $gearbox
     */
    public function setGearbox (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $gearbox): void
    {
        $this->gearbox = $gearbox;
    }


    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getClimatisation (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->climatisation;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $climatisation
     */
    public function setClimatisation (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $climatisation): void
    {
        $this->climatisation = $climatisation;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCarcondition (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->carcondition;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $carcondition
     */
    public function setCarcondition (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $carcondition): void
    {
        $this->carcondition = $carcondition;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getUsagetype (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->usagetype;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $usagetype
     */
    public function setUsagetype (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $usagetype): void
    {
        $this->usagetype = $usagetype;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getFeature (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->feature;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $feature
     */
    public function setFeature (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $feature): void
    {
        $this->feature = $feature;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }



    /**
     * @return int
     */
    public function getVatable (): int
    {
        return $this->vatable;
    }

    /**
     * @param int $vatable
     */
    public function setVatable (int $vatable): void
    {
        $this->vatable = $vatable;
    }

    /**
     * @return string
     */
    public function getCompanyName (): string
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName (string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getAddress (): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress (string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return int
     */
    public function getZip (): int
    {
        return $this->zip;
    }

    /**
     * @param int $zip
     */
    public function setZip (int $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getCity (): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity (string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPhone (): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone (string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail (): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail (string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCoordinates (): string
    {
        return $this->coordinates;
    }

    /**
     * @param string $coordinates
     */
    public function setCoordinates (string $coordinates): void
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @return int
     */
    public function getGeneralInspection (): int
    {
        return $this->generalInspection;
    }

    /**
     * @param int $generalInspection
     */
    public function setGeneralInspection (int $generalInspection): void
    {
        $this->generalInspection = $generalInspection;
    }

    /**
     * @return int
     */
    public function getPower (): int
    {
        return $this->power;
    }

    /**
     * @param int $power
     */
    public function setPower (int $power): void
    {
        $this->power = $power;
    }

    /**
     * @return int
     */
    public function getNumSeats (): int
    {
        return $this->numSeats;
    }

    /**
     * @param int $numSeats
     */
    public function setNumSeats (int $numSeats): void
    {
        $this->numSeats = $numSeats;
    }

    /**
     * @return int
     */
    public function getCubicCapacity (): int
    {
        return $this->cubicCapacity;
    }

    /**
     * @param int $cubicCapacity
     */
    public function setCubicCapacity (int $cubicCapacity): void
    {
        $this->cubicCapacity = $cubicCapacity;
    }

    /**
     * @return int
     */
    public function getNumberOwners (): int
    {
        return $this->numberOwners;
    }

    /**
     * @param int $numberOwners
     */
    public function setNumberOwners (int $numberOwners): void
    {
        $this->numberOwners = $numberOwners;
    }

    /**
     * @return string
     */
    public function getEfficiencyClass (): string
    {
        return $this->efficiencyClass;
    }

    /**
     * @param string $efficiencyClass
     */
    public function setEfficiencyClass (string $efficiencyClass): void
    {
        $this->efficiencyClass = $efficiencyClass;
    }


    /**
     * @return string
     */
    public function getCo2Emission (): string
    {
        return $this->co2Emission;
    }

    /**
     * @param string $co2Emission
     */
    public function setCo2Emission (string $co2Emission): void
    {
        $this->co2Emission = $co2Emission;
    }

    /**
     * @return string
     */
    public function getInnerConsumption (): string
    {
        return $this->innerConsumption;
    }

    /**
     * @param string $innerConsumption
     */
    public function setInnerConsumption (string $innerConsumption): void
    {
        $this->innerConsumption = $innerConsumption;
    }

    /**
     * @return string
     */
    public function getOuterConsumption (): string
    {
        return $this->outerConsumption;
    }

    /**
     * @param string $outerConsumption
     */
    public function setOuterConsumption (string $outerConsumption): void
    {
        $this->outerConsumption = $outerConsumption;
    }

    /**
     * @return string
     */
    public function getCombinedConsumption (): string
    {
        return $this->combinedConsumption;
    }

    /**
     * @param string $combinedConsumption
     */
    public function setCombinedConsumption (string $combinedConsumption): void
    {
        $this->combinedConsumption = $combinedConsumption;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getDoorCount (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->doorCount;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $doorCount
     */
    public function setDoorCount (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $doorCount): void
    {
        $this->doorCount = $doorCount;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getEmissionClass (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->emissionClass;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $emissionClass
     */
    public function setEmissionClass (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $emissionClass): void
    {
        $this->emissionClass = $emissionClass;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getEmissionSticker (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->emissionSticker;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $emissionSticker
     */
    public function setEmissionSticker (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $emissionSticker): void
    {
        $this->emissionSticker = $emissionSticker;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getInteriorColor (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->interiorColor;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $interiorColor
     */
    public function setInteriorColor (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $interiorColor): void
    {
        $this->interiorColor = $interiorColor;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getInteriorType (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->interiorType;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $interiorType
     */
    public function setInteriorType (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $interiorType): void
    {
        $this->interiorType = $interiorType;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getExteriorColor (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->exteriorColor;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $exteriorColor
     */
    public function setExteriorColor (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $exteriorColor): void
    {
        $this->exteriorColor = $exteriorColor;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getAirbag (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->airbag;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $airbag
     */
    public function setAirbag (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $airbag): void
    {
        $this->airbag = $airbag;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCountryVersion (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->countryVersion;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $countryVersion
     */
    public function setCountryVersion (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $countryVersion): void
    {
        $this->countryVersion = $countryVersion;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getParkingAssistant (): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->parkingAssistant;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $parkingAssistant
     */
    public function setParkingAssistant (\TYPO3\CMS\Extbase\Persistence\ObjectStorage $parkingAssistant): void
    {
        $this->parkingAssistant = $parkingAssistant;
    }



    /**
     * @return int
     */
    public function getAccidentdamaged (): int
    {
        return $this->accidentdamaged;
    }

    /**
     * @param int $accidentdamaged
     */
    public function setAccidentdamaged (int $accidentdamaged): void
    {
        $this->accidentdamaged = $accidentdamaged;
    }

    /**
     * @return int
     */
    public function getRoadworthy (): int
    {
        return $this->roadworthy;
    }

    /**
     * @param int $roadworthy
     */
    public function setRoadworthy (int $roadworthy): void
    {
        $this->roadworthy = $roadworthy;
    }

    /**
     * @return int
     */
    public function getDamageunrepaired (): int
    {
        return $this->damageunrepaired;
    }

    /**
     * @param int $damageunrepaired
     */
    public function setDamageunrepaired (int $damageunrepaired): void
    {
        $this->damageunrepaired = $damageunrepaired;
    }






    /**
     * @return int
     */
    public function getDeliveryDate (): int
    {
        return $this->deliveryDate;
    }

    /**
     * @param int $deliveryDate
     */
    public function setDeliveryDate (int $deliveryDate): void
    {
        $this->deliveryDate = $deliveryDate;
    }

    /**
     * @return int
     */
    public function getImageCount (): int
    {
        return $this->imageCount;
    }

    /**
     * @param int $imageCount
     */
    public function setImageCount (int $imageCount): void
    {
        $this->imageCount = $imageCount;
    }






}
