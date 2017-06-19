@extends('layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Edit branch #{{ $branch->id }}</h3>
            </div>
            <div class="box-body edit-branch">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#branch-details" aria-controls="home" role="tab" data-toggle="tab">Branch Details</a></li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Miscellaneous Settings</a></li>
                    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">MAC Addresses</a></li>
                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Sub Footer</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="branch-details">
                        @include('branchs.edit-tab')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">...</div>
                    <div role="tabpanel" class="tab-pane" id="messages">...</div>
                    <div role="tabpanel" class="tab-pane" id="settings">...</div>
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