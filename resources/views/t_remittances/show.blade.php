@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>REMITTANCE COUNTERCHECK</h4>
                
              </div>
            </div>
          </div>
          <div class="panel-body" style="margin: 30px 0px;">
            @if ( $company->corp_type == "ICAFE" )
              @include("t_remittances/icafe") 
            @elseif( $company->corp_type == "INN" )
              @include("t_remittances/inn")
            @endif
            
            @include("t_remittances/modal")
              
            <div class="row text-right">
              <div class="pull-right col-md-3">
                <button disabled="true" class="btn btn-primary btn-check-ok {{ \Auth::user()->checkAccessByIdForCorp($company->corp_id, 16, 'E') ? "" : "disabled" }}">
                  Check Ok <br> Selection
                </button>
                <button disabled="true" class="btn btn-success btn-save-ok {{ \Auth::user()->checkAccessByIdForCorp($company->corp_id, 16, 'E') ? "" : "disabled" }}">
                  Save Checked <br>  OK
                </button>
              </div>
            </div>

            <div class="row">
              <a class="btn btn-default" href="{{ route('branch_remittances.index', ['corpID' => $company->corp_id]) }}">
                <i class="fa fa-reply"></i> Back
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection