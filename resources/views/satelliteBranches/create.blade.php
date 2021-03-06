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

        input[type="radio"] {
            width:18px; height:18px;
        }

        .checkbox-inline span {
            position: relative;
            bottom: -5px;
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
                        <h3 class="text-center">Create new Satellite Branch</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Add Satellite Branch
                                        </div>
                                        <div class="col-xs-6 text-right">

                                        </div>
                                    </div>

                                </div>
                                <form class="form-horizontal" action="{{ url('/satellite-branch') }}" METHOD="POST" id="form1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-9 col-md-offset-1">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="branchName">Satellite Branch:</label>
                                                    <div class="col-md-5">
                                                        <input id="branchName" name="branchName" type="text" value="{{ old('branchName') }}"
                                                               data-parsley-required-message="Branch name is required" class="form-control input-md"
                                                               data-parsley-maxlength-message="The template name may not be greater than 30 characters"
                                                               data-parsley-maxlength="30" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="branchDescription">Description:</label>
                                                    <div class="col-md-7">
                                                        <input id="branchDescription" name="branchDescription" type="text" value="{{ old('branchDescription') }}"
                                                               class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="branchNotes">Notes:</label>
                                                    <div class="col-md-7">
                                                        <input id="branchNotes" name="branchNotes" type="text" value="{{ old('branchNotes') }}"
                                                               class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-gorup">
                                                        <label for="itemActive" style="margin-left: -7px;" class="col-md-3 col-xs-3 control-label">Active:</label>
                                                    <div class="col-md-8 col-xs-8 pull-left">
                                                        <input type="checkbox" style="margin-top: 7px;" name="itemActive" {{ old('itemActive') ? 'checked' : '' }} class="itemActive" />
                                                    </div>
                                                </div>
                                                <div class="form-gorup">
                                                    <label style="margin-left: -7px;" class="col-md-3 col-xs-3 control-label">Corporation:</label>
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            @foreach($corporations as $key => $val)
                                                            <label class="radio-inline">
                                                                <input type="radio" name="corporation" style="margin-top: -2px" {{ old('corporation') ? 'checked' : '' }} value="{{ $val->corp_id }}"><span>{{ $val->corp_name }}</span>
                                                            </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="{{ url('/satellite-branch') }}" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</a>
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