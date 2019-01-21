<div class="table-responsive table-purchases" style="display: ;">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 130px">Item #</th>
        <th style="width: 130px">Item </th>
        <th style="width: 130px">For Equipment </th>
        <th style="width: 130px">Vendor </th>
        <th style="width: 130px">Qty Ordered </th>
        <th style="width: 130px">Cost </th>
        <th style="width: 130px">Total Cost</th>
        <th style="width: 130px">Action </th>
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
        <td class="text-center"><label for=""></label></td>
        <td class="text-center"><label for=""></label></td>
        <td>
          <select name="" class="form-control">
          
          </select>
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" value="{{ $row->qty_to_order }}" autocomplete="off">
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
      @endforeach
      @endif
    </tbody>
  </table>
</div>