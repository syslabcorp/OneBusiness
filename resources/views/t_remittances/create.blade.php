@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>BRANCH LIST</h4>
                
              </div>
              <div class="col-xs-3">
              <form class="pull-right" method="GET">
                <select name="status" class="form-control" >
                  <option value="checked">Active</option>
                  <option value="unchecked">Unchecked</option>
                </select>
            </form>
              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row">
              
            </div>
            <form class="form-horizontal" id="brach_remittance_create" action="/branch_remittances/create" method="GET" >
            
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="" class="control-lable col-md-4">Remmit Group:</label>
                  <div class="col-md-8">
                    <select name="remit_group" id="remit_group" class="form-control">
                      @foreach($remit_groups as $remit_group)
                        @if($remittance_group->group_ID == $remit_group->group_ID)
                          <option selected value="{{$remit_group->group_ID}}">Remit Group {{ $remit_group->group_ID }}</option>
                        @else
                          <option value="{{$remit_group->group_ID}}">Remit Group {{ $remit_group->group_ID }}</option>
                        @endif
                      @endforeach
                      
                    </select>
                  </div>
                  
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-4">
                  <label for="" class="control-lable col-md-4">City:</label>
                  <div class="col-md-8">
                    <select name="city" id="city" class="form-control">
                      @foreach($cities as $city)
                        @if($city_ID == $city->City_ID)
                          <option selected value="{{$city->City_ID}}">{{$city->City}}</option>
                        @else
                          <option value="{{$city->City_ID}}">{{$city->City}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </form>
              

            <form action="/branch_remittances/collections" method="POST">
              {{csrf_field()}}
              <table class="table table-striped table-bordered">
                  <tbody>
                    <tr>
                      <th class="text-center">Branch Name</th>
                      <th class="text-center">Start CRR</th>
                      <th class="text-center">End CRR</th>
                      <th class="text-center">Total Collection</th>
                    </tr>
                    @php $total = 0; @endphp
                    @foreach($branchs as $branch)
                      <tr>
                        <td class="text-center"> {{$branch->ShortName}} </td>
                        <td class="text-center">
                          @if($branch->remittance_collections()->whereIn('Branch', $brs)->count())
                            {{$branch->remittance_collections()->whereIn('Branch', $brs)->first()->Start_CRR }}
                            <input type="hidden" name="collections[{{$branch->Branch}}][Start_CRR]" value="{{$branch->remittance_collections()->whereIn('Branch', $brs)->first()->Start_CRR }}">
                          @else
                            @if( $branch->remittance_collections->count() )
                              {{ $branch->remittance_collections()->orderBy( 'End_CRR', 'desc')->first()->End_CRR + 1}}
                              <input type="hidden" name="collections[{{$branch->Branch}}][Start_CRR]" value="{{ $branch->remittance_collections()->orderBy( 'End_CRR', 'desc')->first()->End_CRR + 1}}">
                            @else
                              1
                              <input type="hidden" name="collections[{{$branch->Branch}}][Start_CRR]" value="1">
                            @endif
                          @endif
                        </td>
                        <td class="text-center">
                          <input type="hidden" name="Group" value="{{ $remit_group->group_ID}}">
                          <input class="form-control" type="text" 
                          name="collections[{{$branch->Branch}}][End_CRR]" id="" value="{{$branch->remittance_collections()->whereIn('Branch', $brs)->count() ? ($branch->remittance_collections()->whereIn('Branch', $brs)->first()->End_CRR) : "" }}">
                        </td>
                        <td class="text-center">
                          <input class="form-control" type="text" name="collections[{{$branch->Branch}}][Total_Collection]" id="" value="{{$branch->remittance_collections()->whereIn('Branch', $brs)->count() ? ($branch->remittance_collections()->whereIn('Branch', $brs)->first()->Total_Collection) : "" }}">
                        </td>
                        @if($branch->remittance_collections()->whereIn('Branch', $brs)->count())
                          @php $total += $branch->remittance_collections()->whereIn('Branch', $brs)->first()->Total_Collection; @endphp
                        @endif
                      </tr>
                    @endforeach
                  
                  </tbody>
                </table>

                <div class="row">
                  <div class="pull-right">
                    <strong>SUBTOTAL: {{$total}} </strong>
                  </div>
                </div>
                
                <div class="row">
                  <div class=" pull-right" style="margin-top: 20px;">
                    <button class="btn btn-success">Save</button>
                  </div>
                </div>
            </form>
              
              

            <div class="row">
              <button class="btn btn-default">
                <i class="fa fa-reply"></i>
                Back
              </button>
            </div>
          </div>
          
        </div>
      </div>
    </div>
</section>
@endsection