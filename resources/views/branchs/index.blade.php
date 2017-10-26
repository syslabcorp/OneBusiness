@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-9">
                        <h4>{{ $company ? $company->corp_name : "Branch Lists" }}</h4>
                    </div>
                    <form class="col-xs-3 pull-right" method="GET">
                        <select name="status" class="form-control" id="filter-branchs">
                            <option value="all">All</option>
                            <option {{ $status == "active" ? "selected" : "" }} value="active">Active</option>
                            <option {{ $status == "inactive" ? "selected" : "" }} value="inactive">Inactive</option>
                        </select>
                        @if($company)
                        <input type="hidden" name="corpID" value="{{ $company->corp_id }}" />
                        @endif
                    </form>
                </div>
            </div>
            <div class="panel-body" style="margin: 30px 0px;">
              @if(count($branchs))
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th >Province</th>
                            <th>City</th>
                            <th>Active</th>
                            <th>Branch Name</th>
                            <th>Operator</th>
                            <th>Street</th>
                            <th>Units</th>
                            <th>Action</th>
                        </tr>
                        @foreach($branchs as $province)
                            @php $index = 0; @endphp
                            @foreach($province['cities'] as $city)
                                @foreach($city as $branch)
                                <tr class="text-center">
                                    @if($index == 0)
                                    <td rowspan="{{ $province['count'] }}">{{ $branch->city->province->Province }}</td>
                                    @endif
                                    @if($loop->index == 0)
                                        <td rowspan="{{ count($city) }}">{{ $branch->city->City }}</td>
                                    @endif
                                    <td class="text-center">
                                        <div class="control-checkbox">
                                            <input type="checkbox" {{ $branch->Active == 1 ? 'checked' : ''}} style="pointer-events: none;">
                                            <label>&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>{{ $branch->ShortName }}</td>
                                    <td>{{ $branch->Description }}</td>
                                    <td>{{ $branch->Street }}</td>
                                    <td>{{ $branch->MaxUnits }}</td>
                                    <td>
                                        <a href="{{ route('branchs.edit', [$branch]) }}" style="margin-right: 10px;" class="btn btn-info btn-xs {{ \Auth::user()->checkAccessById(1, "E") ? "" : "disabled" }}"
                                            title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="{{ route('branchs.rates.index', [$branch]) }}" style="margin-right: 10px;" 
                                            class="btn btn-success btn-xs {{ \Auth::user()->checkAccessById(2, "V") ? "" : "disabled" }}"
                                            title="Rates template and scheduling">
                                            <i class="fa fa-star"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php $index++; @endphp
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
              @else
                <div class="error">
                    {{ __('No data to display') }}
                </div>
              @endif
              <div class="text-left">
                <a href="/OneBusiness/home" class="btn btn-default">
                  <i class="fa fa-reply"></i> Back
                </a>
                @if(\Auth::user()->checkAccessById(1, "A"))
                  <a href="{{ route('branchs.create', ['corpID' => $corpId]) }}" class="btn btn-success">New Branch</a>
                @endif
              </div>
            </div>
            
          </div>
        </div>
      </div>
</section>
@endsection