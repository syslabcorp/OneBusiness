@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>CRR COLLECTION</h4>
                
              </div>
              <div class="col-xs-3">
                <div class="pull-right">
                  <a href="{{ route('branch_remittances.create', ['corpID' => $corpID]) }}">Add Collection</a>
                  
                </div> 
              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row" style="margin-bottom: 20px;">
              <form class="col-xs-3 pull-right" method="GET">
                <select name="status" class="form-control" >
                  <option value="checked">Checked</option>
                  <option value="unchecked">Unchecked</option>
                </select>
              </form>
            </div>
            <table class="table table-striped table-bordered">
              <tbody>
                <tr>
                  <th >TXN No.</th>
                  <th>Date/Time</th>
                  <th>Pick-up Teller</th>
                  <th>Subtotal</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                @foreach($collections as $collection)
                  <tr class="text-center">
                    <td>{{ $collection->ID }}</td>
                    <td>{{ $collection->CreatedAt->format('Y-m-d H:ia') }}</td>
                    <td>{{ $collection->user->UserName }}</td>
                    <td>{{ $collection->Subtotal }}</td>
                    <td>
                      <input type="checkbox" name="status" id="" onclick="return false;" >
                    </td>
                    <td>

                      <a href="{{ route('branch_remittances.show', [$collection, 'corpID' => $corpID]) }}" style="margin-right: 10px;" 
                        class="btn btn-success btn-xs"
                        title="View">
                        <i class="fa fa-eye"></i>
                      </a>

                      <a href="{{ route('branch_remittances.edit', [$collection, 'corpID' => $corpID]) }}" style="margin-right: 10px;" 
                        class="btn btn-primary btn-xs"
                        title="Edit">
                        <i class="fa fa-pencil"></i>
                      </a>

                      <form action="{{ route('branch_remittances.destroy', [$collection, 'corpID' => $corpID]) }}" method="POST"
                        style="display: inline-block;">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button style="margin-right: 10px;" class="btn btn-danger btn-xs"
                          title="Delete" onclick="return confirm('Are you sure you want to delete this collection?')">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
                @if(!$collections->count())
                <tr>
                  <td colspan="6">Not found any collections</td>
                </tr>
                @endif
              </tbody>
            </table>

            <div class="row">
              <div class="col-md-4">
                <form class="" id="date_range" action="{{ route('branch_remittances.index', ['corpID' => $corpID]) }}" method="GET">
                  <input type="hidden" name="corpID" value="{{$corpID}}">
                  <div class="checkbox col-xs-12">
                    <label for="view_date_range" class="control-label">
                      <input type="checkbox" {{$start_date || $end_date ? 'checked': ""}} id="view_date_range" value="1">
                      View by Date Range
                    </label>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xs-6">
                        <input type="date" name="start_date" id="start_date" {{ $start_date || $end_date ? '': 'disabled="true"' }} class="form-control datepicker " value="{{$start_date}}">
                      </div>
                      <div class="col-xs-6">
                        <input type="date" name="end_date" id="end_date"  {{ $start_date || $end_date ? '': 'disabled="true"' }}  class="form-control datepicker"  value="{{$end_date}}">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xs-12">
                        <a href="/OneBusiness/home" class="btn btn-default">
                          <i class="fa fa-reply"></i> Back
                        </a>
                        <button id="button_ranger_date" {{ $start_date || $end_date ? '': 'disabled="true"' }} class="btn btn-primary">Show</button>
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
</section>
@endsection