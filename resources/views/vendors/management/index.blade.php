@extends('layouts.app')
@section('header-scripts')
    <link href="/css/parsley.css" rel="stylesheet" >
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll
        {
            overflow-x: auto;
            overflow-y: auto;
        }

        th.dt-center, td.dt-center { text-align: center; }

        .panel-body {
            padding: 15px !important;
        }

        a.disabled {
            pointer-events: none;
            cursor: default;
            color: transparent;
        }
        .modal {
            z-index: 10001 !important;;
        }

        #example_ddl > select {
            margin: 2px 0 2px 0;
            width: 176px;
            height: 30px;
        }

        input[type="checkbox"]{
            height: 18px;
            width: 18px;
        }

        #example_ddl label {
            position: relative;
            top: 8px;
        }

        #example_ddl2, #example_ddl3, #example_ddl4 {
            margin-right: 5px !important;
        }

        .my_custom {
            position: relative;
            left: 16px;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                    <ul id="sidebar_menu" class="sidebar-nav">
                        <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
                    </ul>
                    <div class="sidebar-nav" id="sidebar">
                        <div id="treeview_json"></div>
                    </div>
                </div>

                <!-- Page content -->
                <div id="page-content-togle-sidebar-sec">
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Vendor Management</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            @if($vendors->count() > 0 )
                                                <a href="/vendors">  {{ $vendors[0]->VendorName }} </a>
                                            @endif
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="#" data-toggle="modal" data-target="#addNewAccount" class="pull-right @if(!\Auth::user()->checkAccessById(29, "A")) disabled @endif" >Add Account</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Corporation</th>
                                            <th>Corp Id</th>
                                            <th>Branch</th>
                                            <th>Account Number</th>
                                            <th>Description</th>
                                            <th>Cycle(days)</th>
                                            <th>Offset</th>
                                            <th>Active</th>
                                            <th>Hiden</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($vendors as $vendormgm)
                                            <tr>
                                                <td>{{ $vendormgm->corp_name }}</td>
                                                <td>{{ $vendormgm->corp_id }}</td>
                                                <td>{{ $vendormgm->ShortName }}</td>
                                                <td>{{ $vendormgm->acct_num }}</td>
                                                <td>{{ $vendormgm->description }}</td>
                                                <td>{{ $vendormgm->days_offset }}</td>
                                                <td>{{ $vendormgm->firstday_offset }}</td>
                                                <td>@if($vendormgm->active) <input type="checkbox" disabled checked> @else
                                                        <input type="checkbox" disabled>@endif</td>
                                                <td>{{ $vendormgm->active }}</td>
                                                <td>
                                                    <a href="#" name="edit" class="btn btn-primary btn-sm edit  @if(!\Auth::user()->checkAccessById(29, "E")) disabled @endif">
                                                        <i class="glyphicon glyphicon-pencil"></i><span style="display: none;"></span>
                                                    </a>
                                                    <a href="#" name="delete" class="btn btn-danger btn-sm delete @if(!\Auth::user()->checkAccessById(29, "D")) disabled @endif">
                                                        <i class="glyphicon glyphicon-trash"></i><span style="display: none;">{{ $vendormgm->acct_id }}</span>
                                                    </a>
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
    </div>
    <!-- Modal add new account -->
    <div id="addNewAccount" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add Vendor Account</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/vendor-management') }}" id="form1" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                        <label class="col-md-3 control-label" for="corporationId">Corporation:</label>
                                        <div class="col-md-7">
                                            <select name="corporationId" class="form-control input-md corporationId" id=""
                                                    data-parsley-required-message="Corporation person is required" required>
                                                <option value="">Select Corporation:</option>
                                                @foreach($corporations as $corporation)
                                                    <option value="{{ $corporation->corp_id }}">{{ $corporation->corp_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12 pull-left" style="margin-left: -80px;">
                                        <input type="checkbox" name="mainStatus" class="pull-left mainStatus" name="" id="">
                                        <label for="mainStatus" style="margin-top: 2px; margin-left: 1px">Main</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="branchName">Branch:</label>
                                    <div class="col-md-7">
                                        <select name="branchName" class="form-control input-md branchName" id="">
                                            <option value="">Select Branch:</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="vendorAccountNumber">Account number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="vendorAccountNumber" name="vendorAccountNumber" type="text" class="form-control input-md"
                                       data-parsley-required-message="Account number person is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 50 characters"
                                       data-parsley-maxlength="50"  data-parsley-pattern="^[\d+\-\?]+\d+$" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="description">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="description" name="description" type="text" class="form-control input-md">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="cycleDays">Cycle(days):</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="cycleDays" name="cycleDays" type="text" value="0" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="offsetDays">Offset:</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="offsetDays" name="offsetDays" type="text" value="0" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="activeAccount">Active:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="" name="activeAccount" type="checkbox" class="input-md">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <input type="hidden" name="suppId" value="{{ $vendors[0]->supp_id }}">
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new account -->


    <!-- Modal edit account -->
    <div id="editAccount" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Vendor Account</h5>
                </div>
                <form class="form-horizontal" action="" id="form2" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="editBranchName">Branch:</label>
                                    <div class="col-md-7">
                                        <select name="editBranchName" class="form-control input-md editBranchName" id="">
                                            <option value="">Select Branch:</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 pull-left" style="margin-left: -80px;">
                                    <input type="checkbox" name="editMainStatus" class="pull-left editMainStatus" name="" id="">
                                    <label for="editMainStatus" style="margin-top: 2px; margin-left: 1px">Main</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="editCorporationId">Corporation:</label>
                                    <div class="col-md-7">
                                        <select name="editCorporationId" class="form-control input-md editCorporationId" id=""
                                                data-parsley-required-message="Corporation person is required" required>
                                            <option value="">Select Corporation:</option>
                                            @foreach($corporations as $corporation)
                                                <option value="{{ $corporation->corp_id }}">{{ $corporation->corp_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editVendorAccountNumber">Account number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="editVendorAccountNumber" name="editVendorAccountNumber" type="text" class="form-control input-md"
                                       data-parsley-required-message="Account number person is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 50 characters"
                                       data-parsley-maxlength="50" data-parsley-pattern="^[\d+\-\?]+\d+$"  required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editDescription">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="editDescription" name="editDescription" type="text" class="form-control input-md">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editCycleDays">Cycle(days):</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="editCycleDays" name="editCycleDays" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="offsetDays">Offset:</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="editOffsetDays" name="editOffsetDays" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editActiveAccount">Active:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="" name="editActiveAccount" type="checkbox" class="input-md">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                {{ method_field('PUT') }}
                                <input type="hidden" name="suppId" value="@if($vendors->count() > 0 ){{ $vendors[0]->supp_id }} @endif">
                                <button type="submit" class="btn btn-success pull-right">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing account -->

    <!-- Modal delete item from inventory -->
    <div class="modal fade" id="confirm-delete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form action="" method="POST" >
                    <div class="modal-body">
                        <p class="text-center">You are about to delete one track, this procedure is irreversible.</p>
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="brandToDelete"></span>-
                            <span style="font-weight:bold" class="descriptionOfBrand"></span> ?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <input style="display: none" class="serviceId" >
                        {!! csrf_field() !!}
                        {{ method_field('Delete') }}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-ok" class="deleteItem">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end Modal -->

@endsection

@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>
    <script>
        (function($){

            $('#form1, #form2').parsley();

            var mainTable = $('#myTable').DataTable({
                initComplete: function () {
                    $('<label for="">Filters:</label>').appendTo("#example_ddl");

                    this.api().columns(8).every( function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">All</option></select>')
                            .appendTo( '#example_ddl3' )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        select.append( '<option value="1">Active</option>' )
                        select.append( '<option value="0">Inactive</option>' )

                    } );

                    this.api().columns(1).every( function () {
                        var column = this;
                        var corporationID = $('<select class="form-control"><option value="{{ $corporations[0]->corp_id }}">{{ $corporations[0]->corp_name }}</option></select>')
                            .appendTo('#example_ddl2')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                mainTable.search('')

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );


                            <?php $cnt = 0 ?>
                            @foreach($corporations as $key => $val)
                            @if($cnt == 0){
                            <?php $key ?>
                        }@else{
                            corporationID.append('<option value="{{ $val->corp_id }}">{{ $val->corp_name }}</option>');
                        }
                        @endif

                        <?php $cnt++; ?>
                        @endforeach

                    });



                },
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row my_custom'<'col-sm-2.pull-left'<'#example_ddl'>><'col-sm-2.pull-left'<'#example_ddl2'>><'col-sm-2.pull-left'<'#example_ddl3'>><'col-sm-2.pull-left'<'#example_ddl4'>><'col-sm-2.pull-left'<'#example_ddl5'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                order: [[ 2, 'asc' ]],
                "columnDefs": [
                    { "orderable": false, "targets": [0, 2]},
                    { "orderable": false, 'visible': false, "targets": [1,8]},
                    { "orderable": false, "width": "9%", "targets": 5 },
                    {"className": "dt-center", "targets": [5, 7]}
                ]
            });
            $('.dataTable').wrap('<div class="dataTables_scroll" />');

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(1)').text();
                var description  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .brandToDelete').text(itemCode);
                $('#confirm-delete .descriptionOfBrand').text(description);
                $('#confirm-delete form').attr('action', '/OneBusiness/vendor-management/'+id);
                $('#confirm-delete').modal("show");
            });

            $(document).on('click', '.mainStatus', function () {
                if($('.mainStatus').is(':checked')){
                    $('.branchName').attr('disabled', true).css({"background-color":"#dddddd", "color":"#dddddd"});
                }else{
                    $('.branchName').attr('disabled', false).css({"background-color":"#FFF", "color":"#333"});
                }
            });

            $(document).on('click', '.editMainStatus', function () {
                if($('.editMainStatus').is(':checked')){
                    $('.editBranchName').attr('disabled', true);
                }else{
                    $('.editBranchName').attr('disabled', false);
                }
            });

            $(document).on('click', '.edit', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();

                $.ajax({
                    type: "POST",
                    url: "/OneBusiness/vendor-management/get-account-for-vendor",
                    data: { id : id },
                    success: function (data) {
                        if(data.nx_branch == -1){
                            $('.editMainStatus').attr("checked", true);
                            $('.editBranchName').attr("disabled", true);
                        }else{
                            $('.editBranchName').val(data.nx_branch);
                        }
                        $('.editCorporationId').val(data.corp_id);
                        $('#editVendorAccountNumber').val(data.acct_num);
                        $('input[name="editDescription"]').val(data.description);
                        $('input[name="editCycleDays"]').val(data.days_offset);
                        $('input[name="editOffsetDays"]').val(data.firstday_offset);

                        if(data.active){
                            $('input[name="editActiveAccount').attr("checked", true);
                        }
                        $('#editAccount form').attr('action', '/vendor-management/'+id);
                        $('#editAccount').modal("toggle");
                    }
                })
            });

           /* $(document).on('change', '#example_ddl2', function () {
                var location = $('#example_ddl2 option:selected').val();
                window.location.href = '?corp='+location;
            })*/

            $(document).on('change', '.corporationId', function () {
                var corpId = $('.corporationId option:selected').val();
                var options = $('.branchName');
                options.empty();
                //get branches
                var cnt = 0;
                $.ajax({
                    method: 'POST',
                    url: '/OneBusiness/vendors/get-branches',
                    data: { corpId : corpId },
                    success: function (data) {
                        data = JSON.parse(data);
                        $.each(data, function (key, val) {
                            cnt++;
                            options.append('<option value="'+val.Branch+'">'+val.ShortName+'</option>');
                        })

                        if(cnt == 0){
                            options.append('<option value="">No options</option>');
                        }
                    }

                })
            })

            mainTable.search( $('#example_ddl2 option:selected').text() );
            mainTable.draw();


        })(jQuery);
    </script>
@endsection