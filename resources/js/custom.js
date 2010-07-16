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
 * Click event handler for 'add account' button on registration page.
 */
function addAccount() {
    var fields = $("profilefields");
    $("form[name='regForm']").append(fields.html());
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

function handleBreadcrumbClick(event) {
    var id = event.target.id;
    doPageLoad(id, false, true);
}

function handleSearchboxToggle() {
    $("div#search_box").toggleClass("hide");
    $("div#search_open").toggleClass("hide");
}

function handleSearchboxType(event) {
    //dumpHeights();
    
    var selected = $("select#search_type option:selected").text();
    
    showAllRows();
    
    if(selected != '0') {
        hideRows(2, selected);
    }
    
    setWindowHeight($("body").height());
    
    dumpHeights();
}

function setWindowHeight(value) {
    $(document).height(3000);
}

function dumpHeights() {
    //var window = $(window).height();
    var dh = $(document).height();
    var bh = $(document.body).height();
    var hh = $("html").height();
    alert("Document: " + dh + "\nBody: " + bh + "\nHTML: " + hh);
}

function hideRows(columnIndex, text) {
    $("table#items > tbody > tr > td:nth-child("+columnIndex+")").each(
        function(index, element) {
            if($(this).text() != text) {
                $(this).parent().addClass("hide");
            }
        }
    );
}

function showAllRows() {
    $("table#items > tbody > tr").each(
        function() {
            $(this).removeClass("hide");
        }
    );
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
}

function addBreadcrumbHandler() {
    $("div#breadcrumb > div.parent").click(handleBreadcrumbClick);
}

function addSearchboxHandler() {
    $("div#search_open").click(handleSearchboxToggle);
    $("div#search_close").click(handleSearchboxToggle);
    $("select#search_type").change(handleSearchboxType);
}

function addDebugBox() {
    addDebugBox.scroll = $(document).scrollTop();
    $("body").append("<div id='debug' style='position:absolute;right:0;top:533;'>Debug</div>");
    $(document).scroll(
        function(event) {
            var position = $(document).scrollTop();
            var move = position - addDebugBox.scroll;
            if(position > 533) {
                $("#debug").animate({top:'+='+move},0);
            }
            addDebugBox.scroll = position;
        }
    );
    $("#debug").click(
        function() {
            $("#debug").addClass("hide");
        }
    );
}

function addDebugHandlers() {
    $(document).mousemove(
        function(event) {
            debug.mouseX = event.pageX;
            debug.mouseY = event.pageY;
            debug();
        }
    );
    $(document).scroll(
        function(event) {
            debug.scrollLeft = $(document).scrollLeft();
            debug.scrollTop  = $(document).scrollTop();
            debug();
        }
    );
}

function debug() {
    if(typeof debug.mouseX == undefined) {
        debug.mouseX = 0;
        debug.mouseY = 0;
        debug.scrollTop = 0;
        debug.scrollLeft = 0;
    }
    var text = "Debug<br/>"
             + "Mouse X: " + debug.mouseX + "<br />"
             + "Mouse Y: " + debug.mouseY + "<br />"
             + "Scroll Top: " + debug.scrollTop + "<br />"
             + "Scroll Left: " + debug.scrollLeft + "<br />";
    $("#debug").html(text);
    
}
/*
 * bootstrap
 */
function init() {
    addItemHandler();
    addTabHandler();
    addBreadcrumbHandler();
    addSearchboxHandler();
    addDebugBox();
    addDebugHandlers();
}

$(init);