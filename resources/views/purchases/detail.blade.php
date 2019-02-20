
<div class="" id="equipDetail">
  <div class="row">
    @if($purchase->id)
    <form class="form form-horizontal" action="{{ route('purchase_request.update', [$purchase->id,'corpID' => request()->corpID]) }}" method="POST">
      <input type="hidden" name="_method" value="PUT">
    @else
    <form class="form form-horizontal" action="{{ route('purchase_request.store', ['corpID' => request()->corpID]) }}" method="POST">
    @endif
      {{ csrf_field() }}
      <div class="rown">
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>{{ $purchase->id ? 'P.R #' : 'Requester' }} </strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="text" class="form-control" value="{{ $purchase->id ? $purchase->id : \Auth::user()->UserName }}" disabled >
              <input type="hidden" class="form-control" name="requester_id" value="{{ $purchase->id ? $purchase->id : \Auth::user()->UserID }}">
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Branch </strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <select name="branch" class="form-control" {{ $purchase->id ? 'disabled' : '' }}>
              @foreach($branches as $branch)
              @if($branch->Branch == $purchase->branch)
                <option value="{{ $branch->Branch }}" selected>{{ $branch->ShortName }}</option>
              @else
                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
              @endif
              @endforeach
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Description </strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="text" class="form-control" name="description" value="{{ $purchase->description ? $purchase->description : ''}}" {{ $purchase->id ? 'disabled' : '' }}>
            </div>
          </div>
        
          <div class="rown">
            <div class="col-sm-3 form-group text-right" style="margin: 0px;">
              <label><strong>Request for </strong></label>
            </div>
            <div class="form-group">
              <input type="radio" class="form-check-input" name="eqp_prt" value="Equipment" {{ $purchase->eqp_prt == 'Equipment' ? 'checked' : '' }} {{ $purchase->id ? 'disabled' : '' }}>  Equipment
              <input type="radio" class="form-check-input" name="eqp_prt" value="Part" {{ $purchase->eqp_prt == 'Part' ? 'checked' : '' }} {{ $purchase->id ? 'disabled' : '' }}> Parts              
            </div>
          </div>
       
        </div>
        <div class="col-sm-6">
          @if($purchase->id)
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Request Type </strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <input type="text" class="form-control" name="date" value="{{ $purchase->eqp_prt }}" {{ $purchase->id ? 'disabled' : '' }}> 
            </div>
          </div>
          @endif
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Date Requested </strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <input type="text" class="form-control" name="date" value="{{ date('Y-m-d') }}" {{ $purchase->id ? 'disabled' : '' }} readonly> 
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Qty </strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <input type="number" class="form-control sumtotal" name="total_qty" value="{{ $purchase->total_qty ? $purchase->total_qty : ''}}" readonly>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>JO# </strong></label>
            </div>
            <div class="col-sm-7 form-group">
            @if(!$purchase->job_order)
              <label for="" style="color:#8dd0f6;padding: 5px;">00000</label>
            @else
              <a href="" style="color:#8dd0f6;padding: 5px;">{{ $purchase->job_order }}</a>
            @endif
            </div>
          </div>
        </div>
         
      </div>
      <hr>
      @include('purchases.purchases')
      <div class="rown">
        <div class="col-xs-6">
          <a class="btn btn-default" href="{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}">Back</a>
        </div>
        <div class="col-xs-6 text-right before_edt" >
          @if($purchase->id)
          <button type="button" class="btn btn-info {{ $purchase->status ? 'edit_verify' : 'edit' }} {{ \Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'E') ? '' : 'disabled' }}" >
            <i class="far fa-save"></i> Edit PR
          </button>
          @else 
          <button type="button" class="btn btn-primary btn-save" >
            <i class="far fa-save"></i> Create P.R.
          </button>
          @endif
        </div>
        <div class="col-xs-6 text-right after_edit" style="visibility:hidden">
          <button type="button" class="btn btn-primary btn-update" value="" name="updated">
            <i class="far fa-save"></i> Update PR
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
      