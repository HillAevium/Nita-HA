<tr>
  <td><a href="[URL]" title="[Name]" class="Header">[Name]</a></td>
  <td>[SDate] - [EDate]</td>
  <td>[State], [City]</td>
  <td>[Price]</td>
  <td><form action="[AddCart]" method="post" name="AddtoCartForm" id="AddtoCartForm" enctype="multipart/form-data">
      <div class="AddToCartCol">
        <input type="submit" name="Add to Cart" id="Add to Cart" title="Add to Cart" value="Add to Cart" />
      </div>
      <input type="hidden" name="qty" id="qty_[ID]" title="Quantity" value="1" />
      <input type="hidden" name="id" id="id" value="[ID]" />
      <input type="hidden" name="price" id="price" value="[PriceInt]" />
      <input type="hidden" name="name" id="name" value="[Name]" />
      <input type="hidden" name="Controller" id="Controller" value="AddToCart" />
    </form></td>
</tr>
