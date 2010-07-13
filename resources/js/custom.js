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

/**
 * Click event handler for 'add to shopping cart' button.
 */
function handleCartItemClick(id) {
    alert("You have selected: " + id);
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
    /* Not using ajax page loads yet
    var id = event.currentTarget.id;
    var requestUrl = 'http://127.0.0.1:8082/shop/program/id/' + id + '/request/ajax';
    var listPanel = "#list_panel";
    var callback = addTabHandler;
    
    // Swap the list for the details
    doContainerSwap(requestUrl, listPanel, addTabHandler);
    */
    
    // If the cart was clicked divert to the event handler
    // for that. We do this here to make it easier to find
    // which item was clicked without embedding it into the
    // cart div itself.
    if(event.target.id == 'cart_item') {
        var id = event.currentTarget.id;
        handleCartItemClick(id);
    } else {
        doPageLoad('/shop/program/id/' + event.currentTarget.id, false, true);
    }
}

/**
 * Add event handlers to listed items.
 * 
 * @return void
 */
function addItemHandler() {
    $("table#items > tbody > tr").hover(handleItemHover, handleItemHover);
    $("table#items > tbody > tr").click(handleItemClick);
}

/**
 * Add event handlers to tab panels.
 * 
 * @return void
 */
function addTabHandler() {
    $("ul.tabs").tabs("div.panes > div");
}

/*
 * bootstrap
 */
function init() {
    addItemHandler();
    addTabHandler();
}

$(init);