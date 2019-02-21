<div class="table-responsive table-purchases" style="display: ;">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        @if($purchase->eqp_prt == 'Equipment')
        <th style="width: 12%;">Equipment </th>
        <th style="width: 12%">Item #</th>
        <th style="width: 12%">Item </th>
        <th style="width: 12%">Vendor </th>
        <th style="width: 12%">Qty Ordered </th>
        <th style="width: 12%">Cost </th>
        <th style="width: 12%">Total Cost</th>
          @if(($purchase->flag != 4) && ($purchase->flag != 6)) 
          <th style="width: 12%">Action </th>
          @endif
        @elseif($purchase->eqp_prt == 'Part')
        <th style="width: 12%">Item #</th>
        <th style="width: 12%">Item </th>
        <th style="width: 12%">For Equipment </th>
        <th style="width: 12%">Vendor </th>
        <th style="width: 12%">Qty Ordered </th>
        <th style="width: 12%">Cost </th>
        <th style="width: 12%">Total Cost</th>
          @if(($purchase->flag != 4) && ($purchase->flag != 6)) 
          <th style="width: 12%">Action </th>
          @endif
        @endif
      </tr>
    </thead>
    <tbody>
      @if(count($purchase->details))
      @php 
        $a = 1;
      @endphp
          @foreach($purchase->details as $row)
          @if($purchase->eqp_prt == 'Equipment')
            @php
              $index = count($row->parts);
              $hdrModel = new \App\Models\Equip\Hdr;
              $hdrs = $hdrModel->orderBy('asset_id')->get();
              $vendorModel = new \App\Vendor;
              $vendors = $vendorModel->orderBy('VendorName','asc')->get();
            @endphp
      
            @foreach($row->parts as $part)
            <tr class="purchaseRow">
              @if($index == count($row->parts))
              <td class="text-center"  rowspan={{ count($row->parts) }} >
                <label class="label-table-min">{{ $row->equipment() ? $row->equipment()->description : 'NaN' }}</label>
              </td>
              @endif
              <td class="text-center">
                @if($part->isVerified == 2 || $part->isVerified == 3 )
                  <i class="fas fa-exclamation-triangle dropDown">
                    <div class="menuDropDown">
                      <strong class="title">Quantity  Changed</strong>
                      <p class="item">from {{ $part->qty_old }} to {{ $part->qty_to_order }}</p>
                      @if($part->date_verified)
                        <p class="item" style="color:red">Verified  {{ $part->date_verified }}</p>
                      @endif
                    </div>
                  </i>
                @elseif($part->isVerified == 1)
                  <i class="fas fa-exclamation-triangle dropDown">
                    <div class="menuDropDown">
                      <strong class="title">Item Deleted</strong>
                      <p class="item">{{ $part->remark }}</p>
                    </div>
                  </i>
                @endif 
                <label class="label-table-min index">{{ $loop->index+1 }}</label>
                <input type="hidden" name="" value="{{ $part->id }}">
              </td>
              <td class="text-center"><label for="">{{ $part->itemMaster() ? $part->itemMaster()->description : '' }}</label></td>
              <td>
                <select name="parts[{{ $row->item_id }}][{{ $part->id }}][vendor_id]" class="form-control " {{ ( \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') && $row->purchaseRequest->flag != 1) && ($row->purchaseRequest->flag != 4) && ($row->purchaseRequest->flag != 6) ? '' : 'disabled' }} > 
                <option class="" value="">-- select --</option>
                @foreach($vendors as $vendor)
                  @if($vendor->Supp_ID == ($part->vendor_id ? $part->vendor_id : ($part->itemMaster() ? $part->itemMaster()->supplier_id : '')))
                  <option class="" value="{{ $vendor->Supp_ID }}" selected>{{ $vendor->VendorName }}</option>
                  @else 
                  <option class="" value="{{ $vendor->Supp_ID }}">{{ $vendor->VendorName }}</option>
                  @endif
                @endforeach
                </select>
              </td>
              <td>
                <input type="text" {{ (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') && $row->purchaseRequest->flag != 1) && ($row->purchaseRequest->flag != 4) && ($row->purchaseRequest->flag != 6) ? '' : 'readonly' }} class="form-control text-center label-table-min qty quantity" name="parts[{{ $row->item_id }}][{{ $part->id }}][qty_to_order]" value="{{ $part->qty_to_order }}" autocomplete="off">
              </td>
              <td>
                <input type="text" {{ (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') && $row->purchaseRequest->flag != 1) && ($row->purchaseRequest->flag != 4) && ($row->purchaseRequest->flag != 6) ? '' : 'readonly' }} class="form-control text-right label-table-min cost quantity cost-mask" name="parts[{{ $row->item_id }}][{{ $part->id  }}][cost]" value="{{ $part->cost ? $part->cost : $part->itemMaster() ? $part->itemMaster()->LastCost : '' }}" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control text-right total" name="" autocomplete="off" readonly>
              </td>
              @if(($purchase->flag != 4) && ($purchase->flag != 6)) 
              <td style="width: 100px;">
                @if(($part->date_verified == NULL) && ($part->isVerified == 2))
                  <button type="button" class="btn btn-info btn-md center-block undoQTY">
                    <i class="fa fa-refresh" aria-hidden="true"></i>
                  </button>
                @elseif($part->isVerified == 1)
                  <button type="button" class="btn btn-info btn-md center-block undoDelete">
                    <i class="fa fa-refresh" aria-hidden="true"></i>
                  </button>
                @else 
                  <button type="button" {{ (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') || \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'D')) ? '' : 'disabled' }} class="btn btn-danger btn-md center-block access_delete" data-toggle="modal" data-target="#myModal">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                @endif
              </td>
              @endif
            </tr>
            @php 
              $index --;
            @endphp
            @endforeach
        @elseif($purchase->eqp_prt == 'Part')
          @php
            $hdrModel = new \App\Models\Equip\Hdr;
            $hdrs = $hdrModel->orderBy('asset_id')->get();
            $vendorModel = new \App\Vendor;
            $vendors = $vendorModel->orderBy('VendorName','asc')->get();
          @endphp
          <tr class="purchaseRow">
            <td class="text-center">
            @if($row->isVerified == 2 || $row->isVerified == 3 )
              <i class="fas fa-exclamation-triangle dropDown">
                <div class="menuDropDown">
                  <strong class="title">Quantity  Changed</strong>
                    <p class="item">from {{ $row->qty_old }} to {{ $row->qty_to_order }}</p>
                  @if($row->date_verified)
                    <p class="item" style="color:red">Verified  {{ $row->date_verified }}</p>
                  @endif
                </div>
              </i>
            @elseif($row->isVerified == 1)
              <i class="fas fa-exclamation-triangle dropDown">
                <div class="menuDropDown">
                  <strong class="title">Item Deleted</strong>
                  <p class="item">{{ $row->remark }}</p>
                </div>
              </i>
            @endif 
              <label class="label-table-min index">{{ $a++ }}</label>
              <input type="hidden" name="parts[{{ $loop->index+1 }}][part_id]" value="{{ $row->id }}">
            </td>
            <td class="text-center">
              <label class="label-table-min index">{{ $row->itemMaster() ? $row->itemMaster()->description : '' }}</label>
            </td>
            
            <td class="text-center"><label for=""></label>
            </td>
            
            <td>
              <select name="parts[{{ $loop->index+1 }}][vendor_id]" class="form-control " {{ ( \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') && $row->purchaseRequest->flag != 1) && ($row->purchaseRequest->flag != 4) && ($row->purchaseRequest->flag != 6) ? '' : 'disabled' }} > 
              <option class="" value="">-- select --</option>
              @foreach($vendors as $vendor)
                @if($vendor->Supp_ID == ( $row->vendor_id ? $row->vendor_id : ($row->itemMaster() ? $row->itemMaster()->supplier_id : '')))
                <option class="" value="{{ $vendor->Supp_ID }}" selected>{{ $vendor->VendorName }}</option>
                @else 
                <option class="" value="{{ $vendor->Supp_ID }}">{{ $vendor->VendorName }}</option>
                @endif
              @endforeach      
              </select>
            </td>
            <td>
              <input type="text" {{ ( \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') && $row->purchaseRequest->flag != 1) && ($row->purchaseRequest->flag != 4) && ($row->purchaseRequest->flag != 6) ? '' : 'readonly' }} class="form-control text-center label-table-min qty quantity" name="parts[{{ $loop->index+1 }}][qty_to_order]" value="{{ $row->qty_to_order }}" autocomplete="off" >
            </td>
            <td class="text-right">
              <input type="text" {{ ( \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') && $row->purchaseRequest->flag != 1) && ($row->purchaseRequest->flag != 4) && ($row->purchaseRequest->flag != 6) ? '' : 'readonly' }} class="form-control text-right label-table-min cost quantity cost-mask" name="parts[{{ $loop->index+1 }}][cost]" value="{{ $row->cost ? $row->cost : $row->itemMaster() ? $row->itemMaster()->LastCost : ''}}" autocomplete="off">
            </td>
            <td class="text-right">
              <input type="text" class="form-control text-right total" name="" autocomplete="off" readonly>
            </td>
            @if(($purchase->flag != 4) && ($purchase->flag != 6)) 
            <td style="width: 100px;">
              @if($row->isVerified == 2)
                <button type="button" class="btn btn-info btn-md center-block undoQTY">
                  <i class="fa fa-refresh" aria-hidden="true"></i>
                </button>
              @elseif($row->isVerified == 1)
                <button type="button" class="btn btn-info btn-md center-block undoDelete">
                <i class="fa fa-refresh" aria-hidden="true"></i>
                </button>
              @else 
                <button type="button" {{ (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E') || \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'D')) ? '' : 'disabled' }} class="btn btn-danger btn-md center-block access_delete" data-toggle="modal" data-target="#myModal">
                  <i class="fas fa-trash-alt"></i>
                </button>
              @endif
            </td>  
            @endif
        @endif
      @endforeach 
      @endif
    </tbody>
    </tr>
  </table>
</div>
