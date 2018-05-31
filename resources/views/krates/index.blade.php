@extends('layouts.custom')

@section('content')
<section class="content rate-page">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4>
          Branch: {{ $branch->ShortName }}

          @if($rate->tmplate_id && \Auth::user()->checkAccessById(2, "E"))
          <a class="btn btn-md btn-info  pull-right" href="{{ route('branchs.krates.edit', [$branch, $rate, 'tmplate_id' => $rate->tmplate_id]) }}">
            <i class="fas fa-pencil-alt"></i> Edit
          </a>
          @endif
          @if(\Auth::user()->checkAccessById(2, "A"))
            <a class="btn btn-md btn-success pull-right" href="{{ route('branchs.krates.create', [$branch]) }}"
              style="margin-right: 10px;">
              <i class="fa fa-plus"></i> New
            </a>
          @endif
        </h4>
      </div>
      <div class="panel-body edit-branch">
        <div>
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#template" aria-controls="template" role="tab" data-toggle="tab">Rates Template</a>
            </li>
          </ul>
          
        </div>
          <div class="tab-content">
            <div class="col-md-12 text-center">
              <select id="rate-template-name" class="form-control" style="width: 300px;display:inline-block;">
                @foreach($branch->krates()->get() as $template)
                <option value="{{ $template->tmplate_id }}" {{ $rate->tmplate_id == $template->tmplate_id ? "selected" : "" }}
                  data-href="{{ route('branchs.krates.index', [$branch, 'tmplate_id' => $template->tmplate_id]) }}"
                  >{{ $template->tmplate_name }}</option>
                @endforeach
              </select>
              <div class="form-group" style="display:inline-block;margin-left: 10px;">
                <input type="checkbox" name="active" {{ $rate->active == 1 ? 'checked' : ''}}  onclick="return false;"/>
                <label>Active</label>
              </div>
            </div>
            <form action="{{ route('branchs.krates.store', [$branch]) }}" method="POST">
              {{ csrf_field() }}
              @include('krates.table')
            </form>
            <hr class="col-md-12">
            <div class="col-md-12 text-left">
              <a class="btn btn-md btn-default" href="{{ route('branchs.index', ['corpID' => $branch->corp_id]) }}">
                <i class="fa fa-reply"></i> Back
              </a>
            </div>
          </div>
      </div>
    </div>
  </div>
</section>
@endsection