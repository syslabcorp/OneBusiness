
<div class="" id="equipDetail">
  <div class="row">

    <!-- <form class="form form-horizontal" action="{{ route('purchase_request.update', ['corpID' => request()->corpID]) }}" method="POST">
      <input type="hidden" name="_method" value="PUT"> -->

    <form class="form form-horizontal" action="{{ route('purchase_request.store', ['corpID' => request()->corpID]) }}" method="POST">

      {{ csrf_field() }}
      <div class="rown">
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Requester :</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="text" class="form-control" name="requester_id" value="{{ $purchase->id ? $purchase->id : $user_id }}" {{ $purchase->id ? 'disabled' : '' }}>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Branch :</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <select name="branch" class="form-control" {{ $purchase->id ? 'disabled' : '' }}>
              @foreach($branches as $branch)
              @if ($purchase->branch))
                <option value="{{ $branch->Branch }}">{{ $branch->Description }}</option>
              @else
                <option value="{{ $branch->Branch }}">{{ $branch->Description }}</option>
              @endif
              @endforeach
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Description :</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="text" class="form-control" name="description" value="" {{ $purchase->id ? 'disabled' : '' }}>
            </div>
          </div>
          
        </div>
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Date Request :</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <input type="text" class="form-control date-mask" name="date" {{ $purchase->id ? 'disabled' : '' }}> 
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Qty :</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <input type="number" class="form-control sumtotal" name="total_qty" readonly>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <h4>Purchases Information</h4>

      <p>
        No purchases yet. 
        <a href="javascript:void(0)" class="addHere" onclick="openTablePurchase(event)" style="">
          Add here
        </a>
      </p>
   
      @include('purchases.purchases')
      <div class="rown">
        <div class="col-xs-6">
          <a class="btn btn-default" href="{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}">Back</a>
        </div>
        <div class="col-xs-6 text-right">
  
            <button type="button" class="btn btn-edit btn-info">
              <i class="fas fa-pencil-alt"></i> Edit
            </button>
            <button style="display: none;" class="btn btn-success btn-save"><i class="far fa-save"></i> Save</button>

            <button type="button" class="btn btn-primary btn-save" >
              <i class="far fa-save"></i> Create P.R.
            </button>
  
        </div>
      </div>
    </form>
  </div>
</div>
      