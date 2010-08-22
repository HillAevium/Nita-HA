<div id="debug_box" style="position:absolute;top:0;left:0;"></div>
<div style="position:relative;height:400px;left:-30px;">
    <img src="/resources/images/transparent.png" width="664" height="363" style="z-index:10;position:absolute;top:-50px;left:130px;cursor:pointer;" usemap="#homemap" />
    <img id="normal" style="position:absolute; top:-50px; left:130px; z-index:9;" src="/resources/images/homepage_nav_normal.png" />
    
    <img id="about_inactive" class="inactive" style="position:absolute;top:58px;left:130px;z-index:1;" src="/resources/images/homepage_nav_about_inactive.png" />
    <img id="about_active" class="active" style="position:absolute;top:58px;left:130px;z-index:2;display:none;" src="/resources/images/homepage_nav_about_active.png" />
    <img id="about_bubble" class="active" style="position:absolute;top:222px;left:44px;z-index:11;display:none;" src="/resources/images/homepage_bubble_about.png" />
    
    <img id="contact_inactive" class="inactive" style="position:absolute;top:120px;left:626px;z-index:1;" src="/resources/images/homepage_nav_contact_inactive.png" />
    <img id="contact_active" class="active" style="position:absolute;top:120px;left:626px;z-index:2;display:none;" src="/resources/images/homepage_nav_contact_active.png" />
    <img id="contact_bubble" class="active" style="position:absolute;top:261px;left:405px;z-index:11;display:none;" src="/resources/images/homepage_bubble_contact.png" />
    
    <img id="publications_inactive" class="inactive" style="position:absolute;top:99px;left:230px;z-index:3;" src="/resources/images/homepage_nav_publications_inactive.png" />
    <img id="publications_active" class="active" style="position:absolute;top:99px;left:230px;z-index:4;display:none;" src="/resources/images/homepage_nav_publications_active.png" />
    <img id="publications_bubble" class="active" style="position:absolute;top:260px;left:403px;z-index:11;display:none;" src="/resources/images/homepage_bubble_publications.png" />
    
    <img id="donate_inactive" class="inactive" style="position:absolute;top:91px;left:531px;z-index:3;" src="/resources/images/homepage_nav_donate_inactive.png" />
    <img id="donate_active" class="active" style="position:absolute;top:92px;left:531px;z-index:4;display:none;" src="/resources/images/homepage_nav_donate_active.png" />
    <img id="donate_bubble" class="active" style="position:absolute;top:242px;left:392px;z-index:11;display:none;" src="/resources/images/homepage_bubble_donate.png" />

    <img id="custom_inactive" class="inactive" style="position:absolute;top:-50px;left:223px;z-index:5;" src="/resources/images/homepage_nav_custom_inactive.png" />
    <img id="custom_active" class="active" style="position:absolute;top:-50px;left:223px;z-index:6;display:none;" src="/resources/images/homepage_nav_custom_active.png" />
    <img id="custom_bubble" class="active" style="position:absolute;top:-140px;left:365px;z-index:11;display:none;" src="/resources/images/homepage_bubble_custom.png" />

    <img id="lawschools_inactive" class="inactive" style="position:absolute;top:-43px;left:552px;z-index:5;" src="/resources/images/homepage_nav_lawschools_inactive.png" />
    <img id="lawschools_active" class="active" style="position:absolute;top:-43px;left:552px;z-index:6;display:none;" src="/resources/images/homepage_nav_lawschools_active.png" />
    <img id="lawschools_bubble" class="active" style="position:absolute;top:-88px;left:675px;z-index:11;display:none;" src="/resources/images/homepage_bubble_lawschools.png" />

    <img id="programs_inactive" class="inactive" style="position:absolute;top:41px;left:381px;z-index:7;" src="/resources/images/homepage_nav_programs_inactive.png" />
    <img id="programs_active" class="active" style="position:absolute;top:41px;left:381px;z-index:8;display:none;" src="/resources/images/homepage_nav_programs_active.png" />
    <img id="programs_bubble" class="active" style="position:absolute;top:240px;left:415px;z-index:11;display:none;" src="/resources/images/homepage_bubble_programs.png" />
</div>

<img style="position:absolute; z-index:0; bottom:0; right:-115px;" src="/resources/images/portraits/ernesto.png" />

<div id="slogan" style="position:absolute;left:0;bottom:40px;line-height:30px;color:#004b85;">
    NITA is the premier provider<br />
    of learning-by-doing education for the legal profession.
</div>
<map name="homemap" id="map">
    <area id="about"        shape="poly"   coords="17,139, 111,112, 118,167, 128,179, 117,244, 34,273, 2,166, 17,139" />
    <area id="custom"       shape="poly"   coords="94,27, 261,1, 275,92, 254,91, 252,172, 160,189, 118,169, 97,27" />
    <area id="programs"     shape="poly"   coords="253,90, 439,92, 439,262, 409,277, 253,282, 253,90" />
    <area id="contact"      shape="poly"   coords="548,188, 600,169, 621,182, 660,278, 542,324, 529,287, 548,287, 548,188" />
    <area id="donate"       shape="poly"   coords="439,153, 538,174, 549,167, 549,288, 406,282, 407,275, 438,255, 439,153" />
    <area id="lawschools"   shape="poly"   coords="461,7, 600,42, 569,165, 529,176, 439,155, 439,94, 461,7" />
    <area id="publications" shape="poly"   coords="104,347, 127,175, 163,188, 256,170, 256,279, 300,278, 283,357, 104,347" />
</map>
<script type="text/javascript">

$(document).ready(function() {
    $('#normal').fadeIn();
    $('#map').hover(
        function() {
            $("#normal").fadeOut();
        },
        function() {
            $("#normal").fadeIn();
        }
    );
    $('#map > area').each(function() {
        $(this).hover(
            function(event) {
                $("#" + event.target.id + "_active").fadeIn();
                $("#" + event.target.id + "_bubble").fadeIn();
            },
            function(event) {
                
                $(".active").fadeOut();
            }
        );

        $(this).click(function(event) {
            switch(event.target.id) {
                case 'about' :
                    doPageLoad('/About', false, true);
                    break;
                case 'custom' :
                    doPageLoad('/CustomPrograms', false, true);
                    break;
                case 'programs' :
                    doPageLoad('/Shop', false, true);
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
                case 'publications' :
                    doPageLoad('/Publications', false, true);
                    break;
            }
        });
    });
});

// Debugging for the map
// Enable this if the image under the image map changes
// and dimensions need to be resized.

/*
$(document).ready(function() {
    $('#debug_box').append("");
    $('#normal').mousemove(function(event) {
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
    var offset = $('#normal').offset();
    $('#debug_box').empty()
//                   .append("Image Top: " + offset.top + "<br />")
//                   .append("Image Left: " + offset.left + "<br />")
//                   .append("Mouse x: " + x + "<br />")
//                   .append("Mouse y: " + y + "<br />")
//                   .append("Image x: " + (x - offset.left) + "<br />")
//                   .append("Image y: " + (y - offset.top) + "<br />")
//                   .append("Selection: " + renderDebug.currentImage + "<br />");
}

renderDebug.currentImage = 'None';
*/
</script>