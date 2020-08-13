<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car',
        'label' => 'add_key',
        'label_alt' => 'model_description',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'description, airbag, images, images_as_csv, images_urls, creation_date, seller_inventory_key, add_key, location, hsn, tsn, schwacke, price, mileage, first_registration, model_description, make, model, carclass, category, fuel, gearbox, climatisation, carcondition, usagetype, feature',
        'iconfile' => 'EXT:cardealer/Resources/Public/Icons/tx_cardealer_domain_model_car.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden',
    ],
    'types' => array(
        '1' => array('showitem' =>
            '--div--; Allgemein, slug, location, add_key, hsn, tsn, schwacke, seller_inventory_key, creation_date,delivery_date,price, vatable, roadworthy, damageunrepaired, accidentdamaged, category, make, model, model_description, description,
						--div--; Fahrzeugzustand, number_owners, carclass,carcondition, usagetype, mileage, general_inspection, first_registration, country_version, 
						--div--; Bilder, image_count,images, images_as_csv, images_urls,  
						--div--; Kontaktdaten, company_name ,address, zip, city, phone, email, coordinates, 
						--div--; Ausstattung, feature,  interior_color, interior_type, exterior_color, airbag, parking_assistant, climatisation, num_seats, door_count,  
						--div--; Motor, fuel, gearbox, cubic_capacity,power,
						--div--; Umwelt, efficiency_class, co2_emission, inner_consumption, outer_consumption, combined_consumption,  emission_class, emission_sticker,   
						--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
    ),
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_cardealer_domain_model_car',
                'foreign_table_where' => 'AND tx_cardealer_domain_model_car.pid=###CURRENT_PID### AND tx_cardealer_domain_model_car.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
            ],
        ],

        'images' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 999,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],

        'images_as_csv' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.images_as_csv',
            'config' => [
                'type' => 'text',
                'enableRichtext' => false,
                'richtextConfiguration' => 'default',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],

        ],
        'images_urls' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.images_urls',
            'config' => [
                'type' => 'text',
                'enableRichtext' => false,
                'richtextConfiguration' => 'default',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],

        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => false,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],

        ],

        'location' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.location',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'image_count' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.image_count',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,integer'
            ],
        ],
        'price' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.price',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required,integer'
            ],
        ],
        'model_description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.model_description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'mileage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.mileage',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,integer'
            ],
        ],
        'hsn' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.hsn',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'tsn' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.tsn',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'schwacke' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.schwacke',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'add_key' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.add_key',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],

        'vatable' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.vatable',
            'config' => [
                'type' => 'check',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'company_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.company_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'zip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.zip',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,integer'
            ],
        ],
        'city' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.city',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'phone' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.phone',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'coordinates' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.coordinates',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'power' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.power',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,integer'
            ],
        ],
        'num_seats' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.num_seats',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,integer'
            ],
        ],
        'cubic_capacity' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.cubic_capacity',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,integer'
            ],
        ],
        'number_owners' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.number_owners',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,integer'
            ],
        ],
        'efficiency_class' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.efficiency_class',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'co2_emission' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.co2_emission',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'inner_consumption' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.inner_consumption',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'outer_consumption' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.outer_consumption',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'combined_consumption' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.combined_consumption',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'door_count' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.door_count',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_doorcount',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_doorcount.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'emission_class' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.emission_class',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_emissionclass',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_emissionclass.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'emission_sticker' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.emission_sticker',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_emissionsticker',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_emissionsticker.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'interior_color' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.interior_color',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_interiorcolor',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_interiorcolor.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'interior_type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.interior_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_interiortype',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_interiortype.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'exterior_color' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.exterior_color',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_exteriorcolor',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_exteriorcolor.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'airbag' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.airbag',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 3,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_airbag',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_airbag.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'country_version' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.country_version',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_countryversion',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_countryversion.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'parking_assistant' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.parking_assistant',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_parkingassistant',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_parkingassistant.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'accidentdamaged' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.accidentdamaged',
            'config' => [
                'type' => 'radio',
                'size' => 30,
                'eval' => 'trim',
                'items' => [
                    [ 'nein', '0' ],
                    [ 'ja', '1' ],
                    [ 'keine angabe', '999' ]
                ],
            ],
        ],
        'roadworthy' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.roadworthy',
            'config' => [
                'type' => 'radio',
                'size' => 30,
                'eval' => 'trim',
                'items' => [
                    [ 'nein', '0' ],
                    [ 'ja', '1' ],
                    [ 'keine angabe', '999' ]
                ],
            ],
        ],
        'damageunrepaired' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.damageunrepaired',
            'config' => [
                'type' => 'radio',
                'size' => 30,
                'eval' => 'trim',
                'items' => [
                    [ 'nein', '0' ],
                    [ 'ja', '1' ],
                    [ 'keine angabe', '999' ]
                ],
            ],
        ],


        'seller_inventory_key' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.seller_inventory_key',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'creation_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.creation_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime'
            ],
        ],
        'delivery_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.delivery_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime'
            ],
        ],
        'first_registration' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.first_registration',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime'
            ],
        ],
        'general_inspection' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.general_inspection',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime'
            ],
        ],
        'make' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.make',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_cardealer_domain_model_make',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 1,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],

        ],
        'model' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.model',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_cardealer_domain_model_model',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 1,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],

        ],
        'carclass' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.carclass',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_cardealer_domain_model_carclass',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 1,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],

        ],
        'category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_cardealer_domain_model_category',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 1,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],

        ],
        'fuel' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.fuel',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_fuel',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_fuel.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'gearbox' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.gearbox',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_gearbox',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_gearbox.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'climatisation' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.climatisation',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_climatisation',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_climatisation.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'carcondition' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.carcondition',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_carcondition',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_carcondition.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'usagetype' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.usagetype',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_usagetype',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_usagetype.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'feature' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:cardealer/Resources/Private/Language/locallang_db.xlf:tx_cardealer_domain_model_car.feature',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 999,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_cardealer_domain_model_feature',
                'foreign_table_where' => 'ORDER BY tx_cardealer_domain_model_feature.title',
                'autoSizeMax' => 30,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],

        'slug' => [
            'exclude' => true,
            'label' => 'URL Segment',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,alphanum_x,lower,unique',
            ],
            'config' => [
                'type' => 'slug',
//                'prependSlash' => true,
                'generatorOptions' => [
                    'fields' => ['model_description'],
//                    'prefixParentPageSlug' => true,
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
            ],
        ],

    ],
];
