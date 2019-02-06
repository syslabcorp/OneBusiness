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
											<div class="col-sm-6">
												<div class="rown">
													<div class="col-sm-3 form-group text-right">
														<label style="padding: 5px;"><strong>P.R#  </strong></label>
													</div>
													<div class="col-sm-9 form-group">
														<input type="text" class="form-control" value="{{ $purchase->id ? $purchase->user->UserName : \Auth::user()->UserName }}" {{ $purchase->id ? 'readonly' : '' }}>
														<input type="hidden" class="form-control" name="requester_id" value="{{ $purchase->id ? $purchase->id : \Auth::user()->UserID }}" {{ $purchase->id ? 'readonly' : '' }}>
													</div>
												</div>
												<div class="rown">
													<div class="col-sm-3 form-group text-right">
														<label style="padding: 5px;"><strong>Branch </strong></label>
													</div>
													<div class="col-sm-9 form-group">
														<select name="branch" class="form-control" {{ $purchase->id ? 'disabled' : '' }}>
														@foreach($branches as $branch)
														{{ $branch->id }}
														@if ($branch->id == $purchase->branch)
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
														<label style="padding: 5px;"><strong>Description :</strong></label>
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
														<input type="radio" class="form-check-input " name="eqp_prt" value="equipment" {{ $purchase->eqp_prt == 'equipment' ? 'checked' : '' }} {{ $purchase->id ? 'disabled' : '' }}>  Equipment
														<input type="radio" class="form-check-input " name="eqp_prt" value="parts" {{ $purchase->eqp_prt == 'parts' ? 'checked' : '' }} {{ $purchase->id ? 'disabled' : '' }}> Parts              
													</div>
												</div>
												
											</div>
											<div class="col-sm-6">
												<div class="rown">
													<div class="col-sm-5 form-group text-right">
														<label style="padding: 5px;"><strong>Request Type </strong></label>
													</div>
													<div class="col-sm-7 form-group">
														<input type="text" class="form-control" name="date" value="{{ $purchase->eqp_prt }}" {{ $purchase->id ? 'disabled' : '' }}> 
													</div>
												</div>
												<div class="rown">
													<div class="col-sm-5 form-group text-right">
														<label style="padding: 5px;"><strong>Date Requested :</strong></label>
													</div>
													<div class="col-sm-7 form-group">
														<input type="text" class="form-control" name="date" value="{{ date('Y-m-d') }}" {{ $purchase->id ? 'disabled' : '' }}> 
													</div>
												</div>
												<div class="rown">
													<div class="col-sm-5 form-group text-right">
														<label style="padding: 5px;"><strong>Qty :</strong></label>
													</div>
													<div class="col-sm-7 form-group">
														<input type="number" class="form-control sumtotal" name="total_qty" value="{{ $purchase->total_qty ? $purchase->total_qty : ''}}" readonly>
													</div>
												</div>
												<div class="rown">
													<div class="col-sm-5 form-group text-right">
														<label style="padding: 5px;"><strong>JO# :</strong></label>
													</div>
													<div class="col-sm-7 form-group">
														<label for="" style="color:#8dd0f6;padding: 5px;">00000</label>
													</div>
												</div>
											</div>
										</div>
										<div class="modal fade" id="myModal" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Delete item from PR#[<label for="" class="index_pr"></label><input type="hidden" class="pr_id" >]</h4>
													</div>
													<div class="modal-body">
														<p>Reason: </p>
														<textarea name="" id="" cols="30" rows="2" class="form-control reason" placeholder="TEST NOT HERE"></textarea>
													</div>
													<div class="modal-footer">
														<div class="rown">
															<div class="col-xs-1">
																<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
															</div>
															<div class="col-xs-11 text-right">
																<button type="button" class="btn btn-danger btnRemoveRow  delete_row" data-dismiss="modal">Delete</button>
															</div>
														</div>
													</div>
												</div>
											</div>  
										</div>	
										<hr>
										<h4></h4>

										<p>
									
										</p>
										@include('purchases.purchaseVerify')
										<div class="rown">
											<div class="col-xs-6">
												<a class="btn btn-default" href="{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}">Back</a>
											</div>
											<div class="col-xs-6 text-right">
												<button type="submit" class="btn btn-danger delete_request_verify" value="" name="delete_request">Delete Request</button>
												<button type="button" class="btn btn-primary btn-save" >Verify</button>
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