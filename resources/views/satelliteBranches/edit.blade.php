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
                        <h3 class="text-center">Edit Satellite Branch</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Edit Satellite Branch
                                        </div>
                                        <div class="col-xs-6 text-right">

                                        </div>
                                    </div>

                                </div>
                                <form class="form-horizontal" action="{{ route('satellite-branch.update', $satelliteBranch->sat_branch) }}" data-form-validate METHOD="POST" id="form1">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-9 col-md-offset-1">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="branchName">Satellite Branch:</label>
                                                    <div class="col-md-5">
                                                        <input id="branchName" name="branchName" type="text" value="{{ $satelliteBranch->short_name }}"
                                                               data-parsley-required-message="Branch name is required" class="form-control input-md" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="branchDescription">Description:</label>
                                                    <div class="col-md-7">
                                                        <input id="branchDescription" name="branchDescription" type="text"
                                                               data-parsley-required-message="Branch description is required" value="{{ $satelliteBranch->description }}" class="form-control input-md" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="branchNotes">Notes:</label>
                                                    <div class="col-md-7">
                                                        <input id="branchNotes" name="branchNotes" type="text" value="{{ $satelliteBranch->notes }}" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-gorup">
                                                    <label for="itemActive" style="margin-left: -7px;" class="col-md-3 col-xs-3 control-label">Active:</label>
                                                    <div class="col-md-8 col-xs-8 pull-left">
                                                        <input type="checkbox" style="margin-top: 7px;" name="itemActive" {{ ($satelliteBranch->active) ? 'checked' : '' }} class="itemActive" />
                                                    </div>
                                                </div>
                                                <div class="form-gorup">
                                                    <label style="margin-left: -7px;" class="col-md-3 col-xs-3 control-label">Corporation:</label>
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            @foreach($corporations as $key => $val)
                                                                <label class="checkbox-inline">
                                                                    <input type="checkbox" name="corporations[]" value="{{ $val->corp_id }}"
                                                                    @if(preg_match('/'.$val->corp_id.'/', $satelliteBranch->corp_id)) checked @endif><span>{{ $val->corp_name }}</span>
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
                                                {{ method_field('PUT') }}
                                                {!! csrf_field() !!}
                                                <button type="submit" class="btn btn-success pull-right createBtn">Save</button>
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

            $('.createBtn').on('click', function() {
                var selectVal1 = $('select#itemProduct').val();
                var selectVal2 = $('select#itemBrand').val();

                if(selectVal1 == ""){
                    $('#itemProduct_chosen').addClass("error-border-ps");
                }

                if(selectVal2 == ""){
                    $('#itemBrand_chosen').addClass("error-border-ps");
                }
            });

        })(jQuery);
    </script>
@endsection