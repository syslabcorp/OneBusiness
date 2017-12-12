<div id="Modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" id="modal_form" method="POST" action="{{ route('branch_remittances.store') }}" role="form">
        {{ csrf_field() }}
        <input type="hidden" name="collectionId" value="{{$collection->ID}}">
        <input type="hidden" name="corpID" value="{{ $company->corp_id }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Transaction Details</h4>
        </div>
        <div class="modal-body">
        
          <div class="row">
            <div class="col-xs-3 ">
              <p class="text-right">
                <strong>
                  CASHIER
                </strong>
              </p>
              
            </div>
            <div class="col-xs-9">
              <b id="cashier">
              </b>
            </div>

            
          </div>
          <div class="row">
            <div class="col-xs-3 ">
              <p class="text-right">
                <strong>
                  Shift ID
                </strong>
              </p>
              
            </div>
            <div class="col-xs-9">
              <b id="shift_id">
              </b>
              <input type="hidden" id="hidden_shift_id" name="Shift_ID" value="">
            </div>
          </div>
          
          <div class="row">
            <div class="col-xs-3 ">
              <p class="text-right">
                <strong>
                  TOTAL SALES
                </strong>
              </p>
              
            </div>
            <div class="col-xs-9">
              <b id="total_sales">
              </b>
            </div>
          </div>

            <div class="row">
              <div class="col-xs-3 ">
              <p class="text-right">
                <strong>
                  TOTAL SHORTAGE  
                </strong>
              </p>
              
              </div>
            <div class="col-xs-9">
              <b id="total_shortage">
              </b>
            </div>
          </div>

            
          <div class="form-group">
            <div class="row">
              <label  class="col-sm-3 control-label">TOTAL REMITTANCE</label>
              <div class="col-xs-9">
                  <input type="number" id="total_remittance" name="TotalRemit" class="form-control" value=""/>
              </div>
            </div>
            
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-3">
                <div class="checkbox">
                  <label for="counterchecker">
                    <input type="checkbox" id="counterchecker"  value="1" name="" id="">
                    Counterchecked
                  </label>
                </div>
              </div>
            </div>
            
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-3">
                <div class="checkbox">
                  <label for="wrong_input">
                    <input type="checkbox" id="wrong_input" name="Wrong_Input" id="" value="1" >
                    Wrong Input
                  </label>
                </div>
              </div>
            </div>
            
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-xs-3">
                  <div class="checkbox">
                    <label for="adj_short">
                      <input type="checkbox" id="adj_short"  value="1" name="Adj_Short" >
                      Adjust Shortage
                    </label>
                  </div>
                </div>

                <div class="col-xs-9">
                  <input type="text" id="shortage" name="Adj_Amt" class="form-control">
                </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <label for="" class="col-xs-2">REMARKS</label>
              <div class="col-xs-10">
                <textarea id="remarks" name="Notes" class="form-control" rows="10">
                </textarea>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn pull-left btn-default" data-dismiss="modal">
            <i class="fa fa-reply">Back</i>
          </button>

          <button id="save_button" class="btn btn-primary">Save</button>
        </div>
      </form>
      
    </div>

  </div>
</div>