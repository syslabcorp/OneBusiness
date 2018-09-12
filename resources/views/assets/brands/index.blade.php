@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5>Equipment Brands</h5>
              </div>
              <div class="col-xs-3 text-right" style="margin-top: 10px;">
                <a href="{{ route('equipments.create', ['corpID' => request()->corpID]) }}">Add Brand</a>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="tablescroll">
              <div class="table-responsive">
                <table class="stripe table table-bordered nowrap table-brands" width="100%">
                  <thead>
                    <tr>
                      <th>Brand ID</th>
                      <th>Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($brands as $brand)
                    <tr>
                      <td class="text-center">{{ $brand->brand_id }}</td>
                      <td>{{ $brand->description }}</td>
                      <td class="text-center">
                        <button class="btn btn-primary btn-md">
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button onclick="deleteBrand({{ $brand->brand_id }}, '{{ $brand->description }}')"  class="btn btn-danger btn-md">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('pageJS')
  <script type="text/javascript">
    (()=> { 
      $('.table-brands').dataTable()

      deleteBrand = (id, desc) => {
        showConfirmMessage(
          'Are you sure you want to delete <strong>' + id + ' - ' + desc + '</strong>'   , 'Delete', () => {
            console.log(1)
          }
        )
      }
    })()
  </script>
@endsection