@extends('layouts.custom')

@section('head')
<title>Edit Branch: {{ $branch->ShortName }}</title>
@endsection


@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>Edit Branch: {{ $branch->ShortName }}</h4>
            </div>
            <div class="panel-body edit-branch">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"><a href="#branch-details" aria-controls="home" role="tab" data-toggle="tab">Branch Details</a></li>
                    <li role="presentation"><a href="#misc" aria-controls="misc" role="tab" data-toggle="tab">Miscellaneous Settings</a></li>
                    @if($branch->company->corp_type == 'INN')
                        <li role="presentation"><a href="#room" aria-controls="room" role="tab" data-toggle="tab">Room Tag Name</a></li>
                    @else
                        <li role="presentation"><a href="#mac" aria-controls="mac" role="tab" data-toggle="tab">MAC Addresses</a></li>
                    @endif
                    <li role="presentation"><a href="#stub-footer" aria-controls="stub-footer" role="tab" data-toggle="tab">Stub Footer</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="branch-details">
                        @if(\Auth::user()->checkAccessById(3, "V"))
                            @include('branchs.edit-tab')
                        @else
                            <div class="alert alert-danger no-close">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="misc">
                        @if(\Auth::user()->checkAccess("Miscellaneous Settings", "V"))
                            @include('branchs.misc-tab')
                        @else
                            <div class="alert alert-danger no-close">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                    @if($branch->company->corp_type == 'INN')
                    <div role="tabpanel" class="tab-pane" id="room">
                        @if(\Auth::user()->checkAccessById(2, "V"))
                            @include('branchs.room-tab')
                        @else
                            <div class="alert alert-danger no-close">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                    @else
                    <div role="tabpanel" class="tab-pane" id="mac">
                        @if(\Auth::user()->checkAccess("MAC Addresses", "V"))
                            @include('branchs.mac-tab')
                        @else
                            <div class="alert alert-danger no-close">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                    @endif
                    <div role="tabpanel" class="tab-pane" id="stub-footer">
                        @if(\Auth::user()->checkAccess("Stub Footer", "V"))
                           @include('branchs.footer-tab')
                        @else
                            <div class="alert alert-danger no-close">
                                You don't have permission
                            </div>
                        @endif
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
</section>
@endsection