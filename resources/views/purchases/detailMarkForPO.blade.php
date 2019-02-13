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
										@include('purchases.purchaserMarkForPO')
										<div class="rown">
											<div class="col-xs-6">
												<a class="btn btn-default" href="{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}">Back</a>
											</div>
											@if(($purchase->flag != 4) && ($purchase->flag != 6)) 
											<div class="col-xs-6 text-right">
											@if (count($purchase->request_details->whereIn('isVerified', [1,2])->all()) == 0)
												<button type="button"class="btn btn-danger access_mark" data-toggle="modal" data-target="#lewit">Disapprove Request</button>
												<button type="button" class="btn btn-primary access_mark" name="mark" data-toggle="modal" data-target="#lewit1">Mark for PO</button>
											@else
												<button class="btn btn-danger " disabled>Disapprove Request</button>
												<button class="btn btn-primary for-verification" name="verification" value="for_verify">For Verification</button>
											@endif
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
										<div class="modal fade" id="lewit" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Disapprove PR#[<label for="" class="pr_id"></label>]</h4>
													</div>
													<div class="modal-body">
														<p>Reason: </p>
														<textarea name="remarks" id="" cols="30" rows="2" class="form-control reasons" placeholder="TEST NOT HERE"></textarea>
													</div>
													<div class="modal-footer">
														<div class="rown">
															<div class="col-xs-1">
																<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
															</div>
															<div class="col-xs-11 text-right">
																<button type="button" class="btn btn-danger  disapproved" data-dismiss="modal" name="dissaproved_PR" value="dissaproved">Disapprove</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal fade" id="lewit1" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Approve PR#[<label for="" class="approve_id"></label>]</h4>
													</div>
													<div class="modal-body">
														<p>PR# [<label for="" class="approve_id"></label>] is now ready for PO. Proceed for budgeting and PO #?</p>
													</div>
													<div class="modal-footer">
														<div class="rown">
															<div class="col-xs-1">
																<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
															</div>
															<div class="col-xs-11 text-right">
																<button type="button" class="btn btn-primary  btn-markforpo" data-dismiss="modal">Proceed</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											@endif
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