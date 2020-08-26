// initial stuff
$(function() {
    setCheckboxSelectLabel();
    setPowermailCssClasses();

    // setting intital history state on list plugin page
    if ( $('.pageUrl').length > 0 ) {
        initHistoryUrl = $('.pageUrl').attr('href');
        history.pushState({url: initHistoryUrl}, 'Default list state', initHistoryUrl);
        // $('.reset').trigger('click');
    }

    $('.image-slide-container').click(function () {
        $('.detail-image-big img').attr('src', $(this).data('image'));
    })

});

// click backlink in popup
$(document).on("click", ".tx-cardealer-show .backlink", function(e){
    e.preventDefault();
    closeDetails();
    return false;
});


// change form quicksearch
$(document).on("change", ".quicksearch .ajax-filter", function(e){
    var data = $('.tx-cardealer-filter form').serialize();
    doAjax(data, 'filter', window.location);

});

// change form and start search
$(document).on("change", ".default-search .ajax-filter", function(e){
    var data = $('.tx-cardealer-filter form').serialize();
    var filterUrl = $('.filterUrl').attr('href');
    var url = $('.pageUrl').attr('href');
    // console.log(filterUrl);
    // console.log(url);
    doAjax(data, 'list', filterUrl);
    doAjax(data, 'filter', filterUrl);
});

// checkbox toggle and button label
$(document).on("change", ".tx-cardealer-filter .input", function(){
    toggleCheckedAll(this);
    setCheckboxSelectLabel();
});

// toggle: for + and -
$(document).on("click", ".toggleNext span", function(){
    if( $(this).hasClass('open') ) {
        $(this).hide();
        $(this).next('span.close').show();
    } else {
        $(this).addClass('close');
        $(this).hide();
        $(this).prev('span.open').show();
    }
    $(this).closest('.item').find('.toggleContent').slideToggle();
});

// toggle: filter button in responsive view
$(document).on("click", ".toggle-filter", function(e){
    e.preventDefault();
    $('.tx-cardealer-filter form').toggleClass('d-none','');
});

// toggle: features checkboxes
$(document).on("click", ".moreOptions", function(e){
    e.preventDefault();
    $(this).next('.toggleContent').slideToggle('fast');
});

// toggle: dropdowns in searchform
$(document).on("click", ".default-search .toggle-next", function(e){
    e.preventDefault();
    $(this).next('.dropdown').slideToggle('fast');
});

$(document).on("click", ".quicksearch .toggle-next", function(e){
    e.preventDefault();
    var menu = $(".tx-cardealer-filter .dropdown");
    if( $(this).hasClass('open') ) {
        $('.toggle-next').removeClass('open');
        menu.hide();
    } else {
        $('.toggle-next').removeClass('open');
        $(this).addClass('open');
        $(this).next('.dropdown').slideDown();
    }
});

// close dropdown if click somewhere outside
$(document).on("mouseup", function(e){
    var menu = $(".quicksearch .dropdown");
    if (!menu.is(e.target) // if the target of the click isn't the container...
        && menu.has(e.target).length === 0) // ... nor a descendant of the container
    {
        menu.slideUp(300);
    }
});







// scroll to top clicking the bottom paginator
$(document).on("click", ".paginator-bottom a", function(e){
    $('body,html').animate({
        scrollTop: $('.tx-cardealer-list').offset().top-170
    }, 500);
    return false;
});







// open the detail forms
$(document).on("click", ".showForm", function(e){
    e.preventDefault();
    var formDiv = $(this).attr('id');
    $('.'+formDiv).show().addClass('visible');
    $('body').addClass('no-scroll');
    $('.'+formDiv).append('<a href="#" class="closeBtn"><span class="material-icon"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><path fill="#ca1a18" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg></span></a>');
});

// close detail form
$(document).on("click", ".closeBtn, .powermail_create", function(e){
    e.preventDefault();
    closeLayer('.tx-cardealer-form');
});






$(document).on("click", ".ajaxLink", function(e){
    var url = $( this ).attr('href');
    var urlHistory = $('.filterUrl').attr('href');
    // set history state
    if ( $(this).hasClass('history') ) {
        e.preventDefault();
        // alert(urlHistory);
        history.pushState({url:url}, null, url);
        // alert(history.state.url);
    }

    if($(this).hasClass('paging') && $(this).hasClass('current')) {
        return;
    }

    if( $(this).hasClass('paging') ) {
        e.preventDefault();
        getData(url, 'list');
    }

    if( $(this).hasClass("reset") ) {
        e.preventDefault();
        filterUrl = $('.filterUrl').attr('href');
        getData(filterUrl, 'filter');
        getData(url, 'list');
    }

    if( $(this).hasClass('show') ) {
        e.preventDefault();
        getData(url, 'show');
    }


});


// close forms using ESC key
$(document).keyup(function(e) {
    if (e.keyCode === 27)  {
        if($('.tx-cardealer-form').hasClass('visible')) {
            closeLayer('.tx-cardealer-form');
            return false;
        }
        if($('.tx-cardealer-show').hasClass('visible')) {
            closeDetails();
            return false;
        }
    }
});






function setCheckboxSelectLabel() {
    var wrappers = $('.wrapper');
    $.each( wrappers, function( key, wrapper ) {
        var checkboxes = $(wrapper).find('.input');
        var property = $(wrapper).attr('id');
        var prevText = '';
        var numberOfChecked = $(wrapper).find('input.val:checkbox:checked').length;
        $.each( checkboxes, function( i, checkbox ) {
            var button = $(wrapper).find('button');
            if( $(checkbox).prop('checked') == true) {
                var text = $(checkbox).next().html();
                var btnText = prevText + text;
                if(numberOfChecked >= 3) {
                    btnText = numberOfChecked +' '+ property +' ausgewählt';
                }
                $(button).text(btnText);
                prevText = btnText + ', ';
            }
        });
    });
}

function toggleCheckedAll(elem) {
    var apply = $(elem).closest('.wrapper').find('.apply-selection');
    apply.fadeIn('slow');

    var val = $(elem).closest('.dropdown').find('.val');
    var all = $(elem).closest('.dropdown').find('.all');
    var input = $(elem).closest('.dropdown').find('.input');

    if(!$(input).is(':checked')) {
        $(all).prop('checked', true);
        return;
    }

    if( $(elem).hasClass('all') ) {
        $(val).prop('checked', false);
    } else {
        $(all).prop('checked', false);
    }
}


function closeLayer(layer) {
    $(layer).hide().removeClass('visible');
    $('body').removeClass('no-scroll');
}

function setPowermailCssClasses() {
    $('.powermail_input, .powermail_textarea, .powermail_fieldwrap_type_captcha input').addClass('form-control');
    $('.powermail_submit').addClass('btn btn-primary');
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}



function closeDetails(setHistory) {
    if(setHistory === undefined) {
        setHistory = true;
    }
    $('.tx-cardealer-show').html('').removeClass('visible');
    historyUrl = $('.pageUrl').attr('href');
    if(historyUrl === undefined){
        historyUrl = "/";
    }
    if (setHistory) {
        history.pushState({url: historyUrl}, null, historyUrl);
    }
    $('body', window.parent.document).removeClass('no-scroll');
}

function doAjax(data,action,url,method, dataType) {
    showLoading(action);

    // default values for stupid IE and older Safari Versions
    if(action === undefined){
        action = "list";
    }
    if(method === undefined){
        method = "POST";
    }
    if(dataType === undefined){
        dataType = "html";
    }

    // remove chash from url
    testUri = url+'';
    test = testUri.split('?');
    // console.log(test);
    $params = getAllUrlParams(url);
    var noCacheHashURL = '';
    var i = 0;
    $.each($params , function( key, value) {
        if(key != 'chash') {
            if(i++ > 0) {
                sep = '&'
            } else {
                sep = '';
            }
            noCacheHashURL += sep+key+'='+value;

        }
    });
    url = url+'';
    beforeSlash = url.split('?');
    url = beforeSlash[0]+'?'+noCacheHashURL;
    url = url+"&type=4711&tx_cardealer_pi1[action]="+action;
    // url = url+"&type=4711";
    // console.log(url);

    request = $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: dataType
    });
    request.done(function(response) {
        clearconsole();
        // success stuff
        $(".tx-cardealer-" + action + "").parent('.tx-cardealer').replaceWith(response);
        if(action == 'filter') {
            setTimeout(function () {
                setCheckboxSelectLabel();
            }, 500)
        }
        var elem = $('.pageUrl');
        if(elem.length > 0) {
            var realUrl = elem.attr('href');
            history.pushState({url: realUrl}, null, realUrl);
        }
    });
    request.fail(function( jqXHR, textStatus ) {
        // error stuff
        ( "Ooops an error occured: " + textStatus );
    });

}


function getData(url, action) {

    showLoading(action);

    if (action == 'show') {
        // load the whole details page into div
        $('.tx-cardealer-show').addClass('visible');
        $('body').addClass('no-scroll');

        $('.tx-cardealer-show').load(url, function() {
            var baseHref = document.getElementsByTagName('base')[0].href
            clearconsole();
            // reload powermail ajax scripts
            $.getScript(baseHref+"/typo3conf/ext/powermail/Resources/Public/JavaScript/Libraries/parsley.min.js");
            $.getScript(baseHref+"/typo3conf/ext/powermail/Resources/Public/JavaScript/Powermail/Form.min.js", function () {
                setPowermailCssClasses();
            });

        });

    } else {

        if(action == 'list') {
            char = '?';
            // console.log(url, 'list');
        } else {
            // remove chash from url
            $params = getAllUrlParams(url);
            var noCacheHashURL = '';
            var i = 0;
            $.each($params , function( key, value) {
                if(key != 'chash' && key != 'tx_cardealer_pi1%5bidentifier%5d') {
                    if(i++ > 0) {
                        sep = '&'
                    } else {
                        sep = '';
                    }
                    noCacheHashURL += sep+key+'='+value;
                }
            });
            url = url+'';
            beforeSlash = url.split('?');
            url = beforeSlash[0]+'?'+noCacheHashURL;
            // console.log(url, 'filter');
            char = '&';
        }
        $('.tx-cardealer-'+action).load(url+char+'type=4711', function () {
            clearconsole();
            hideLoading(action);
        });
    }

}

function clearconsole()
{
    console.API;

    if (typeof console._commandLineAPI !== 'undefined') {
        console.API = console._commandLineAPI; //chrome
    } else if (typeof console._inspectorCommandLineAPI !== 'undefined') {
        console.API = console._inspectorCommandLineAPI; //Safari
    } else if (typeof console.clear !== 'undefined') {
        console.API = console;
    }

    console.API.clear();
}

function showLoading(action) {
    if(action == 'show') {
        return false;
    }
    $(".tx-cardealer-"+action).find("input, select, button").prop("disabled", true);
    $(".tx-cardealer-"+action).addClass('dimmed');
}

function hideLoading(action) {
    if(action == 'show') {
        return false;
    }
    $(".tx-cardealer-"+action).find("input, select, button").prop("disabled", false);
    $(".tx-cardealer-"+action).removeClass('dimmed');
}

function getAllUrlParams(url) {

    url = url+'';

    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

    // we'll store the parameters here
    var obj = {};

    // if query string exists
    if (queryString) {

        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];

        // split our query string into its component parts
        var arr = queryString.split('&');

        for (var i=0; i<arr.length; i++) {
            // separate the keys and the values
            var a = arr[i].split('=');

            // in case params look like: list[]=thing1&list[]=thing2
            var paramNum = undefined;
            var paramName = a[0].replace(/\[\d*\]/, function(v) {
                paramNum = v.slice(1,-1);
                return '';
            });

            // set parameter value (use 'true' if empty)
            var paramValue = typeof(a[1])==='undefined' ? true : a[1];

            // (optional) keep case consistent
            paramName = paramName.toLowerCase();
            paramValue = paramValue.toLowerCase();

            // if parameter name already exists
            if (obj[paramName]) {
                // convert value to array (if still string)
                if (typeof obj[paramName] === 'string') {
                    obj[paramName] = [obj[paramName]];
                }
                // if no array index number specified...
                if (typeof paramNum === 'undefined') {
                    // put the value on the end of the array
                    obj[paramName].push(paramValue);
                }
                // if array index number specified...
                else {
                    // put the value at that index number
                    obj[paramName][paramNum] = paramValue;
                }
            }
            // if param name doesn't exist yet, set it
            else {
                obj[paramName] = paramValue;
            }
        }
    }

    return obj;
}

function triggerAjaxClick(elem) {
    var speakingUrl = $(elem).attr('href');
    var ajaxLink = $(elem).prev('a.ajaxLink').attr('href');
    history.pushState({url:ajaxLink}, null, speakingUrl);
    $(elem).prev('a.ajaxLink').trigger('click');
}



// back- and forward browser tab
window.onpopstate = function(event) {
    if(event.state !== null) {
        if(event.state.url !== undefined) {
            var url = event.state.url;
            var pathArray = url.split( '/' );
            // console.log(pathArray);

            if(inArray('details', pathArray)) {
                getData(url, 'show');

            } else  {
                getData(url, 'list');
                closeDetails(0);
            }
        }
    }
};

