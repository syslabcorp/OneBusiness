@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>Stock Receiving</h4>
                
              </div>
              <div class="col-xs-3">
                @if(Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A'))
                  <a href="{{route('stocks.create' , ['corpID' => $corpID] )}}" class="pull-right">Add Stock</a>
                @endif
              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-parts" id="stocks_table">
                <thead>
                  @if( true )
                    <tr>
                      <th>
                          <strong>SRR #</strong>
                        </a>
                      </th>
                      <th>
                          <strong>D.R.#</strong>
                        </a>
                      </th>
                      <th>
                          <strong>Date</strong>
                        </a>
                      </th>
                      <th>
                          <strong>Total Amount</strong>
                        </a>
                      </th>
                      <th>
                          <strong>Vendor Name</strong>
                        </a>
                      </th>
                      <th>
                          <strong>Date Saved</strong>
                        </a>
                      </th>
                      <th><strong>Action</strong></th>
                    </tr>
                  @else


                  @endif
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal confirm detele -->
    
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                  <strong>Confirm Delete</strong>
                </h4>
            </div>
        
            <div class="modal-body" style="margin-bottom: 150px; margin-top: 50px;">
                <p>You are sure you want to delete <strong>DR #</strong><strong id="dr"></strong> </p>
            </div>
            
            <div class="modal-footer">
              <div class="col-md-6">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                  <i class="fa fa-reply"></i> Back  
                </button>
              </div>
              <div class="col-md-6">
                <form action="" class="btn-ok" method="POST">
                  {{ csrf_field() }}
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-danger btn-ok" type="submit">Delete</button>
                </form>
              </div>
            </div>
        </div>
      </div>
    </div>

    <!-- Modal alert -->

    <div class="modal fade" id="alert" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
              <strong>Delete DR #<span id="alert-dr"></span></strong>
            </h4>
          </div>
          <div class="modal-body">
            <p>Some or all of the items on this DR have been transferred already. You cannot delete this anymore...</p>
          </div>
          <div class="modal-footer" style="margin-top: 100px;">
            <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>



</section>

@endsection

@section('pageJS')
  <script>
    let baseAPI = '{{ route('api.stocks.index', ["corpID" => request()->corpID]) }}'

    let tablePart = $('.table-parts').DataTable({
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#customFilter">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: ()  => {
        $("#customFilter").append('<div class="col-sm-12" style="margin: 15px 0px;"> \
          Filter: \
          <label style="font-weight: normal;"><input checked name="document-filter" value="all" type="radio" /> All Vendors </label> \
          <label style="font-weight: normal;padding-left: 30px;"><input name="document-filter" value="by" type="radio" /> Vendor </label> \
          <select disabled class="form-control vendor-select" style="width: 350px;"></select> \
        </div>')

        @foreach($vendors as $vendor)
          $('.vendor-select').append(
          '<option value="{{$vendor->Supp_ID}}">{{$vendor->VendorName}}</option>'
          )
        @endforeach

        if (localStorage.getItem('stocksFilter')) {
          $('input[name="document-filter"][value="' + localStorage.getItem('stocksFilter') + '"]').prop('checked', true)
          $('input[name="document-filter"][value="' + localStorage.getItem('stocksFilter') + '"]').change();
        }
      },
      ajax: '{{ route('api.stocks.index', ["corpID" => request()->corpID]) }}',
      columns: [
        {
          targets: 0,
          data: "txn_no"
        },
        {
          targets: 1,
          data: "RR_No"
        },
        {
          targets: 2,
          data: "RcvDate"
        },
        {
          targets: 3,
          data: "TotalAmt",
          class: "text-right"
        },
        {
          targets: 4,
          data: "VendorName"
        },
        {
          targets: 5,
          data: "DateSaved"
        },
        {
          targets: 6,
          class: 'text-center',
          render: (data, type, row, meta) => {
              return '<a  class="btn bt-mdn btn-success fas fa-eye {{ \Auth::user()->checkAccessById(35, 'V') ? '' : 'disabled' }}" \
              href="{{ route('stocks.index') }}/' + row.txn_no + '?corpID={{ request()->corpID }}"> </a> \
              <button onclick="removeStock(' + row.txn_no +')" {{ \Auth::user()->checkAccessById(35, 'D') ? '' : 'disabled' }}\
              class="btn btn-md btn-danger fas fa-trash-alt" data-toggle="modal" data-target=".edit-part-modal"> </button>'
          }
        }
      ]
    })

    removeStock = (itemId) => {
      swal({
        title: "<div class='delete-title'>Delete</div>",
        text:  "<div class='delete-text'>You are about to delete StockID ["+ itemId +"]</strong></div>",
        html:  true,
        customClass: 'swal-wide',
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Delete',
        showCancelButton: true,
        closeOnConfirm: true,
        allowEscapeKey: true
      }, (data) => {
        if(data) {
          $.ajax({
          url: '{{ route('api.stocks.index') }}/' + itemId + '?corpID={{ request()->corpID }}' ,
          type: 'DELETE',
          success: (res) => {
            tableStock.ajax.reload()
          }
        })
        }
      });
    }

    $('body').on('change', 'input[name="document-filter"]', (event) => {
      if (event.target.value == 'all') {
        $('.vendor-select').prop('disabled', true);
        tablePart.ajax.url(baseAPI).load()
      } else {
        $('.vendor-select').prop('disabled', false);

        if (localStorage.getItem('stocksTypeId')) {
          $('.vendor-select').val(localStorage.getItem('stocksTypeId'))
        }

        tablePart.ajax.url(baseAPI + '&vendor_id=' + $('.vendor-select').val()).load()
      }

      localStorage.setItem('stocksFilter', event.target.value)
    })
    
    $('body').on('change', '.vendor-select', (event) => {
      localStorage.setItem('stocksTypeId', event.target.value)

      tablePart.ajax.url(baseAPI + '&vendor_id=' + $('.vendor-select').val()).load()
    })

    $('#confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.btn-ok').attr('action', $(e.relatedTarget).data('href'));
      $('#dr').text( $(e.relatedTarget).data('dr'));
    });

    $('#alert').on('show.bs.modal', function(e) {
      $('#alert-dr').text( $(e.relatedTarget).data('dr'));
    });

  </script>
@endsection