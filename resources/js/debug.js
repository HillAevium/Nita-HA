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

$(addDebugBox);
$(addDebugHandlers);