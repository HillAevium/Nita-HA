/**
 * Swap a container out for another.
 * 
 * @param url the url to load the new container
 * @param container the old container to replace
 * @param callback a function to call after the container has been loaded
 * @return void
 */
function doContainerSwap(url, container, callback) {
    $(container).hide();
    // Because Refreshing is so 2009...
    $.get(url, function(data) {
        $(container).after(data);
        callback();
    });
}

function doPageLoad(uri, secure, useHistory) {
    var protocol = (secure ? 'https://' : 'http://');
    var host     = window.location.host;
    
    // Do the page reload
    if(useHistory) {
        window.location.href = protocol + host + uri;
    } else {
        window.location.replace(protocol + host + uri);
    }
}

function clearFormElements(el) {
    $(el).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });
}

/**
 * Click event handler for 'add to shopping cart' button.
 */
function handleCartItemClick(id) {
    var item = $("#" + id + " #cart_item");
    var html = item.html();
    item.html("Loading...");
    $.post('/cart/add', {id: id}, function(data, status, xhr) {
        item.html(html);
        switch(xhr.status) {
            case 202 : // ACCEPTED
                alert("Item Added");
                break;
            default :
                alert("Error");
                break;
        }
    });
}

/**
 * Hover event handler for list items.
 */
function handleItemHover() {
    $(this).toggleClass("item_hover");
}

/**
 * Click event handler for list items.
 * 
 * @param event the click event
 */
function handleItemClick(event) {
    // If the cart was clicked divert to the event handler
    // for that. We do this here to make it easier to find
    // which item was clicked without embedding it into the
    // cart div itself.
    
    if(event.target.id == 'cart_item') {
        var id = event.currentTarget.id;
        handleCartItemClick(id);
    } else {
        doPageLoad('/Program/' + event.currentTarget.id, false, true);
    }
}

function handleBreadcrumbClick(event) {
    var el = $(event.target);
    var id = el.parent().attr('id');
    doPageLoad(id, false, true);
}

/**
 * Add event handlers to listed items.
 * 
 * @return void
 */
function addItemHandler() {
    //$("table#items > tbody > tr").hover(handleItemHover, handleItemHover);
    $("table#items > tbody > tr").click(handleItemClick);
}

/**
 * Add event handlers to tab panels.
 * 
 * @return void
 */
function addTabHandler() {
    $("ul.tabs").tabs("div.panes > div");
    $("#grandchild_tab_panel").tabs("div");
}

function addBreadcrumbHandler() {
    $("div#breadcrumb div.parent").click(handleBreadcrumbClick);
}

function addTooltipHandler() {
    $("#search_bar a").tooltip();
}


/*
 * bootstrap
 */
function init() {
    addItemHandler();
    addTabHandler();
    addBreadcrumbHandler();
    addTooltipHandler();
}

$(init);