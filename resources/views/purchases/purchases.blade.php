<div class="table-responsive table-purchases" style="display: none;">
  <div class="text-right">
    <button type="button"  
      class="btn btn-success btn-sm btnAddRow" style="margin-bottom: 10px;">Add Row (F2)</button>
  </div>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 80px">Item #</th>
        <th style="width: 150px">EQP</th>
        <th style="width: 150px">PRT</th>
        <th style="width: 150px">Item Name</th>
        <th style="width: 150px">Qty to Order</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      
      <tr class="newPart" style="display: none;">
        <td class="text-center">
          <label class="label-table-min"></label>
          <input type="hidden" class="form-control item_id" name="item_id">
        </td>
        <td align="center"><input type="checkbox" class="form-check-input"></td>
        <td align="center"><input type="checkbox" class="form-check-input"></td>

        <td><select name="type" class="form-control">
                <option value="">Branch5</option>
                <option value="">Branch4</option>
              </select></td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="qty" value="1" autocomplete="off">
        </td>
        <td style="width: 100px;">
          <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>