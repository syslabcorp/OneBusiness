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
									<form class="form form-horizontal" action="{{ route('purchase_request.update', [$purchase->id,'corpID' => request()->corpID]) }}" method="POST">
      								<input type="hidden" name="_method" value="PUT">
											{{ csrf_field() }}
											<div class="rown">
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>P.R # :</strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<label for="" class="form-control">{{ $purchase->id ? $purchase->id : $user_id }}</label>
																<input type="hidden" class="form-control" name="requester_id" value="{{ $purchase->id ? $purchase->id : $user_id }}">
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Description :</strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<label for="" class="form-control">{{ $purchase->description ? $purchase->description : ''}}</label>
															</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Branch :</strong></label>
															</div>
															<div class="col-sm-5 form-group">
																<label for="" class="form-control">{{ $purchase->branch }}</label>
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Date Request :</strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<label for="" class="form-control">{{ date('Y-m-d') }}</label>
															</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>JO# :</strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<label for="" class="form-control"></label>
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Total Cost :</strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<label for="" class="form-control"></label>
															</div>
													</div>
												</div>
										</div>
										<hr>
										<h4></h4>
										<p>
										</p>
										@include('purchases.purchaser')
										<div class="rown">
											<div class="col-xs-6">
												<a class="btn btn-default" href="{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}">Back</a>
											</div>
											<div class="col-xs-6 text-right">
												<button class="btn btn-danger btn-save" name="disapproved" value="1">Disapprove Request</button>
												<button type="button" class="btn btn-primary btn-save" name="mark" value="1">Mark for PO</button>
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