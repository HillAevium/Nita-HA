<div id="debug_box"></div>
<img src="/resources/images/home_demo.jpg"
     id="homeimg"
     usemap="#homemap"/>
<map name="homemap" id="map">
    <area id="About"   shape="poly"   coords="120,163, 223,133, 240,200, 230,265, 155,295, 120,163" />
    <area id="Custom"  shape="poly"   coords="215,50,  365,30,  380,110, 360,110, 360,190, 270,210, 240,195, 215,50" />
    <area id="Browse"  shape="poly"   coords="245,200, 280,210, 360,195, 360,305, 415,305, 400,370, 220,355, 245,200" />
    <area id="Contact" shape="poly"   coords="650,210, 710,190, 760,300, 650,345, 635,315, 650,315, 650,210" />
    <area id="Donate"  shape="poly"   coords="545,180, 640,200, 640,310, 505,310, 500,300, 545,280, 545,180" />
    <area id="School"  shape="poly"   coords="565,30,  705,65,  675,190, 645,200, 545,175, 545,110, 565,30" />
    <area id="Enroll"  shape="poly"   coords="360,110, 545,110, 540,275, 510,300, 360,300, 360,110" />
</map>
<script type="text/javascript">

$(document).ready(function() {
    $('#map > area').each(function() {
        $(this).click(function(event) {
            switch(event.target.id) {
                case 'About' :
                    doPageLoad('/About', false, true);
                    break;
                case 'Custom' :
                    doPageLoad('/CustomPrograms', false, true);
                    break;
                case 'Browse' :
                    doPageLoad('/Publications', false, true);
                    break;
                case 'Contact' :
                    doPageLoad('/ContactNita', false, true);
                    break;
                case 'Donate' :
                    doPageLoad('/Donate', false ,true);
                    break;
                case 'School' :
                    doPageLoad('/LawShools', false, true);
                    break;
                case 'Enroll' :
                    doPageLoad('/Shop', false, true);
                    break;
            }
        });
    });
});

// Debugging for the map
// Enable this if the image under the image map changes
// and dimensions need to be resized.

$(document).ready(function() {
    $('#debug_box').append("");
    $('#homeimg').mousemove(function(event) {
        renderDebug(event.pageX, event.pageY);
    });
    $('#map > area').each(function() {
        $(this).mousemove(function(event) {
            renderDebug(event.pageX, event.pageY);
        });
        $(this).mouseenter(function(event) {
            renderDebug.currentImage = event.target.id;
        });
        $(this).mouseleave(function() {
            renderDebug.currentImage = 'None';
        });
    });
});

function renderDebug(x, y) {
    var offset = $('#homeimg').offset();
    $('#debug_box').empty()
//                   .append("Image Top: " + offset.top + "<br />")
//                   .append("Image Left: " + offset.left + "<br />")
//                   .append("Mouse x: " + x + "<br />")
//                   .append("Mouse y: " + y + "<br />")
//                   .append("Image x: " + (x - offset.left) + "<br />")
//                   .append("Image y: " + (y - offset.top) + "<br />")
                   .append("Selection: " + renderDebug.currentImage + "<br />");
}

renderDebug.currentImage = 'None';
</script>