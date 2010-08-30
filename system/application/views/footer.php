</div> <!-- #main_container -->
<div id="footer">
    <div id="footer_inner">
        <div id="footer_menu1">
            <ul class="menu">
                <li><a class="orange" href="/Shop">programs</a></li> /
                <li><a class="red" href="/Publications">publications</a></li> /
                <li><a class="purple" href="/CustomPrograms">custom programs</a></li> /
                <li><a class="dark_blue" href="/LawSchools">law schools</a></li> /
                <li><a class="green" href="/Donate">donate</a></li> /
                <li><a class="teal" href="/About">about</a></li> /
                <li><a class="light_blue" href="/ContactNita">contact</a></li>
            </ul>
        </div>
        <div id="footer_menu2">
            <ul class="menu">
                <li><a href="/MyCart">shopping cart</a></li> /
                <li><a href="/MyAccount">my account</a></li> /
                <li><a href="/Careers">careers</a></li> /
                <li><a href="/Links">links</a></li> /
                <li><a href="/Privacy">privacy policy</a></li> /
                <li><a href="/NewsRoom">news room</a></li> /
                <li><a href="/ENewsLetter">e-newsletter</a></li> /
                <li><a href="/SiteMap">sitemap</a></li>
            </ul>
        </div>
        <div id="copyright">
            <div id="copyright_inner">
                &copy;2010 National Institute for Trial Advocacy / <a href="mailto:info@nita.org">info@nita.org</a>
                <a id="facebook" href=""></a>
                <a id="twitter" href=""></a>
            </div>
        </div>
        <div id="design_credit">
            <div id="design_credit_inner">
                <table>
                    <tr>
                        <td align="right">
                            design by <span class="ha">HA</span>&nbsp;
                        </td>
                        <td align="left">
                            <div class="blue_square"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div> <!-- #footer_inner -->
</div> <!-- #footer -->
<?php if($_SERVER['REQUEST_URI'] == '/'): ?>
<div style="width:1000px;margin:0 auto;position:relative;z-index:1;">
<?php
if ($handle = opendir(BASEPATH.'../resources/images/portraits')) {
    while (false !== ($file = readdir($handle))) {
        if($file != "." && $file != "..") {
            $images[] = $file;
        }
    }
    shuffle($images); 
    echo "<img style=\"position:absolute; bottom:90px; right:-55px;\" src=\"/resources/images/portraits/" . $images[0] . "\" />";
}
?>
<div id="slogan" style="position:absolute;left:17px;bottom:130px;line-height:30px;color:#004b85;text-align:left;">
    NITA is the premier provider<br />
    of learning-by-doing education for the legal profession.
</div>
</div>
<?php endif; ?>
<div> <!-- Auth debug -->
    <?php //echo $this->mod_auth; ?>
</div>

<div class="ajax_loading" style="display:none;width:100%;height:100%;position:fixed;_position:absolute;top:0;_top:expression(eval(document.body.scrollTop));left:0;opacity:0.5;filter:alpha(opacity=50);z-index:9999;background:#000;"></div>
<div class="ajax_loading" style="display:none;width:100%;height:100%;position:fixed;_position:absolute;top:0;_top:expression(eval(document.body.scrollTop));left:0;z-index:10000;background:url(/resources/images/ajax-loader.gif) center center no-repeat;"></div>
</body>
</html>