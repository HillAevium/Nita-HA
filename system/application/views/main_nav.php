<?php
    $uri = $_SERVER['REQUEST_URI'];
?>

<div id="main_nav">
  <ul class="menu">
    <li><a img="programs" class="programs <?php if(preg_match('#(Shop|Program/)#',$uri)) { echo "current"; } ?>" href="/Shop"></a></li>
    <li><a img="publications" class="publications <?php if(preg_match('#Publications#',$uri)) { echo "current"; } ?>" href="/Publications"></a></li>
    <li><a img="custom_programs" class="custom_programs <?php if(preg_match('#CustomPrograms#',$uri)) { echo "current"; } ?>" href="/CustomPrograms"></a></li>
    <li><a img="law_schools" class="law_schools <?php if(preg_match('#LawSchools#',$uri)) { echo "current"; } ?>" href="/LawSchools"></a></li>
    <li><a img="donate" class="donate <?php if(preg_match('#Donate#',$uri)) { echo "current"; } ?>" href="/Donate"></a></li>
    <li><a img="about" class="about <?php if(preg_match('#About#',$uri)) { echo "current"; } ?>" href="/About"></a></li>
    <li><a img="contact" class="contact <?php if(preg_match('#ContactNita#',$uri)) { echo "current"; } ?>" href="/ContactNita"></a></li>
  </ul>
</div> <!-- #main_nav -->
