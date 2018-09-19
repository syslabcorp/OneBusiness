@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="rown">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="rown">
                <div class="col-xs-9">
                  <h5>Equipment Brands</h5>
                </div>
                <div class="col-xs-3 text-right" style="margin-top: 10px;">
                  @if(\Auth::user()->checkAccessById(54, 'A'))
                    <a data-toggle="modal" data-target="#addBrandModal" href="javascript:void(0)">Add Brand</a>
                  @endif
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
                    <tr data-id="{{$brand->brand_id}}">
                      <td class="text-center">{{ $brand->brand_id }}</td>
                      <td>{{ $brand->description }}</td>
                      <td class="text-center">
                        <button data-toggle="modal" data-target="#editBrandModal" onclick="editBrand({{ $brand->brand_id }}, '{{ $brand->description }}')" class="btn btn-primary btn-md" {{ \Auth::user()->checkAccessById(54, 'E') ? '' : 'disabled' }}>
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button onclick="deleteBrand({{ $brand->brand_id }}, '{{ $brand->description }}')" class="btn btn-danger btn-md"
                          {{ \Auth::user()->checkAccessById(54, 'D') ? '' : 'disabled' }}>
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
    <!-- Modal add brand-->
    <div class="modal fade" id="addBrandModal" role="dialog">
      <div class="modal-dialog ">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title"><strong>New Equipment Brand</strong></h5>
          </div>
          <div class="modal-body">
            <form action="{{ route('asset-brands.store') }}" method="post" role="form" id="myForm">
              {{ csrf_field() }}
              <div class="form-group">
                <div class="rown">
                  <div class="col-xs-9 col-xs-offset-1">
                      <div class="rown">
                          <div class="col-xs-3" style="padding-top: 9px;">
                            <label for="">Brand:</label>
                          </div>
                          <div class="col-xs-9">
                            <input type="text" class="form-control" name="description" required>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
              <div class="rown">
                <div class="col-xs-6">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fas fa-reply"></i>&nbsp;&nbsp;Back</button>
                </div>
                <div class="col-xs-6 text-right">
                  <button type="submit" class="btn btn-primary">Create</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Modal add brand-->

  <!-- Modal edit brand-->
  <div class="modal fade" id="editBrandModal" role="dialog">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title"><strong>Edit Equipment Brand</strong></h5>
        </div>
        <div class="modal-body">
          <form action="{{ route('asset-brands.index') }}" method="post" role="form" >
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group">
              <div class="rown">
                <div class="col-xs-9 col-xs-offset-1">
                    <div class="rown">
                        <div class="col-xs-3" style="padding-top: 9px;">
                          <label for="">Brand:</label>
                        </div>
                        <div class="col-xs-9">
                          <input type="text" class="form-control" name="description" required>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="rown">
              <div class="col-xs-6">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fas fa-reply"></i>&nbsp;&nbsp;Back</button>
              </div>
              <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!--/ Modal edit brand-->
@endsection

@section('pageJS')
  <script type="text/javascript">
    (()=> {
      let brandsTable = $('.table-brands').dataTable()
      deleteBrand = (id, desc) => {
        showConfirmMessage(
          'Are you sure you want to delete <strong>' + id + ' - ' + desc + '</strong>'   , 'Confirm Delete', () => {
            $.ajax({
              type: 'DELETE',
              url:  '{{ route('asset-brands.index') }}/' + id,
              success: (res) => {
                $('.table-brands tbody tr[data-id="' + id + '"]' ).remove()
              }
            })
        })
      }

      editBrand = (id, desc) => {
        $('#editBrandModal input[name="description"]').val(desc)
        $('#editBrandModal form').attr("action",'{{ route('asset-brands.index') }}/' + id)
      }
    })()

  </script>

@endsection