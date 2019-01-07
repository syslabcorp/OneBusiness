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
      <tr class="newPurchase" style="display: none;">
        <td class="text-center">
          <label class="label-table-min index">1</label>
        </td>
        <td><input type="checkbox" class="form-check-input" name="eqp" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="prt" value="1"></td>

        <td>
        <select name="item_name" class="form-control">
        @foreach($branches as $branch)
          <option value="{{ $branch->Branch }}">{{ $branch->Description }}</option>
        @endforeach
        </select>
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="qty_to_order" value="1" autocomplete="off">
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