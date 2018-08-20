@extends('layouts.custom')

@section('header_styles')
  <link href="{{ asset('css/my.css') }}" rel="stylesheet" type="text/css"/>
  <style type="text/css">
    table.dataTable {
      margin-top: 0px !important;
      margin-bottom: 0px !important;
    }
    .branch-filter {
      margin-bottom: 15px;
    }
  </style>
@endsection

@section('content')
  <div class="box-content">
    @if(Session::has('success'))
      <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('success') !!}</em></div>
    @elseif(Session::has('error'))
      <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('error') !!}</em></div>
    @endif
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>Employee Profile</h4>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <div  id="tablescroll" class="tablescroll">
                <div class="table-wrap">
                  <table id="table-deliveries" class="stripe table table-bordered nowrap" width="100%">
                    <thead>
                      <tr>
                        <th style="min-width: 50px">ID</th>
                        <th style="min-width: 70px">Employee Name</th>
                        <th style="min-width: 70px">Address</th>
                        <th style="min-width: 70px">Date of Birth</th>
                        <th style="min-width: 40px">Age</th>
                        <th style="min-width: 30px">Sex</th>
                        <th style="min-width: 30px">Active</th>
                        <th style="min-width: 70px">Branch</th>
                        <th style="min-width: 70px">Department</th>
                        <th style="min-width: 70px">Position</th>
                        <th style="min-width: 70px">Date Hired</th>
                        <th style="min-width: 70px">Base Salary</th>
                        <th style="min-width: 70px">Pay Code</th>
                        <th style="min-width: 90px">SSS#</th>
                        <th style="min-width: 90px">PHIC#</th>
                        <th style="min-width: 90px">HDMF#</th>
                        <th style="min-width: 70px">Account#</th>
                        <th style="min-width: 70px">Type</th>
                      </tr>
                    </thead>
                    <tbody >
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Sort Order</h4>
          </div>
          <div class="modal-body">
            <p><strong>Current Order:</strong class="current_sort"> <span class="current_sort">Branch > Position > Department</span> </p>
            <div id="ListElement">
              <ul id="sortable">

                <li class="ui-state-default">
                  <div class="col-md-2 text-center"><input type="checkbox" name="ReportPriority" value="Profile Author" checked/></div>
                  <div class="col-md-10 sort-item">Branch</div>
                </li>

                <li class="ui-state-default">
                  <div class="col-md-2 text-center"><input type="checkbox" name="ReportPriority" value="Profile Author" checked/></div>
                  <div class="col-md-10 sort-item">Position</div>
                </li>

                <li class="ui-state-default">
                  <div class="col-md-2 text-center"><input type="checkbox" name="ReportPriority" value="Profile Author" checked/></div>
                  <div class="col-md-10 sort-item">Department</div>
                </li>

                <li class="ui-state-default">
                  <div class="col-md-2 text-center"><input type="checkbox" name="ReportPriority" value="Profile Author"/></div>
                  <div class="col-md-10 sort-item">Date Hired</div>
                </li>

                <li class="ui-state-default">
                  <div class="col-md-2 text-center"><input type="checkbox" name="ReportPriority" value="Profile Author"/></div>
                  <div class="col-md-10 sort-item">Name</div>
                </li>

              </ul>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="sort-button" data-dismiss="modal">Sort</button>
          </div>
        </div>

      </div>
    </div>
  </div>

<script src="http://onebusiness.shacknet.biz/OneBusiness/js/table-edits.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/momentjs.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/bootstrap-datetimepicker.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#table-deliveries').DataTable({

      "dom": '<"row"<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f>><"col-md-2 branch-filter"><"col-md-3 empStatus"><"col-md-3 lvl"><"col-md-3 sort"><"col-md-1 o-i">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: function() {
      $(".branch-filter").append('<div class="row"><label class="filterLabel1"> <input value="hasBranch" type="checkbox" class="check-branch" name="selectBranch" /> Select Branch</label></div><div class="row"><select name="branch" class="select-branch form-control"></select></div>')
      $(".branch-filter select").val('{{ $branch }}')
      $('.branch-filter .check-branch').prop('checked', {{$branchSelect ? "true" : "false"}})
      $('.select-branch').prop('disabled', {{$branchSelect ? "false" : "true"}} )

      $('.empStatus').append('<div class="row"><label class="filterLabel1"> Employee Status</label></div><div class="row"><label class="filterLabel1"><input type="radio" value="1" name="status">Active</label><label class="filterLabel1"><input type="radio" value="2" name="status">Inactive</label><label class="filterLabel1"><input type="radio" name="status"  value="all">All</label></div>')
      $(".empStatus input[name=status][value='{{ $status }}']").prop("checked",true)

      $('.lvl').append('<div class="row"><label class="filterLabel1"> Level</label></div><div class="row"><label class="filterLabel1"><input value="non-branch" type="radio" name="level">Non-branch</label><label class="filterLabel1"><input type="radio" value="branch" name="level">Branch</label><label class="filterLabel1"><input type="radio" value="all" name="level">All</label></div>')
      $(".lvl input[name=level][value='{{ $level }}']").prop("checked",true);
      $('.sort').append('<div class="row"><a data-toggle="modal" data-target="#myModal"> Sort Order</a></div><div class="row current_sort">Branch > Position > Department</div>')
      $('.o-i').append('<a href="{{ route('employee.ioPDF', ['corpID' => $corpID, 'status' => 1]) }}" target="_blank" class="btn-io btn btn-primary pull-right">I/O</a>')
      $(".branch-filter select").append("<option class='first-option' value=''></option>")
      @foreach($branches as $branch)
        $(".branch-filter select").append("<option class='value-option' value='{{$branch->Branch}}'>{{$branch->ShortName}}</option>")
      @endforeach
      },
      ajax: '{{ route('employee.deliveryItems', ['corpID' => $corpID, 'branchSelect' => $branchSelect, 'branch' => $branch, 'status' => $status, 'level'=>$level]) }}',
      "fnDrawCallback": () => {
        $('.clone').remove();
      },
      columns: [
        {
          targets: 0,
          data: "UserID",
        },
        {
          targets: 1,
          data: "UserName",
          render: (data, type, row, meta) => {
            return "<a href='{{ route('employee.index') }}/" + row.UserID + "?corpID={{ $corpID }}'>"+ data +"</a>";
          },
        },
        {
          targets: 2,
          data: "Address"
        },
        {
          targets: 3,
          data: "BDay",
          type: 'date-eu'
        },
        {
          targets: 4,
          data: "Age"
        },
        {
          targets: 5,
          data: "Sex"
        },
        {
          targets: 6,
          data: "active",
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
        },
        {
          targets: 7,
          data: "Branch"
        },
        {
          targets: 8,
          data: "Department"
        },
        {
          targets: 9,
          data: "Position"
        },
        {
          targets: 10,
          data: "DateHired"
        },
        {
          targets: 11,
          data: "BaseSalary"
        },
        {
          targets: 12,
          data: "PayCode",
          render: (data, type, row, meta) => {
            return "<div class='tooltipp'>" + data + "<div class='tooltiptext panel'> <div class='panel-heading'>Includes:</div><div class='panel-body'> \
                  <strong>Benefits</strong>: <br>"
                  + row.Benf.map(item => '- ' + item).join('<br>') + 
                  "<br> <strong>Deductions:</strong> <br>"
                  + row.Deduct.map(item => '- ' + item).join('<br>') + 
                  "<br> <strong>Expense:</strong> <br>"
                  + row.Exp.map(item => '- ' + item).join('<br>') + 
                "</div></div></div>";
          },
        },
        {
          targets: 12,
          data: "SSS"
        },
        {
          targets: 13,
          data: "PHCI"
        },
        {
          targets: 14,
          data: "HDMF"
        },
        {
          targets: 15,
          data: "Account"
        },
        {
          targets: 16,
          data: "Type"
        }
      ],
      order: [
        [1, 'asc']
      ],
      scrollX:        true,
      scrollCollapse: true,
      fixedColumns:   {
        leftColumns: 2
      }
    });

    $('body').on('change', 'input[type=radio][name=status], input[type=radio][name=level], .select-branch, .check-branch',
      () => {
        reloadTable();
      }
    )
    
    $('body').on('change', '.empStatus input', (event) => {
      let href = $('.btn-io').attr('href')
      href = href.replace(/&status=.*/, '') + '&status=' + event.target.value

      $('.btn-io').attr('href', href)
    })

    function reloadTable(){
      let url = "<?php echo route('employee.deliveryItems', ['corpID' => $corpID, 'branchSelect' => 'targetBranchSelect', 'branch' => 'targetBranch', 'status' => "targetStatus", 'level'=>"targetLevel"]) ?>"
      let status = $('input[type=radio][name=status]:checked').val()
      let branchSelect = $('input[name=selectBranch]').prop('checked') ? "hasBranch" : ""
      let branch = $('.select-branch').val();
      let level = $('input[type=radio][name=level]:checked').val();
      url = url.replace('targetStatus', status).replace('targetBranchSelect', branchSelect)
                .replace('targetBranch', branch).replace('targetLevel', level)
      table.ajax.url( url ).load(() => {
      })
    }

    $('body').on( "change", ".check-branch",
      (event) => {
        if ($('.check-branch').is(":checked"))
        {
          $('.select-branch').prop('disabled', false);
          $('.value-option').first().prop('selected', true);
          $('input[name="level"]').prop('disabled', true)
          reloadTable();
        }
        else
        {
          $('input[name="level"]').prop('disabled', false)
          $('.first-option').prop('selected', true);
          $('.select-branch').prop('disabled', true);
        }
      }
    )

    $("#sortable").sortable({
      change: ( event, ui ) => {
      }
    });

    $('body').on('mouseenter', '.tooltipp', function(event) {
      $(this).find('.tooltiptext').css({
        'display': 'block',
        'top': $(this).offset().top - $(window).scrollTop() - $(this).find('.tooltiptext').height() - 30,
        'left': $(this).offset().left - $(this).find('.tooltiptext').width() / 2,
      })

    }).on('mouseleave', '.tooltipp', function(event) {
      $(this).find('.tooltiptext').css('display', 'none')
    })

    $('#sort-button').click(
      () => {
        change_current_sort()
      }
    )

    const LISTSORT = {
      'Branch': 6,
      'Position': 8,
      'Department': 7,
      'Date Hired': 9,
      'Name': 1
    }
  
    function change_current_sort(){
      var new_sort = ""
      $('#sortable').find('input:checked').each(function(index){
          var self = $(this)
          if(index == 0){
            new_sort += self.parents('.ui-state-default').find('.sort-item').text()
          }else{
            new_sort += (" > " + self.parents('.ui-state-default').find('.sort-item').text())
          }
          position_sort = LISTSORT[self.parents('.ui-state-default').find('.sort-item').text()]
      })
      
      $('.current_sort').text(new_sort)

      localStorage.setItem('sortEmployee', new_sort)

      new_sort += ' > Name'

      new_sort_array = new_sort.split(" > ").reverse();
      $.each(new_sort_array, function( index, value ) {
        $('.dataTables_scrollHeadInner').find("th:contains("+value+")").trigger("click")
        if($('.dataTables_scrollHeadInner').find("th:contains("+value+")").hasClass('sorting_desc')){
          $('.dataTables_scrollHeadInner').find("th:contains("+value+")").trigger("click")
        }
      });
    }

    if (localStorage.getItem('sortEmployee')) {
      $('.ui-state-default type="checkbox"').prop('checked', false)

      let sortItems = localStorage.getItem('sortEmployee').split(" > ").reverse()
    }
  });

  
</script>
@endsection
