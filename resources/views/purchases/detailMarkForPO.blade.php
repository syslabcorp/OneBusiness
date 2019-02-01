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
																<label for="" class="form-control">{{ $purchase->id ? $purchase->id : $user_id }}</label>
																<input type="hidden" class="form-control" name="requester_id" value="{{ $purchase->id ? $purchase->id : $user_id }}">
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Branch </strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<label for="" class="form-control">{{ $purchase->getBranch->ShortName }}</label>
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Description </strong></label>
															</div>
															<div class="col-sm-9 form-group">
																<label for="" class="form-control">{{ $purchase->description }}</label>
															</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Request Type </strong></label>
															</div>
															<div class="col-sm-5 form-group">
																<label for="" class="form-control">{{ $purchase->eqp_prt }}</label>
															</div>
													</div>
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>Date Requested </strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<label for="" class="form-control">{{ date('Y-m-d') }}</label>
															</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="rown">
															<div class="col-sm-4 form-group">
																<label style="padding: 5px;"><strong>JO# </strong></label>
															</div>
															<div class="col-sm-4 form-group">
																<label for="" class="form-control"></label>
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
											<div class="col-xs-6 text-right">
											@if ($purchase->flag != 5 )
												<button type="button"class="btn btn-danger access_mark" data-toggle="modal" data-target="#lewit">Disapprove Request</button>
												<button type="button" class="btn btn-primary access_mark" name="mark" data-toggle="modal" data-target="#lewit1">Mark for PO</button>
											@else
												<button class="btn btn-danger " disabled>Disapprove Request</button>
												<button type="button" class="btn btn-primary">For Verification</button>
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
														<textarea name="" id="" cols="30" rows="2" class="form-control reasons" placeholder="TEST NOT HERE"></textarea>
													</div>
													<div class="modal-footer">
														<div class="rown">
															<div class="col-xs-1">
																<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
															</div>
															<div class="col-xs-11 text-right">
																<button type="button" class="btn btn-danger  disapproved" data-dismiss="modal">Disapprove</button>
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