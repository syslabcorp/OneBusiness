@extends('layouts.custom')

@section('content')
  <section class="content rate-page">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>
            Branch: {{ $branch->ShortName }}
            <button class="btn btn-danger cancel-selection" style="float:right;margin-top:0px;display:none;">Cancel Selection</button>
          </h4>
        </div>
        <div class="panel-body edit-branch">
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#template" aria-controls="template" role="tab" data-toggle="tab">Rates Template</a>
              </li>
            </ul>

            <div class="tab-content">
              <form action="{{ route('branchs.krates.update', [$branch, $rate, 'tmplate_id' => $rate->tmplate_id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <div class="col-md-12 text-center">
                  <div class="form-group {{ $errors->has('tmplate_name') ? 'has-error' : '' }}"
                      style="display:inline-block;">
                    <label for="">Template name:</label>
                    <input type="text" class="form-control" name="tmplate_name" style="width: 300px;display:inline-block;" 
                    value="{{ old('tmplate_name') ? old('tmplate_name') : $rate->tmplate_name }}">
                    @if($errors->has('tmplate_name'))
                      <span class="help-block">{{ preg_replace("/tmplate/", "template", $errors->first('tmplate_name')) }}</span>
                    @endif
                  </div>
                  <div class="form-group" style="display:inline-block;margin-left: 10px;vertical-align:top;">
                    <input id="active" type="checkbox" name="active" {{ $rate->active == 1 ? 'checked' : ''}} value="1"/>
                    <label for="active">Active</label>
                  </div>
                </div>
                @include('krates.table')
                <hr class="col-md-12">
                @if(\Auth::user()->checkAccessById(2, "E"))
                <div class="col-md-12 text-right">
                  <a class="btn btn-sm btn-default pull-left" href="{{ route('branchs.krates.index', [$branch]) }}">
                    <i class="fa fa-reply"></i> Back
                  </a>
                  <button class="btn btn-sm btn-success btn-save">
                    <i class="fa fa-save"></i> Save
                  </button>
                </div>
                @endif
              </form>
            </div>
        </div>
      </div>
    </div>
  </section>
@endsection