@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>Edit Transaction: {{ $collection->ID }}</h4>
              </div>
              <div class="col-xs-3">
              <form class="pull-right form-status" method="GET">
                <select class="form-control" >
                  <option value="1">Active</option>
                  <option value="0" {{ $selectStatus == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
              </form>
              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row">
            </div>
            <form class="form-horizontal" id="brach_remittance_create" action="{{ route('branch_remittances.edit',[$collection, 'corpID' => $corpID]) }}" method="GET" >
              <input type="hidden" name="corpID" value={{$corpID}}>
              <input type="hidden" name="groupStatus" value="1" />
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="" class="control-lable col-md-4">Remit Group:</label>
                  <div class="col-md-8">
                    <select {{ $selectGroup ? "" : "readonly" }} name="groupId" id="remit_group" class="form-control">
                      @foreach($remitGroups as $group)
                        <option value="{{$group->group_ID}}" 
                        {{ $selectGroup->group_ID == $group->group_ID ? 'selected' : '' }}
                          >{{ $group->desc }}</option>
                      @endforeach
                      
                    </select>
                  </div>
                  
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-4">
                  <label for="" class="control-lable col-md-4">City:</label>
                  <div class="col-md-8">
                    <select {{ $selectGroup ? "" : "disabled" }} name="cityId" id="city" class="form-control">
                      @foreach($cities as $city)
                        @if($selectCity && $selectCity->City_ID == $city->City_ID)
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
            
            @if($selectGroup)
            <form action="{{ route('branch_remittances.update', [$collection, 'corpID' => $corpID, 'cityId' => $selectCity->City_ID, 'groupId' => $selectGroup->group_ID]) }}" method="POST" id="remittance-collection-create">
              {{csrf_field()}}
              <input type="hidden" name="_method" value="PUT" />
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Branch Name</th>
                      <th>Start CRR</th>
                      <th>End CRR</th>
                      <th>Total Collection</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $total = 0; @endphp
                    @foreach($branchs as $branch)
                      @php $detail = $collection->detail($selectGroup->group_ID, $branch->Branch) @endphp
                      <tr>
                        <td>
                          {{ $branch->ShortName }}
                          <input type="hidden" name="collections[{{$branch->Branch}}][ID]" 
                            value="{{ $detail->ID }}"/>
                          <input type="hidden" name="collections[{{$branch->Branch}}][Group]" 
                            value="{{ $detail->Group }}"/>
                          <input type="hidden" name="collections[{{$branch->Branch}}][Branch]" 
                            value="{{ $detail->Branch }}"/>
                        </td>
                        <td>
                          <input type="text" name="collections[{{$branch->Branch}}][Start_CRR]" value="{{ $collection->getStartCRR($selectGroup->group_ID, $branch->Branch, $corpID) }}"
                            readonly="true" class="form-control">
                        </td>
                        <td>
                          <input class="form-control" type="text" name="collections[{{$branch->Branch}}][End_CRR]"
                            value="{{ !empty(old("collections.{$branch->Branch}.End_CRR")) ? old("collections.{$branch->Branch}.End_CRR") : $detail->End_CRR }}">
                          @if($errors->has("collections.{$branch->Branch}.End_CRR"))
                            <i style="color:#cc0000;">{{ $errors->first("collections.{$branch->Branch}.End_CRR") }}</i>
                          @endif
                        </td>
                        <td>
                          <input class="form-control collection" type="text" name="collections[{{$branch->Branch}}][Collection]"
                            value="{{ !empty(old("collections.{$branch->Branch}.Collection")) ? old("collections.{$branch->Branch}.Collection") : $detail->Collection }}">
                          @if($errors->has("collections.{$branch->Branch}.Collection"))
                            <i style="color:#cc0000;">{{ $errors->first("collections.{$branch->Branch}.Collection") }}</i>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                    @if(count($branchs) == 0)
                      <tr>
                        <td colspan="4">
                          Not found any collections
                        </td>
                      </tr>
                    @endif
                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="pull-right">
                    <strong>SUBTOTAL: <span class="subtotal">{{ $collection->Subtotal }}</span> </strong>
                  </div>
                </div>
                @if(count($branchs))
                <div class="row">
                  <div class=" pull-right" style="margin-top: 20px;">
                    <button class="btn btn-success">Save</button>
                  </div>
                </div>
                @endif
            </form>
            @else
            <div class="error">
              You don't have assigned any groups
            </div>
            @endif

            <div class="row">
              <a class="btn btn-default" href="{{ route('branch_remittances.index', $queries) }}">
                <i class="fa fa-reply"></i>
                Back
              </a>
            </div>
          </div>
          
        </div>
      </div>
    </div>
</section>
@endsection

@section('pageJS')
<script type="text/javascript">
  $(document).ready(function() {
    $('#remittance-collection-create .collection').change(function(event) {
      var subTotal = 0;
      $('#remittance-collection-create .collection').each(function(el, index) {
        if($(this).val()) {
          subTotal += parseInt($(this).val());
        }
      });
      console.log(subTotal);
      $('#remittance-collection-create .subtotal').text(subTotal);
    });
  });
</script>
@endsection