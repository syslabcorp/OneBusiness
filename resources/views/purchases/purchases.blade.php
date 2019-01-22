<div class="table-responsive table-purchases" style="display: ;">
  <div class="text-right">
    <button type="button"  {{ $purchase->id ? 'disabled' : '' }}
      class="btn btn-success btn-sm btnAddRow" style="margin-bottom: 10px;">Add Row (F2)</button>
  </div>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 25%">Item #</th>
        <th style="width: 25%">Item Name</th>
        <th style="width: 25%">Qty to Order</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    @if(count($purchase->details))
      @php
        $a = 1;
      @endphp
      @foreach($purchase->details as $row)
        @php
          $index = count($row->parts);
          $hdrModel = new \App\Models\Equip\Hdr;
          $hdrs = $hdrModel->orderBy('asset_id')->get();
        @endphp
        
        <tr class="purchaseRow" data-id="{{ $row->item_id }}">
          <td class="text-center" {{ $index == count($row->parts) ? 'rowspan='.(count($row->parts)+1) : '' }} >
            <label class="label-table-min index">{{ $a++ }}</label>
          </td>
          <td class="text-center">
            <select class="form-control brand" name="" id="" {{ $purchase->id ? 'disabled' : '' }} >
              <option value="">-- select --</option>
              @foreach($hdrs as $hdr)
                @if ($hdr->asset_id == $row->item_id)
                <option value="{{ $row->item_id }}" selected>{{ $row->equipment()->description }}</option>
                @else
                <option value="{{ $hdr->asset_id }}">{{ $hdr->description }}</option>
                @endif
              @endforeach
            </select>
          </td>
          <td ></td>
          <td style="width: 100px;" {{ $index == count($row->parts) ? 'rowspan='.(count($row->parts)+1) : '' }}>
            <button type="button" {{ $purchase->id ? 'disabled' : '' }} class="btn btn-danger btn-md btnRemoveRow center-block">
              <i class="fas fa-trash-alt"></i>
            </button>
          </td>
        </tr>
        @foreach($row->parts as $part)
        <tr class="rowTR" data-parent="{{ $row->item_id }}">
          <td class="text-center">
            <label for="">{{ $part->getItemAttribute() ? $part->getItemAttribute()->description : 'NaN'}}</label>
            <input type="hidden" name="parts[{{ $row->item_id }}][item_id][{{ $loop->index+1 }}]" value="{{ $part->item_id }}">
          </td>
          <td>
            <input type="number" class="form-control text-center label-table-min qty quantity" name="parts[{{ $row->item_id }}][qty][{{ $loop->index+1 }}]" value="{{ $part->qty_to_order }}" autocomplete="off" readonly>
          </td>
        </tr>
        @php 
          $index --;
        @endphp
        @endforeach
      @endforeach
    @endif
    </tbody>
  </table>
</div>