@extends('layouts.app')
@section('header-scripts')
    <link href="css/parsley.css" rel="stylesheet" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
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


        @media (max-width: 960px) {
            .bankCodeRw {
                margin-left: 0 !important;
            }

            .addBank {
                position: relative;
                margin-left: 0 !important;
                right: -45px;
                margin-top: 10px;
            }

            .acctNumRw{
                margin-left: 2px !important;
            }
        }

        @media (min-width: 961px) and (max-width: 1001px) {
            .bankCodeRw {
                margin-left: 0 !important;
            }

            .addBank {
                position: relative;
                margin-left: 0 !important;
                right: -45px;
                margin-top: 10px;
            }

            .acctNumRw {
                margin-left: 2px !important;
            }

            .acctNumRw > label {
                position: relative;
                left: -450px;
            }
        }

        #example_ddl label {
            position: relative;
            top: 8px;
        }

        #example_ddl5 {
            position: relative;
            top: 5px;
        }

        #example_ddl2, #example_ddl3, #example_ddl4 {
            margin-right: 5px !important;
        }

        .my_custom {
            position: relative;
            left: 16px;
        }

        .editAccount {
            margin-right: 4px;
        }



    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                  <div id="sidebar_menu" class="sidebar-nav">
                    <ul></ul>
                  </div>
                </div>

                <!-- Page content -->
                <div id="page-content-togle-sidebar-sec">
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
                    @endif
                    <div id="result" style="display: none;"></div>
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Banks</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="#" class="pull-right @if(!\Auth::user()->checkAccessById(27, "A")) disabled @endif"
                                                data-toggle="modal" data-target="#addNewAccount" >Add Bank Account</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Use</th>
                                            <th>Bank Code</th>
                                            <th>Account Number</th>
                                            <th>Date Created</th>
                                            <th>Action</th>
                                            <th>Corp Id</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal add new bank account -->
    <div id="addNewAccount" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">New Bank Account Number</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/bank-accounts') }}" id="form2" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="bankCode">Bank Code:</label>
                                    <div class="col-md-9">
                                        <select name="bankCode" class="form-control input-md bankCode" id=""
                                                data-parsley-required-message="Bank Code is required" required>
                                            <option value="">Select Bank:</option>
                                            @foreach($selectBank as $bank)
                                                <option value="{{ $bank->bank_id }}">{{ $bank->bank_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12" style="margin-left: -30px;">
                                    <a href="#" class="addBank" data-dismiss="modal" data-toggle="modal" data-target="#addNewBank" style="font-size: 0.8em">Add Bank</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                <label class="col-md-3 control-label" for="corpName">Corporation:</label>
                                <div class="col-md-9">
                                    <select name="corpName" class="form-control input-md corpName" id=""
                                            data-parsley-required-message="Corporation is required" required>
                                        <option value="">Select Corporation:</option>
                                        @foreach($selectCorp as $corp)
                                            <option value="{{ $corp->corp_id }}">{{ $corp->corp_name }}</option>
                                        @endforeach
                                    </select>
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
                                            @if(is_object($satelliteBranch))
                                            @foreach($satelliteBranch as $branch)
                                                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                                            @endforeach
                                                @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 pull-left" style="margin-left: -80px;">
                                    <input type="checkbox" name="mainStatus" class="pull-left mainStatus" name="" id="">
                                    <label for="mainStatus" style="margin-top: 2px; margin-left: 1px">Main</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="bankAccountNumber">Account number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankAccountNumber" name="bankAccountNumber" type="text" class="form-control input-md"
                                       data-parsley-required-message="Account number is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 50 characters"
                                       data-parsley-maxlength="50"  data-parsley-pattern="^[\d+\-\?]+\d+$" required="">
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
                                <input type="hidden" name="pcBranchId" class="pcBranchId" value="">
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new bank account -->

    <!-- Modal add new bank -->
    <div id="addNewBank" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add Bank</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/banks') }}" id="form1" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankName">Bank Name:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankName" name="bankName" type="text" class="form-control input-md"
                                       data-parsley-required-message="Bank Name is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 20 characters"
                                       data-parsley-maxlength="20" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankName">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankDescription" name="bankDescription" type="text" class="form-control input-md"
                                       data-parsley-required-message="Description is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 100 characters"
                                       data-parsley-maxlength="100" required="">
                            </div>
                        </div>
                        <hr class="wide">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="bankTable"  class="table table-striped table-hover responsive">
                                            <thead>
                                            <tr>
                                                <th>Bank Name</th>
                                                <th>Description</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectBank as $bank)
                                                    <tr>
                                                        <td>{{ $bank->bank_code }}</td>
                                                        <td>{{ $bank->description }}</td>
                                                        <td>
                                                            <a href="#" name="edit" class="btn btn-primary btn-md editBank  {{--@if(!\Auth::user()->checkAccessById(23, "E")) disabled @endif--}}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                            <a href="#" name="delete" class="btn btn-danger btn-md delete  {{--@if(!\Auth::user()->checkAccessById(23, "E")) disabled @endif--}}">
                                                                <i class="glyphicon glyphicon-trash"></i><span style="display: none;">{{ $bank->bank_id }}</span>
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
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"  data-toggle="modal"
                                        data-target="#addNewAccount"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new bank -->

    <!-- Modal add new bank when edit -->
    <div id="addNewBankonEdit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add Bank</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/banks') }}" id="form1" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankName">Bank Name:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankName" name="bankName" type="text" class="form-control input-md"
                                       data-parsley-required-message="Bank Name is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 20 characters"
                                       data-parsley-maxlength="20" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankName">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankDescription" name="bankDescription" type="text" class="form-control input-md"
                                       data-parsley-required-message="Description is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 100 characters"
                                       data-parsley-maxlength="100" required="">
                            </div>
                        </div>
                        <hr class="wide">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="bankTable"  class="table table-striped table-hover responsive">
                                            <thead>
                                            <tr>
                                                <th>Bank Name</th>
                                                <th>Description</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectBank as $bank)
                                                    <tr>
                                                        <td>{{ $bank->bank_code }}</td>
                                                        <td>{{ $bank->description }}</td>
                                                        <td>
                                                            <a href="#" name="edit" class="btn btn-primary btn-md editBank  {{--@if(!\Auth::user()->checkAccessById(23, "E")) disabled @endif--}}">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                            <a href="#" name="delete" class="btn btn-danger btn-md delete  {{--@if(!\Auth::user()->checkAccessById(23, "E")) disabled @endif--}}">
                                                                <i class="glyphicon glyphicon-trash"></i><span style="display: none;">{{ $bank->bank_id }}</span>
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
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"  data-toggle="modal"
                                        data-target="#editAccountModal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new bank when edit -->

    <!-- Modal delete bank -->
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
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="itemToDelete"></span> -
                            <span class="bankToDelete" style="font-weight: bold"></span> ?</p>
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

    <!-- Modal edit the bank -->
    <div id="editBankModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Bank</h5>
                </div>
                <form class="form-horizontal" action="" METHOD="POST" id="editBankModalForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankDescriptionEdit">Bank Name:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankNameEdit" name="bankNameEdit" type="text" class="form-control input-md"
                                       data-parsley-required-message="Bank Name is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 20 characters"
                                       data-parsley-maxlength="20" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankDescriptionEdit">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankDescriptionEdit" name="bankDescriptionEdit" type="text" class="form-control input-md"
                                       data-parsley-required-message="Description is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 100 characters"
                                       data-parsley-maxlength="100" required="">
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
                                <input type="hidden" class="bankID" name="bankID">
                                <button type="submit" class="btn btn-success pull-right">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing the bank -->

    <!-- Modal edit account -->
    <div id="editAccountModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Account</h5>
                </div>
                <form class="form-horizontal" action="" id="editAccountModalForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 col-xs-12 control-label" for="bankAccountCodeEdit">Bank Code:</label>
                                    <div class="col-md-9 col-xs-12">
                                        <select name="bankAccountCodeEdit" id="bankAccountCodeEdit" class="form-control input-md" id=""
                                                data-parsley-required-message="Bank Code is required" required>
                                            <option value="">Select Bank:</option>
                                            @foreach($selectBank as $bank)
                                                <option value="{{ $bank->bank_id }}">{{ $bank->bank_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12" style="margin-left: -30px;">
                                    <a href="#" class="addBank" data-dismiss="modal" data-toggle="modal" data-target="#addNewBankonEdit" style="font-size: 0.8em">Add Bank</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                <label class="col-md-3 control-label" for="editCorpName">Corporation:</label>
                                <div class="col-md-9">
                                    <select name="editCorpName" class="form-control input-md editCorpName corpName" id=""
                                            data-parsley-required-message="Corporation is required" required>
                                        <option value="">Select Corporation:</option>
                                        @foreach($selectCorp as $corp)
                                            <option value="{{ $corp->corp_id }}" >{{ $corp->corp_name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="branchName">Branch:</label>
                                    <div class="col-md-7">
                                        <select name="editbranchName" class="form-control input-md editbranchName branchName" id="">
                                            <option value="">Select Branch:</option>
                                            @if(is_object($satelliteBranch))
                                            @foreach($satelliteBranch as $branch)
                                                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                                            @endforeach
                                                @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 pull-left" style="margin-left: -80px;">
                                    <input type="checkbox" name="mainStatus" class="pull-left editmainStatus" name="" id="">
                                    <label for="mainStatus" style="margin-top: 2px; margin-left: 1px">Main</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankAccountNumberEdit">Account Number:</label>
                            <div class="col-md-7 col-xs-12">
                                <input id="bankAccountNumberEdit" name="bankAccountNumberEdit" type="text" class="form-control input-md"
                                       data-parsley-required-message="Account number is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 50 characters"
                                       data-parsley-maxlength="50"  data-parsley-pattern="^[\d+\-\?]+\d+$" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                <input type="hidden" class="accountID" name="accountID">
                                <button type="submit" class="btn btn-success pull-right">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing the bank -->

    <!-- checkbox change modal -->

    <div class="modal fade" id="confirmCheckbox" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Default Account
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            Your default account number has been changed!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end checkbox change modal -->

    <!-- Modal delete item from inventory -->
    <div class="modal fade" id="confirm-delete-account" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form action="" method="POST" id="deleteAccount" >
                    <div class="modal-body">
                        <p class="text-center">You are about to delete one track, this procedure is irreversible.</p>
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="bankOfAccount"></span> -
                            <span style="font-weight:bold" class="accountToDelete"></span> ?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" class="deleteAccountId" >
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-dateFormat/1.0/jquery.dateFormat.min.js"></script>
    <script>
        (function($){
            $('#editAccountModalForm, #editBankModalForm, #form1, #form2').parsley();
            var __data = "";
            var mainTable = $('#myTable').DataTable({
                initComplete: function () {
                    $('<label for="">Filters:</label>').appendTo("#example_ddl");
                    var corporationID = $('<select class="form-control"><option value="@if(isset($corporations[0]->corp_id)){{ $corporations[0]->corp_id }} @endif">@if(isset($corporations[0]->corp_name)){{ $corporations[0]->corp_name }} @else N/A @endif</option></select>')
                        .appendTo('#example_ddl2');
                    var cntCorp = 0;
                    @if(is_object($corporations))
                    @foreach($corporations as $key => $val)
                    if(cntCorp != 0){
                        corporationID.append('<option value="{{ $val->corp_id }}">{{ $val->corp_name }}</option>');
                    }
                    cntCorp++;

                    @endforeach
                        @endif
                    var branchStatus = $('<select class="form-control"><option value="1" selected>Active</option></select>')
                        .appendTo('#example_ddl3');
                    branchStatus.append('<option value="0">Inactive</option>');
                    var branches = $('<select class="form-control"><option value="@if(isset($satelliteBranch[0]->Branch)){{ $satelliteBranch[0]->Branch }} @endif">@if(isset($satelliteBranch[0]->ShortName)){{ $satelliteBranch[0]->ShortName }} @else N/A @endif</option></select>')
                        .appendTo('#example_ddl4');
                    var cntBranches = 0;
                    @if(is_object($satelliteBranch))
                    @foreach($satelliteBranch as $key => $val)
                    if(cntBranches != 0){
                        branches.append('<option value="{{ $val->Branch }}">{{ $val->ShortName }}</option>');
                    }
                    cntBranches++;

                    @endforeach
                        @endif
                    var mainStatus = $('<input class="" type="checkbox"><label value="">Main</label>')
                        .appendTo('#example_ddl5');
                },
               "processing": true,
               bServerSide: false,
            //    sAjaxSource: true,
               "serverSide": true,
                "ajax" : {
                   type: "POST",
                    url: "{!! route('banks.get_banks_list') !!}",
                    data: function (d) {
                        d.dataStatus = $('#example_ddl3 select option:selected').val() == undefined ? 1 : $('#example_ddl3 select option:selected').val();
                        @if(isset($corporations[0]->corp_id))
                        d.corpId = $('#example_ddl2 select option:selected').val() == undefined ? '{{ $corporations[0]->corp_id }}' : $('#example_ddl2 select option:selected').val();
                        console.log($('#example_ddl4 select').val());
                        d.branch = $('#example_ddl4 select option:selected').val() == undefined ? '{{ $satelliteBranch[0]->Branch }}' : $('#example_ddl4 select').val();
                        @endif
                        d.MainStatus = $('#example_ddl5 input').is(":checked");

                    }
                },
                stateSave: true,
                stateDuration:-1,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row my_custom'<'col-sm-2.pull-left'<'#example_ddl'>><'col-sm-2.pull-left'<'#example_ddl2'>><'col-sm-2.pull-left'<'#example_ddl3'>><'col-sm-2.pull-left'<'#example_ddl4'>><'col-sm-2.pull-left'<'#example_ddl5'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            var checked = "";
                            if(row.default_acct == 1) checked = "checked";
                            return '<input type="checkbox" '+ checked +' disabled >';
                        },
                        "targets": 0
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.bank_code;
                        },
                        "targets": 1
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.acct_no;
                        },
                        "targets": 2
                    },
                    {
                        "render": function ( data, type, row ) {
                           // console.log($.format.date(row.date_created, 'dd/MM/yyyy'));
                            var dateMin = $.format.date(row.date_created, 'dd/MM/yyyy');
                            return dateMin;
                        },
                        "targets": 3
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checkAccess = '<?php  if(\Auth::user()->checkAccessById(27, "E")) {  echo 1; }else{ echo 0; } ?>';
                            var optionClass = "";
                            if(checkAccess == 0) { optionClass = 'disabled' };

                            var checkDelete = '<?php  if(\Auth::user()->checkAccessById(27, "D")) {  echo 1; }else{ echo 0; } ?>';
                            var optionClassDel = "";
                            if(checkDelete == 0) { optionClassDel = 'disabled' };
                           return '<a href="#" name="checkDefaultAcct" class="btn btn-success btn-md checkDefaultAcct" '+optionClass+'>' +
                            '<i class="glyphicon glyphicon-ok"></i><span class="changeAccountID" style="display: none;">'+ row.bank_acct_id +'</span>' +
                            '</a>&nbsp<a href="#" name="editAccount" class="btn btn-primary btn-md editAccount" '+optionClass+'>' +
                            '<i class="fas fa-pencil-alt"></i><span class="editBankID" style="display: none;">'+row.bank_acct_id+'</span>' +
                            '<span class="editBranchID" style="display: none;">'+row.branch+'</span>' +
                            '<span class="codeNumID" style="display: none;">'+row.bank_id+'</span></a>' +
                               '<a href="#" name="delete" class="btn btn-danger btn-md delete-account '+optionClassDel+'">'+
                               '<i class="glyphicon glyphicon-trash"></i></a>';
                        },
                        "targets": 4
                    },
                    {
                        "render": function ( data, type, row ) {
                           return row.corp_id;
                        },
                        "targets": 5
                    },
                    { "orderable": false, "width": "5%", "targets": 0},
                    { "orderable": false, "width": "15%", "targets": 4 },
                    { "orderable": false, "visible" : false, "targets": 5 },
                    {"className": "dt-center", "targets": 4},
                    {"className": "dt-center", "targets": 0}
                ],
                "columns": [
                    { "data": "default_acct" },
                    { "data": "bank_code" },
                    { "data": "acct_no" },
                    { "data": "date_created" }
                ],
            });

            //init datatables
            $('#bankTable').DataTable({
                "bLengthChange": false,
                "pageLength": 5,
                columns: [
                    null,
                    null,
                    { orderable : false }
                ]
            });


            $('.dataTable').wrap('<div class="dataTables_scroll" />');

            $(document).on('click', '.mainStatus', function () {
                if($('.mainStatus').is(':checked')){
                    $('.branchName').attr('disabled', true).css({"background-color":"#dddddd", "color":"#dddddd"});
                }else{
                    $('.branchName').attr('disabled', false).css({"background-color":"#FFF", "color":"#333"});
                }
            });

            $(document).on('click', '.editmainStatus', function () {
                if($('.editmainStatus').is(':checked')){
                    $('.editbranchName').attr('disabled', true).css({"background-color":"#dddddd", "color":"#dddddd"});
                }else{
                    $('.editbranchName').attr('disabled', false).css({"background-color":"#FFF", "color":"#333"});
                }
            });


            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(1)').text();
                var bankName  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .itemToDelete').text(itemCode);
                $('#confirm-delete .bankToDelete').text(bankName);
                $('#confirm-delete form').attr('action', 'banks/'+id);
                $('#confirm-delete').modal("show");
            });

            $(document).on('click', '.editBank', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(1)').text();
                var itemDescription  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#bankNameEdit').val(itemCode);
                $('#bankDescriptionEdit').val(itemDescription);
                $('.bankID').val(id);
             //   $('#addNewBank').modal("toggle");
                $('#editBankModal form').attr('action', 'banks/'+id);
                $('#editBankModal').modal("toggle");
            });

            $(document).on('click', '.editAccount', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('.editBankID').text();
                var accountNum  = $(this).closest('tr').find('td:nth-child(3)').text();
                var codeNum = $(this).closest('tr').find('.codeNumID').text();
                $('#bankAccountCodeEdit').val(codeNum);
                $('#bankAccountNumberEdit').val(accountNum);

                var row = $(this).closest('tr');
                var hiddenColumnValue = mainTable.row(row).data();
                console.log(hiddenColumnValue);
                $('.editCorpName').val(hiddenColumnValue.corp_id);
                $('.editbranchName').val(hiddenColumnValue.branch);
                $('.accountID').val(id);
                $('#editAccountModal').modal("toggle");
                if(hiddenColumnValue.branch == -1)
                {
                    $('.editmainStatus').prop('checked', true);
                    $('.editbranchName').attr('disabled', true).css({"background-color":"#dddddd", "color":"#dddddd"});
                }
                else
                {
                    $('.editmainStatus').prop('checked', false);
                    $('.editbranchName').removeAttr('disabled').css({"background-color":"", "color":""});
                }


                var corpId = hiddenColumnValue.corp_id;
                var options = $('.editCorpName').parents('form').find('.branchName');

                options.empty();
                //get branches
                var cnt = 0;
                $.ajax({
                    method: 'POST',
                    url: "{!! route('vendors.get_branch') !!}",
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
            });

            $(document).on('click', '.checkDefaultAcct', function (e) {
                e.preventDefault();

                var ref = $(this);
                var id  = $(this).closest('td').find('.changeAccountID').text();

                $.ajax({
                    type: 'POST',
                    url: '{!! route("bank_accounts.change_default_account") !!}',
                    data: { id : id },
                    success: function () {
                        ref.closest('tbody').find('input:checked').each(function () {
                            $(this).prop("checked", false);
                        });

                        ref.closest('tr').find('td:first-child input').prop("checked", true);

                        $.alert({
                            title: 'Default Account',
                            content: '<span style="color: green">Successfully changed!</span>',
                            backgroundDismiss: true,
                        });
                    }
                });

            });


            $('#example_ddl5').on("click", function(e) {
                if($('#example_ddl5 input').is(':checked')){
                    $('#example_ddl4 select').attr('disabled', true).css({"background-color":"#FFF", "color":"#FFF"});
                }else{
                    $('#example_ddl4 select').attr('disabled', false).css("color", "#333");
                }
                mainTable.ajax.reload();
            });

            $('#example_ddl2').on('change', function () {
                var dataStatus = $('#example_ddl3 select option:selected').val();
                var corpId = $('#example_ddl2 select option:selected').val();

                var options = $('#example_ddl4 select');
                options.empty();
                //get branches
                var cnt = 0;
                $.ajax({
                    method: 'POST',
                    url: "{!! route('checkbooks.get_branches') !!}",
                    data: { status : dataStatus, corpId : corpId },
                    success: function (data) {
                        data = JSON.parse(data);
                        $.each(data, function (key, val) {
                            cnt++;
                            options.append('<option value="'+val.Branch+'">'+val.ShortName+'</option>');
                        })
                        if(cnt == 0){
                            options.append('<option value="">No option</option>');
                        }
                        setTimeout(() => {
                            mainTable.ajax.reload();
                        }, 200);
                    }

                })
                
            })

            $('#example_ddl3').on('change', function () {
                var dataStatus = $('#example_ddl3 select option:selected').val();
                var corpId = $('#example_ddl2 select option:selected').val();

                var options = $('#example_ddl4 select');
                options.empty();
                var cnt = 0;
                //get branches
                $.ajax({
                    method: 'POST',
                    url: "{!! route('banks.get_branches') !!}",
                    data: { status : dataStatus, corpId : corpId },
                    success: function (data) {
                        data = JSON.parse(data);
                        $.each(data, function (key, val) {
                            cnt++;
                            options.append('<option value="'+val.Branch+'">'+val.ShortName+'</option>');
                        })
                        if(cnt == 0){
                            options.append('<option value="">No option</option>');
                        }
                        mainTable.ajax.reload();
                    }

                })
                
            })

            $('#example_ddl4').on('change', function () {
                var id = $('#example_ddl4 option:selected').val();
                $('.pcBranchId').val(id);
                mainTable.ajax.reload();
            })

            $(document).on('submit', '#editAccountModalForm', function (e) {
                e.preventDefault();

                var bankCode = $('#bankAccountCodeEdit option:selected').val();
                var accountNum = $('#bankAccountNumberEdit').val();
                var accountID = $('.accountID').val();
                var corpId = $('.editCorpName option:selected').val();
                var branch = $('.editbranchName option:selected').val();
                if( $('.editmainStatus').is(':checked') )
                {
                    var mainStatus = "on";
                }
                else
                {
                    var mainStatus = "off";
                }
                
                $.ajax({
                    url: "{!! route('bank_accounts.update') !!}",
                    method: "POST",
                    data: { bankAccountCodeEdit : bankCode, bankAccountNumberEdit : accountNum, accountID : accountID, corpId : corpId, branch: branch, mainStatus: mainStatus},
                    success: function (data) {
                        if(data == "success"){
                            $('#editAccountModal').modal("toggle");

                            $("#result").html('<div class="alert alert-success col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspAccount updated successfully!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                            mainTable.ajax.reload();
                        }else{
                            $('#editAccountModal').modal("toggle");
                            $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspSomething went wrong!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                        }
                    },
                    error: function () {
                        $('#editAccountModal').modal("toggle");
                        $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                            '</span><em>&nbspSomething went wrong!</em></div></div>');
                        $('#result').fadeIn();
                        $("#result").delay(3000).fadeOut("slow");
                    }
                })
            });

            $(document).on('click', '.delete-account', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('.changeAccountID').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(2)').text();
                var account  = $(this).closest('tr').find('td:nth-child(3)').text();
                $('#confirm-delete-account').find('.deleteAccountId').val(id);
                $('#confirm-delete-account .bankOfAccount').text(itemCode);
                $('#confirm-delete-account .accountToDelete').text(account);
                $('#confirm-delete-account').modal("show");
            });

            $(document).on('submit', '#deleteAccount', function (e) {
                e.preventDefault();

                var accountID  = $('.deleteAccountId').val();

                $.ajax({
                    url: "/OneBusiness/bank-accounts/delete",
                    method: "POST",
                    data: { id : accountID },
                    success: function (data) {
                        if(data == "success"){
                            $('#confirm-delete-account').modal("toggle");

                            $("#result").html('<div class="alert alert-success col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspAccount deleted successfully!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                            mainTable.ajax.reload();
                        }else{
                            $('#confirm-delete-account').modal("toggle");
                            $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspSomething went wrong!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                        }
                    },
                    error: function () {
                        $('#confirm-delete-account').modal("toggle");
                        $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                            '</span><em>&nbspSomething went wrong!</em></div></div>');
                        $('#result').fadeIn();
                        $("#result").delay(3000).fadeOut("slow");
                    }
                })

            });

            $(document).on('change', '.bankCode', function () {
                var id = $('.bankCode option:selected').val();
                $('.pcBranchId').val(id);
            })

            $(document).on('change', '.corpName', function () {
                var corpId = $(this).val();
                var options = $(this).parents('form').find('.branchName');

                options.empty();
                //get branches
                var cnt = 0;
                $.ajax({
                    method: 'POST',
                    url: "{!! route('vendors.get_branch') !!}",
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

            })(jQuery);
    </script>
@endsection