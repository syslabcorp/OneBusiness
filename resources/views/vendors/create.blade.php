@extends('layouts.app')
@section('header-scripts')
    <link href="css/parsley.css" rel="stylesheet" >
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
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Create new Vendor</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Create new Vendor
                                        </div>
                                        <div class="col-xs-6 text-right">

                                        </div>
                                    </div>

                                </div>
                                <form class="form-horizontal" action="{{ url('/vendors') }}" METHOD="POST" id="form1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="vendorName">Vendor Name:</label>
                                                    <div class="col-md-7">
                                                        <input id="vendorName" name="vendorName" type="text" value="{{ old('vendorName') }}"
                                                               data-parsley-required-message="Vendor name is required" class="form-control input-md"
                                                               data-parsley-maxlength-message="Vendor name should not exceed 80 characters"
                                                               data-parsley-maxlength="80" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="payTo">Pay To:</label>
                                                    <div class="col-md-7">
                                                        <input id="payTo" name="payTo" type="text" value="{{ old('payTo') }}" class="form-control input-md"
                                                                data-parsley-maxlength-message="Pay To should not exceed 80 characters"
                                                                data-parsley-required-message="Pay To is required"
                                                                data-parsley-maxlength="80" required >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="address">Address:</label>
                                                    <div class="col-md-7">
                                                        <textarea name="address" id="address" cols="30" class="form-control input-md" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="contactPerson">Contact Person:</label>
                                                    <div class="col-md-5">
                                                        <input id="contactPerson" name="contactPerson" type="text" value="{{ old('contactPerson') }}"
                                                               class="form-control input-md"
                                                               data-parsley-maxlength-message="Contact person should not exceed 80 characters"
                                                               data-parsley-maxlength="80">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="telNo1">Tel. No-Line 1:</label>
                                                    <div class="col-md-5">
                                                        <input id="telNo1" name="telNo1" type="text" value="{{ old('telNo1') }}" class="form-control input-md"
                                                                data-parsley-maxlength-message="Tel 1 should not exceed 20 characters"
                                                                data-parsley-maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="telNo2">Tel. No-Line 2:</label>
                                                    <div class="col-md-5">
                                                        <input id="telNo2" name="telNo2" type="text" value="{{ old('telNo2') }}" class="form-control input-md"
                                                                data-parsley-maxlength-message="Tel 2 should not exceed 20 characters"
                                                                data-parsley-maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="cellphone">Cellphone No:</label>
                                                    <div class="col-md-5">
                                                        <input id="cellphone" name="cellphone" type="text" value="{{ old('vendorName') }}" class="form-control input-md" 
                                                                data-parsley-maxlength-message="Cell No should not exceed 20 characters"
                                                                data-parsley-maxlength="20">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="checkbox" style="margin-top: 7px;" name="payeesAccountOnly" {{ old('itemActive') ? 'checked' : '' }} class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="payeesAccountOnly" style="position: relative; top: -20px;">Checks Deposit to Payees Account Only</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="checkbox" name="usageTracking" {{ old('itemActive') ? 'checked' : '' }} class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="usageTracking" style="position: relative; top: -20px;">Enable Usage Tracking</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <hr class="wide">
                                                <span class="visibiltyLbl">Visibility</span>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="radio" style="margin-top: 7px;" name="visibilityInfo" checked value="1" {{ old('itemActive') ? 'checked' : '' }} class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="visibilityInfo" style="position: relative; top: -19px;">CDS Only</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="radio" style="margin-top: 7px;" name="visibilityInfo" value="2" {{ old('itemActive') ? 'checked' : '' }} class="itemActive" />
                                                        <div style="margin-left: 30px">
                                                            <label for="visibilityInfo" style="position: relative; top: -19px;">Petty Cash Only</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group noMargin">
                                                    <div class="col-md-12 col-xs-12">
                                                        <input type="radio" style="margin-top: 7px;" name="visibilityInfo" value="3" {{ old('itemActive') ? 'checked' : '' }} class="itemActive" />
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