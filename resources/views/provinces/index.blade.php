@extends('layouts.custom') @section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="row">
      <div class="col-md-6 col-xs-6">
        List of Provinces
      </div>
      <div class="col-md-6 col-xs-6 text-right">
        @if(\Auth::user()->checkAccessById(18, "A"))
        <a href="{{ route('provinces.create') }}" class="pull-right">Add Province</a>
        @endif
      </div>
    </div>

  </div>
  <div class="panel-body">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>SNo.</th>
          <th>Province</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($provinces as $province)
        <tr>
          <td>{{ $province->Prov_ID }}</td>
          <td>{{ $province->Province}}</td>
          <td>
            <a href="{{ URL::to('provinces/view_cities/'.$province->Prov_ID) }}" class="btn btn-success btn-md blue-tooltip" data-title="View"
              data-toggle="tooltip" data-placement="top" title="View Province">
              <span class="far fa-eye"></span>
            </a>

            <a href="{{ route('provinces.edit', $province) }}" class="btn btn-primary btn-md"
              {{ \Auth::user()->checkAccessById(18, 'E') ? '' : 'disabled' }} title="Edit Province">
              <span class="fas fa-pencil-alt"></span>
            </a>
          </td>

        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection