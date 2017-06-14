@extends('layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Branch Lists</h3>
            </div>
            <div class="box-body">
              @if(count($branchs))
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th >Province</th>
                            <th>City</th>
                            <th>Acitve</th>
                            <th>Branch Name</th>
                            <th>Operator</th>
                            <th>Street</th>
                            <th>Units</th>
                            <th></th>
                        </tr>
                        @foreach($branchs as $province)
                            @php $index = 0; @endphp
                            @foreach($province['cities'] as $city)
                                @foreach($city as $branch)
                                <tr class="text-center">
                                    @if($index == 0)
                                    <td rowspan="{{ $province['count'] }}">{{ $branch->city->province->name }}</td>
                                    @endif
                                    @if($loop->index == 0)
                                        <td rowspan="{{ count($city) }}">{{ $branch->city->name }}</td>
                                    @endif
                                    <td class="text-center">
                                        <div class="control-checkbox">
                                            <input type="checkbox" {{ $branch->active == 1 ? 'checked' : ''}}>
                                            <label>&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>{{ $branch->branch_name }}</td>
                                    <td>{{ $branch->description }}</td>
                                    <td>{{ $branch->street }}</td>
                                    <td>{{ $branch->max_units }}</td>
                                    <td>
                                        <a href="#">Rates template and scheduling</a>
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
                    {{ __('Not found any data to display') }}
                </div>
              @endif
            </div>
            <div class="box-footer">
                <a href="/home" class="btn btn-default">
                    <i class="fa fa-reply"></i> Back
                </a>
                <a href="{{ route('branchs.create') }}" class="btn btn-success">New Branch</a>
            </div>
          </div>
        </div>
      </div>
</section>
@endsection