@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>Stock Receiving</h4>
                
              </div>
              <div class="col-xs-3">

              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">

            <form class="form-horizontal" action="{{ route('stocks.store', ['corpID' => $corpID] ) }}" method="POST" >
              {{ csrf_field() }}
              <div class="form-group">
                <label class="control-label col-sm-2" for="email">D.R.#</label>
                <div class="col-sm-10">
                  <input type="text" name="RR_No" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Rcv Date</label>
                <div class="col-sm-10">
                  <input type="date" name="RcvDate" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Total Amount</label>
                <div class="col-sm-10">
                  <input type="text" name="TotalAmt" class="form-control" >
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Vendor Name</label>
                <div class="col-sm-10">
                  <select name="Supp_ID" id="">
                    @foreach($vendors as $vendor)
                      <option value="{{$vendor->Supp_ID}}">{{$vendor->VendorName}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group"> 
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-default">Submit</button>
                </div>
              </div>
            </form>  


            <div class="row">
              <div class="col-md-6">
                <a type="button" class="btn btn-default" href="{{ URL::previous() }}">
                  <i class="fa fa-reply"></i> Back
                </a>
              </div>
              <div class="col-md-6">
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</section>
@endsection
