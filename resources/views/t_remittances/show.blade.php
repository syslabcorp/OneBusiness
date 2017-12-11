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
              @include("t_remittances/icare") 
            @elseif( $company->corp_type == "INN" )
              @include("t_remittances/inn")
            @endif
            
            {{--  @include("t_remittances/modal")   --}}
            @include("t_remittances/footer")
              
            <div class="row">
              <div class="pull-right col-md-3">
                <button   class="btn btn-primary">Check Ok <br> Selection</button>
                <button  class="btn btn-success">Save Ok <br> Selection</button>
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