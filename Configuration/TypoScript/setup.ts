
page.includeCSS {
    cardealer_basics = EXT:cardealer/Resources/Public/Css/tx-cardealer-basic.css
    cardealer_styles = EXT:cardealer/Resources/Public/Css/tx-cardealer-styles.css

    flex_slider.if.isTrue = {$plugin.tx_cardealer_pi1.settings.include_flexslider}
    flex_slider = EXT:cardealer/Resources/Public/Js/Plugins/flex-slider/jquery.flexslider.css

    slick.if.isTrue = {$plugin.tx_cardealer_pi1.settings.include_slick}
    slick = EXT:cardealer/Resources/Public/Js/Plugins/slick-1.6.0/slick/slick.css

    slicktheme.if.isTrue = {$plugin.tx_cardealer_pi1.settings.include_slick}
    slicktheme = EXT:cardealer/Resources/Public/Js/Plugins/slick-1.6.0/slick/slick-theme.css
}

page.includeJSFooter {
    flex_slider.if.isTrue = {$plugin.tx_cardealer_pi1.settings.include_flexslider}
    flex_slider = EXT:cardealer/Resources/Public/Js/Plugins/flex-slider/jquery.flexslider.js

    slick.if.isTrue = {$plugin.tx_cardealer_pi1.settings.include_slick}
    slick = EXT:cardealer/Resources/Public/Js/Plugins/slick-1.6.0/slick/slick.min.js

    cardealer = EXT:cardealer/Resources/Public/Js/tx-cardealer.js
}

tt_content.stdWrap.innerWrap.cObject = CASE
tt_content.stdWrap.innerWrap.cObject {
    key.field = layout
    98 = TEXT
    98.value = <div class="tx-cardealer-requestform tx-cardealer-form">|</div>
    99 = TEXT
    99.value = <div class="tx-cardealer-tipafriend tx-cardealer-form">|</div>
}


plugin.tx_cardealer_pi1 {
    lib {
        # calculator
        math = TEXT
        math {
            current = 1
            prioriCalc = 1
        }

        # powermail data
        carData = COA
        carData {
            addKey = USER
            addKey {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = addKey
            }
            make = USER
            make {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = make
            }
            model = USER
            model {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = model
            }
            price = USER
            price {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = price
            }
            mileage = USER
            mileage {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = mileage
            }
            location = USER
            location {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = location
            }
            email = USER
            email {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = email
            }
            category = USER
            category {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = category
            }
            carclass = USER
            carclass {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = carclass
            }
            carcondition = USER
            carcondition {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = carcondition
            }
            firstRegistration = USER
            firstRegistration {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = firstRegistration
            }
            modelDescription = USER
            modelDescription {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = modelDescription
            }
            link = USER
            link {
                userFunc = EXOTEC\Cardealer\UserFunc\PowermailData->main
                param = detailsPageLink
            }
        }

        # Powermail Form in Details
        powermail_infomail = COA
        powermail_infomail {
            1 = TEXT
            1.value (

Bitte nehmen Sie Kontakt zu mir auf!

Dieses Fahrzeug hat mein Interesse geweckt.
------------------------------------------

            )
            5 < plugin.tx_cardealer_pi1.lib.carData.make

            6 = TEXT
            6.value (

            )

            10 < plugin.tx_cardealer_pi1.lib.carData.modelDescription

            20 = TEXT
            20.value (

Inserat:
            )

            30 < plugin.tx_cardealer_pi1.lib.carData.addKey

            40 = TEXT
            40.value (

Preis:
            )

            50 < plugin.tx_cardealer_pi1.lib.carData.price

            60 = TEXT
            60.value (

Kilometerstand:
            )

            70 < plugin.tx_cardealer_pi1.lib.carData.mileage

            80 = TEXT
            80.value (
 km
Standort:
            )

            90 < plugin.tx_cardealer_pi1.lib.carData.location


            100 = TEXT
            100.value (

Standort Email:
            )

            110 < plugin.tx_cardealer_pi1.lib.carData.email

            120 = TEXT
            120.value (

Kategorie:
            )

            130 < plugin.tx_cardealer_pi1.lib.carData.category

            140 = TEXT
            140.value (

Klasse:
            )

            150 < plugin.tx_cardealer_pi1.lib.carData.carclass

            160 = TEXT
            160.value (

Erstzulassung:
            )

            170 < plugin.tx_cardealer_pi1.lib.carData.firstRegistration



        }

        # Powermail Form in Details
        powermail_tipafriend = COA
        powermail_tipafriend {
            10 = TEXT
            10.value (
Schau Dir dieses tolle Auto mal an:

            )
            20 < plugin.tx_cardealer_pi1.lib.carData.link
        }


    }
    view {
        templateRootPaths.0 = EXT:cardealer/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_cardealer_pi1.view.templateRootPath}
        partialRootPaths.0 = EXT:cardealer/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_cardealer_pi1.view.partialRootPath}
        layoutRootPaths.0 = EXT:cardealer/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_cardealer_pi1.view.layoutRootPath}

        pluginNamespace = tx_cardealer_pi1
    }
    persistence {
        storagePid = {$plugin.tx_cardealer_pi1.persistence.storagePid}
        #recursive = 1
    }
    features {
        skipDefaultArguments = 1
        # if set to 1, the enable fields are ignored in BE context
        ignoreAllEnableFieldsInBe = 0
        # Should be on by default, but can be disabled if all action in the plugin are uncached
        requireCHashArgumentForActionArguments = 0
    }
    mvc {
        callDefaultActionIfActionCantBeResolved = 1
    }
    settings {
        prices = {$plugin.tx_cardealer_pi1.settings.prices}
        firstRegistrations = {$plugin.tx_cardealer_pi1.settings.firstRegistrations}
        mileages = {$plugin.tx_cardealer_pi1.settings.mileages}
        replace = {$plugin.tx_cardealer_pi1.settings.replace}
        replaceBy = {$plugin.tx_cardealer_pi1.settings.replaceBy}
        ajax_detail_view = {$plugin.tx_cardealer_pi1.settings.ajax_detail_view}
        icons_color = {$plugin.tx_cardealer_pi1.settings.icons_color}
        pid {
            list = {$plugin.tx_cardealer_pi1.settings.pid.list}
            details = {$plugin.tx_cardealer_pi1.settings.pid.details}
        }
        uid {
            request_form = {$plugin.tx_cardealer_pi1.settings.uid.request_form}
            tipafriend_form = {$plugin.tx_cardealer_pi1.settings.uid.tipafriend_form}
        }
        # filterFields array(fieldname => Partial)
        filterFields {
            carclass = Checkboxes
            carcondition = Checkboxes
            usagetype = Checkboxes
            category = Checkboxes
            make = Checkboxes
            model = Checkboxes
            fuel = Checkboxes
            gearbox = Checkboxes
            location = Checkboxes
            climatisation = Checkboxes
            parkingAssistant = Checkboxes
            interiorType = Checkboxes
            interiorColor = Checkboxes
            exteriorColor = Checkboxes
            emissionSticker = Checkboxes
            emissionClass = Checkboxes
            feature = Checkboxes
            doorCount = Checkboxes
            countryVersion = Checkboxes
            price = Checkboxes
            firstRegistration = Checkboxes
            airbag = Checkboxes
        }
    }
}

plugin.tx_cardealer_list < plugin.tx_cardealer_pi1
plugin.tx_cardealer_show < plugin.tx_cardealer_pi1
plugin.tx_cardealer_filter < plugin.tx_cardealer_pi1
plugin.tx_cardealer_newbees < plugin.tx_cardealer_pi1
plugin.tx_cardealer_random < plugin.tx_cardealer_pi1
plugin.tx_cardealer_selection < plugin.tx_cardealer_pi1

plugin.tx_cardealer_pi1_ajax < plugin.tx_cardealer_pi1
plugin.tx_cardealer_pi1_ajax_details < plugin.tx_cardealer_pi1



print = PAGE
print {
    typeNum = 98

    includeCSS {
        file_print = EXT:cardealer/Resources/Public/Css/tx-cardealer-print.css
    }

    10 = USER
    10 {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        extensionName = Cardealer
        pluginName = Pi1
        vendorName = EXOTEC
        controller = Cardealer
        switchableControllerActions {
            Cardealer {
                1 = show
                2 = list
                3 = filter
            }
        }
    }
}





tx_cardealer_pi1_ajax = PAGE
tx_cardealer_pi1_ajax {
    features {
        skipDefaultArguments = 1
    }

    typeNum = 4711
    config {
        disableAllHeaderCode = 1
        xhtml_cleaning = 0
        admPanel = 0
        additionalHeaders = Content-type: text/plain
        no_cache = 1
    }

    10 = USER
    10 {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        extensionName = Cardealer
        pluginName = Pi1
        vendorName = EXOTEC
        controller = Cardealer
        switchableControllerActions {
            Cardealer {
                1 = show
                2 = list
                3 = filter
            }
        }
    }

    #    20 = RECORDS
    #    20 {
    #        source = {$plugin.tx_cardealer_pi1.settings.uid.request_form},{$plugin.tx_cardealer_pi1.settings.uid.tipafriend_form}
    #        dontCheckPid = 1
    #        tables = tt_content
    #    }
}

tx_cardealer_pi1_ajax_details = PAGE
tx_cardealer_pi1_ajax_details {
    typeNum = 1508
    config {
        disableAllHeaderCode = 1
        xhtml_cleaning = 0
        admPanel = 0
        additionalHeaders = Content-type: text/plain
        no_cache = 1
    }

    10 = CONTENT
    10 {
        table = tt_content
        select {
            pidInList = {$plugin.tx_cardealer_pi1.settings.pid.details}
            orderBy = sorting
        }
        renderObj = < tt_content
        slide = 0
        slide {
            collect = 0
            collectReverse = 0
            collectFuzzy = 0
        }
        wrap =
        stdWrap =
    }
}
