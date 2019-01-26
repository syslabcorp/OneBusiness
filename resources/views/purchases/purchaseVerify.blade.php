<div class="table-responsive table-purchases" style="display: ;">
  <div class="text-right" style="visibility:{{ $purchase->flag == 1 ? 'hidden' : '' }}">
    <button type="button"  {{ $purchase->id ? 'disabled' : '' }}
      class="btn btn-success btn-sm btnAddRow" style="margin-bottom: 10px;">Add Row (F2)</button>
  </div>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 25%">Item #</th>
        <th style="width: 25%">Item Name</th>
        <th style="width: 20%">Updated Qty</th>
        <th style="width: 20%">Remarks</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    @if(count($purchase->details->where('isVerified', '=', 1)))
      @foreach($purchase->details->where('isVerified', '=', 1) as $item)
        {{ $item }}
        <tr class="purchaseRow" >
          <td class="text-center" >
            <label class="label-table-min index">{{ $item->id }}</label>
            <input type="hidden" name="id" value="{{ $item->id }}">
          </td>
          <td class="text-center">
            <label for="">ABCD</label>
          </td>
          <td class="text-center">
            <input type="number" class="form-control text-center label-table-min qty quantity" name="" value="" autocomplete="off" readonly>
          </td>
          <td class="text-center">
            <label for="">{{ $item->reason }}</label>
          </td>
          <td style="width: 100px;" >
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