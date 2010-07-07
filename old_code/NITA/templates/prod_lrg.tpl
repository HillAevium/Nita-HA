<div class="ProdItemLarge">
  <div class="Image">[Image]</div>
  <h1>[Name]</h1>
  <div id="tabs">
    <ul id="ProdNav">
      <li id="btnOverview"><a href="#">Overview</a></li>
      <li id="btnFacility"><a href="#">Facility</a></li>
      <li id="btnCLECredits"><a href="#">CLE Credits</a></li>
      <li id="btnTravel"><a href="#">Travel</a></li>
      <li id="btnBooks"><a href="#">Books</a></li>
      <li id="btnForum"><a href="#">Forums</a></li>
    </ul>
    <div id="Details">
      <div id="Overview">
        <p> [SKU]<br />
          [Avail]</p>
        [Desc] </div>
      <div id="Facility">[Fac]</div>
      <div id="CLECredits">[CLE]</div>
      <div id="Travel">[Travel]</div>
      <div id="Books">[Books]</div>
      <div id="Forum">[Forum]</div>
    </div>
    <form action="[AddCart]" method="post" name="AddtoCartForm" id="AddtoCartForm" enctype="multipart/form-data">
      Price: [Price]<br />
      [Attr]
      <div id="BtnAddCart">
        <input type="submit" name="Add to Cart" id="Add to Cart" title="Add to Cart" value="Add to Cart" />
      </div>
      <input type="hidden" name="qty" id="qty_[ID]" title="Quantity" value="1" />
      <input type="hidden" name="id" id="id" value="[ID]" />
      <input type="hidden" name="price" id="price" value="[PriceInt]" />
      <input type="hidden" name="name" id="name" value="[Name]" />
      <input type="hidden" name="Controller" id="Controller" value="AddToCart" />
    </form>
  </div>
</div>
<script>
$(document).ready(function () {
  $("#Details div:gt(0)").addClass("Hide");
	$("#ProdNav a").click(function() {
		$("#ProdNav a").removeClass("NavSel");
		$(this).addClass("NavSel");
		var ID=$(this).parent().attr("id").substr(3);
		$("#Details div").addClass("Hide");
		$("#Details #"+ID).removeClass("Hide");
		return false;
	} );
});
</script>
