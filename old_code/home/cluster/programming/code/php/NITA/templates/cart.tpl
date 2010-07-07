
<form method="post" name="cartForm" id="cartForm" enctype="multipart/form-data" action="[CartUrl]">
  [CARTBUTTONS]
  <table>
    <tr>
      <th>Pragram</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>&nbsp;</th>
    </tr>
    [CARTITEMS]
  </table>
  <hr />
  <p><strong>Total:</strong> [Total]<br />
    <strong>Tax: </strong> [Tax]<br />
    <strong>Shipping: </strong> [Ship]</p>
  <hr />
  <p> <strong>Grand Total: </strong> [Grand] </p>
  [CARTBUTTONS]
  <input type="hidden" name="Controller" id="Controller" value="false" />
</form>
