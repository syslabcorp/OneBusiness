@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5><strong>Purchase Request</strong></h5>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <div class="tab-content" style="padding: 1em;">
                
								<div class="" id="equipDetail">
									<div class="row">

										<form class="form form-horizontal" action="{{ route('purchase_request.update', [$purchase, 'corpID' => request()->corpID]) }}" method="POST">
                      <input type="hidden" name="_method" value="PUT">
                      {{ csrf_field() }}
											<div class="rown">
												<div class="col-sm-6">
													<div class="rown">
                            <div class="col-sm-3 form-group text-right">
                              <label style="padding: 5px;"><strong>P.R # :</strong></label>
                            </div>
                            <div class="col-sm-9 form-group">
                              <input type="text" class="form-control" name="requester_id" value="{{ $purchase->id }}" disabled>
                            </div>
													</div>
													<div class="rown">
                            <div class="col-sm-3 form-group text-right">
                              <label style="padding: 5px;"><strong>Description :</strong></label>
                            </div>
                            <div class="col-sm-9 form-group">
                              <input type="text" class="form-control" name="description" value="{{ $purchase->description }}" disabled>
                            </div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-5 form-group text-right">
																<label style="padding: 5px;"><strong>Branch :</strong></label>
															</div>
															<div class="col-sm-5 form-group">
																<input type="text" class="form-control" name="" value="{{ $purchase->branch }}" disabled>
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-5 form-group text-right">
																<label style="padding: 5px;"><strong>Date Request :</strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<input type="text" class="form-control" name="date" value="{{  $purchase->date ?? date('Y-m-d')}}" disabled> 
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-5 form-group text-right">
																<label style="padding: 5px;"><strong>JO# :</strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<input type="text" class="form-control" name="" value="" readonly> 
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
                            <button type="button" class="btn btn-primary btn-save" >Save</button>
                        </div>
                      </div>
										</form>

									</div>
								</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@include('purchases.script')