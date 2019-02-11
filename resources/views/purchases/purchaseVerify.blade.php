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
    @if(count($purchase->request_details->whereIn('isVerified', [1,2])))
      @foreach($purchase->request_details->whereIn('isVerified', [1,2]) as $item)
        <tr class="purchaseRow" >
          <td class="text-center" >
            <label class="label-table-min index">{{ $item->id }}</label>
            <input type="hidden" name="parts[{{ $item->id }}][id]" value="{{ $item->id }}">
          </td>
          <td class="text-center">
            <label for="">{{ $item->itemMaster() ? $item->itemMaster()->description : ''  }}</label>
          </td>
          <td class="text-center">
            <input type="number" class="form-control text-center label-table-min qty quantity" name="parts[{{ $item->id }}][qty]" value="{{ $item->qty_to_order }}" autocomplete="off" readonly>
          </td>
          <td class="text-center">
            <label for="">{{ $item->remark ? $item->remark : ''}}</label>
          </td>
          <td style="width: 100px;" >
            @if($item->isVerified == 2)
            <button type="button" class="btn btn-danger btn-md center-block access_delete" data-toggle="modal" data-target="#myModal">
              <i class="fas fa-trash-alt"></i>
            </button>
            @else
            <button type="button" class="btn btn-danger btn-md center-block" disabled>
              <i class="fas fa-trash-alt"></i>
            </button>
            @endif
          </td>
        </tr>
      @endforeach
    @endif
    </tbody>
  </table>
</div>