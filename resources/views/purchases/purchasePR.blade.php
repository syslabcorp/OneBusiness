<div class="table-responsive table-purchases" style="display: ;">
  <div class="text-right">
    <button type="button" {{ $purchase->date_approved ? 'disabled' : '' }}  
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
        <th style="width: 150px">Qty Delivered</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @if(count($purchase->details))
      @foreach($purchase->details as $row)
      <tr class="purchaseRow" style="" >
        <td class="text-center">
          <label class="label-table-min index">{{ $loop->index+1 }}</label>
        </td>
        <td><input type="checkbox" class="form-check-input" name="purchases[{{ $loop->index+1 }}][eqp]" value="{{ $row->eqp == 1 ? '1' : '0' }}"  {{ $row->eqp == 1 ? 'checked' : '' }}></td>
        <td><input type="checkbox" class="form-check-input" name="purchases[{{ $loop->index+1 }}][prt]" value="{{ $row->prt == 1 ? '1' : '0' }}" {{ $row->prt == 1 ? 'checked' : '' }}></td>
        <td>
        <select name="purchases[{{ $loop->index+1 }}][item_name]" class="form-control">
        @if(count($branches))
          @foreach($branches as $branch)
          
            @if($branch->Branch == $row->item_name)
            <option value="{{ $branch->Branch }}" selected>{{ $branch->Description }}</option>
            @else
            <option value="{{ $branch->Branch }}">{{ $branch->Description }}</option>
            @endif
          @endforeach
        @endif
        </select>
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="purchases[{{ $loop->index+1 }}][qty_to_order]" value="{{ $row->qty_to_order }}" autocomplete="off">
        </td>
        <td>
          <input type="number" class="form-control text-center " name="" value="" autocomplete="off">
        </td>
        <td style="width: 100px;">
          <button {{ $purchase->date_approved ? 'disabled' : '' }} type="button" class="btn btn-danger btn-md btnRemoveRow center-block">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
      @endforeach
      @endif
      <tr class="newPurchase" style="display: none;">
        <td class="text-center">
          <label class="label-table-min index">1</label>
        </td>
        <td><input type="checkbox" class="form-check-input" name="eqp" value="1"></td>
        <td><input type="checkbox" class="form-check-input" name="prt" value="1"></td>

        <td>
        <select name="item_name" class="form-control">
        @if(!empty($branches))
          @foreach($branches as $branch)
            <option value="{{ $branch->Branch }}">{{ $branch->Description }}</option>
          @endforeach
        @endif
        </select>
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="qty_to_order" value="1" autocomplete="off">
        </td>
        <td>
          <input type="number" class="form-control text-center " name="" value="" autocomplete="off">
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