@extends('layouts.custom')

@section('content')
<section class="content rate-page">
    <div class="col-md-12">
        <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Branch: {{ $branch->ShortName }}</h4>
        </div>
        <div class="panel-body edit-branch">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="#template" aria-controls="template" role="tab" data-toggle="tab">Rates Template</a></li>
                <li role="presentation"><a href="#schedule" aria-controls="schedule" role="tab" data-toggle="tab">Rates Schedule</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane" id="template">
                    @if(\Auth::user()->checkAccess("Rates & Schedule Assignment", "V"))
                        @include('rates.template-tab')
                    @else
                        <div class="alert alert-danger no-close">
                            You don't have permission
                        </div>
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane" id="schedule">
                    @if(\Auth::user()->checkAccess("Rates & Schedule Assignment", "V"))
                        @include('rates.schedule-tab')
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
</section>
@endsection