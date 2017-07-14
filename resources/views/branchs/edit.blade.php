@extends('layouts.app')

@section('head')
<title>Edit Branch: {{ $branch->Branch }}</title>
@endsection


@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Branch: {{ $branch->Branch }}</h3>
            </div>
            <div class="box-body edit-branch">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#branch-details" aria-controls="home" role="tab" data-toggle="tab">Branch Details</a></li>
                    <li role="presentation"><a href="#misc" aria-controls="misc" role="tab" data-toggle="tab">Miscellaneous Settings</a></li>
                    <li role="presentation"><a href="#mac" aria-controls="mac" role="tab" data-toggle="tab">MAC Addresses</a></li>
                    <li role="presentation"><a href="#stub-footer" aria-controls="stub-footer" role="tab" data-toggle="tab">Stub Footer</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="branch-details">
                        @if(\Auth::user()->checkAccess("Branch Details", "V"))
                            @include('branchs.edit-tab')
                        @else
                            <div class="alert alert-danger">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="misc">
                        @if(\Auth::user()->checkAccess("Miscellaneous Settings", "V"))
                            @include('branchs.misc-tab')
                        @else
                            <div class="alert alert-danger">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="mac">
                        @if(\Auth::user()->checkAccess("MAC Addresses", "V"))
                            @include('branchs.mac-tab')
                        @else
                            <div class="alert alert-danger">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="stub-footer">
                        @if(\Auth::user()->checkAccess("Stub Footer", "V"))
                           @include('branchs.footer-tab')
                        @else
                            <div class="alert alert-danger">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            <div class="box-footer">
                <a href="{{ route('branchs.index') }}" class="btn btn-default">
                    <i class="fa fa-reply"></i> Back
                </a>
            </div>
          </div>
        </div>
      </div>
</section>
@endsection