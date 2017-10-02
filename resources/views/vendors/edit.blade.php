@extends('layouts.app')
@section('header-scripts')
    <link href="/css/parsley.css" rel="stylesheet" >
    <style>
        .panel-footer {
            background-color: #FFF;
        }

        .form-horizontal {
            font-size: 0.8em;
        }

        .panel-body {
            padding: 15px !important;
        }

        .modal {
            z-index: 10001 !important;;
        }

        input[type="checkbox"] {
            width:18px; height:18px;
        }

        .checkbox-inline span {
            position: relative;
            bottom: -5px;
        }

        .wide {
            border: 1px solid #ddd;
        }

        .visibiltyLbl {
            position: relative;
            top: -20px;
            font-weight: bold;
        }

        .noMargin {
            margin: 0;
        }

        .pullLeft {
            position: relative;
            text-align: left;
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
                    @if(Session::has('alert-class'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @elseif(Session::has('flash_message'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Edit Vendor</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Edit Vendor
                                        </div>
                                        <div class="col-xs-6 text-right">

                                        </div>
                                    </div>

                                </div>
                                <form class="form-horizontal" action="{{ route('vendors.update', $vendor->Supp_ID) }}" METHOD="POST" id="form1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="vendorName">Vendor Name:</label>
                                                    <div class="col-md-7">
                                                        <input id="vendorName" name="vendorName" type="text" value="{{ $vendor->VendorName }}"
                                                               data-parsley-required-message="Vendor name is required" class="form-control input-md" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="payTo">Pay To:</label>
                                                    <div class="col-md-7">
                                                        <input id="payTo" name="payTo" type="text" value="{{ $vendor->PayTo }}"
                                                               class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="address">Address:</label>
                                                    <div class="col-md-7">
                                                        <textarea name="address" id="address" cols="30" class="form-control input-md" rows="3"
                                                        >{{ $vendor->Address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="contactPerson">Contact Person:</label>
                                                    <div class="col-md-5">
                                                        <input id="contactPerson" name="contactPerson" type="text" value="{{ $vendor->ContactPerson }}"
                                                               data-parsley-required-message="Contact person is required" class="form-control input-md" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="telNo1">Tel. No-Line 1:</label>
                                                    <div class="col-md-5">
                                                        <input id="telNo1" name="telNo1" type="text" value="{{ $vendor->TelNo }}" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="telNo2">Tel. No-Line 2:</label>
                                                    <div class="col-md-5">
                                                        <input id="telNo2" name="telNo2" type="text" value="{{ $vendor->OfficeNo }}" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="cellphone">Cellphone No:</label>
                                                    <div class="col-md-5">
                                                        <input id="cellphone" name="cellphone" type="text" value="{{ $vendor->CelNo }}" class="form-control input-md" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="checkbox" style="margin-top: 7px;" name="payeesAccountOnly"  @if($vendor->x_check == 1) checked @endif class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="payeesAccountOnly" style="position: relative; top: -20px;">Checks Deposit to Payees Account Only</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="checkbox" style="margin-top: 7px;" name="usageTracking" @if($vendor->withTracking == 1) checked @endif class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="usageTracking" style="position: relative; top: -20px;">Enable Usage Tracking</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <hr class="wide">
                                                <span class="visibiltyLbl">Visibility</span>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="radio" style="margin-top: 7px;" name="visibilityInfo" value="1"  @if($vendor->petty_visible == 1) checked @endif class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="visibilityInfo" style="position: relative; top: -19px;">CDS Only</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="radio" style="margin-top: 7px;" name="visibilityInfo" value="2"  @if($vendor->petty_visible == 2) checked @endif class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="visibilityInfo" style="position: relative; top: -19px;">Petty Cash Only</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="radio" style="margin-top: 7px;" name="visibilityInfo" value="3"  @if($vendor->petty_visible == 3) checked @endif class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="visibilityInfo" style="position: relative; top: -19px;">CDS and Petty Cash</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="{{ url('/vendors') }}" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</a>
                                            </div>
                                            <div class="col-sm-6">
                                                {!! csrf_field() !!}
                                                {{ method_field('PUT') }}
                                                <button type="submit" class="btn btn-success pull-right createBtn">Create</button>
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
    </div>
@endsection
@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>
    <script>
        (function($){

            $('#form1').parsley();

        })(jQuery);
    </script>
@endsection