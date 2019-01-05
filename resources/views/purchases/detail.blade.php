
<div class="" id="equipDetail">
  <div class="row">
  
    <form class="form form-horizontal" action="{{ route('purchases.update', ['corpID' => request()->corpID]) }}" method="POST">
      <input type="hidden" name="_method" value="PUT">

    <form class="form form-horizontal" action="{{ route('purchases.store', ['corpID' => request()->corpID]) }}" method="POST">

      {{ csrf_field() }}
      <div class="rown">
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Requester :</strong></label>
            </div>
            <div class="col-sm-9 form-group">
             <input type="text" class="form-control" value="" name="">
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Branch :</strong></label>
            </div>
            <div class="col-sm-9 form-group">
            <?php var_dump($branches);
                  echo "<pre>";
                  ?>
              <select name="type" class="form-control">
              @foreach($branches as $branch)
                <option value="">{{ $branch->Branch }}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Description :</strong></label>
            </div>
            <div class="col-sm-9 form-group {{ $errors->has('description') ? 'has-error' : ''}}">
              <input type="text" class="form-control" name="description" 
                value="">
                <span class="help-block"></span>
            </div>
          </div>
          
        </div>
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Date Request :</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <input type="text" class="form-control" name="" value="">
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Qty :</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <input type="number" class="form-control sumtotal" readonly>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <h4>Purchases Information</h4>

      <p>
        No parts yet. 
        <a href="javascript:void(0)" class="addHere" onclick="openTablePurchase(event)" style="">
          Add here
        </a>
      </p>
   
      @include('purchases.purchases')
      <div class="rown">
        <div class="col-xs-6">
          <a class="btn btn-default" href="{{ route('purchases.index', ['corpID' => request()->corpID]) }}">Back</a>
        </div>
        <div class="col-xs-6 text-right">
  
            <button type="button" class="btn btn-edit btn-info">
              <i class="fas fa-pencil-alt"></i> Edit
            </button>
            <button style="display: none;" class="btn btn-success btn-save"><i class="far fa-save"></i> Save</button>

            <button class="btn btn-success btn-save" >
              <i class="far fa-save"></i> Create
            </button>
  
        </div>
      </div>
    </form>
  </div>
</div>
      