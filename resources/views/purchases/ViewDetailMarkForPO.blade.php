@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5><strong>Purchase Request ( For Verification )</strong></h5>
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
																<label style="padding: 5px;"><strong>P.R # </strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<input type="text" class="form-control" value="{{ $purchase->id ? $purchase->id : $user_id }}" disabled>
																<input type="hidden" class="form-control" name="requester_id" value="{{ $purchase->id ? $purchase->id : $user_id }}">
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Branch </strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<input type="text" class="form-control" value="{{ $purchase->getBranch->ShortName }}" disabled>
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Description </strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<input type="text" class="form-control" value="{{ $purchase->description }}" disabled>
															</div>
													</div>
													@if($purchase->po)
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>PO# </strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<input type="text" class="form-control" value="{{ $purchase->po }}" disabled>
															</div>
													</div>
													@endif
												</div>
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Request Type </strong></label>
															</div>
															<div class="col-sm-5 form-group">
																<input type="text" class="form-control" value="{{ $purchase->eqp_prt }}" disabled>
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Date Requested </strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<input type="text" class="form-control" value="{{ date('Y-m-d') }}" disabled>
															</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>JO# </strong></label>
															</div>
															<div class="col-sm-4 form-group">
															@if(!$purchase->job_order)
																<label for="" style="color:#8dd0f6;padding: 5px;">00000</label>
															@else
																<a href="" style="color:#8dd0f6;padding: 5px;">{{ $purchase->job_order }}</a>
															@endif
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Total Cost </strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<input type="number" class="form-control sumtotalCost" name="total_qty" value="" readonly>
															</div>
													</div>
												</div>
										</div>
										<hr>
										<h4></h4>
										<p>
										</p>
										@include('purchases.ViewMarkForPO')
										<div class="rown">
											<div class="col-xs-6">
												<a class="btn btn-default" href="{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}">Back</a>
											</div>
										
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