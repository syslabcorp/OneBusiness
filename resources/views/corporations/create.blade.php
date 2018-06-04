@extends('layouts.custom')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">{{isset($detail_edit->corp_id) ? "Edit " : "Add " }}Corporation</div>
    <div class="panel-body">
      <form class="form-horizontal" method="POST" action="{{ route('corporations.store') }}">
            {{ csrf_field() }}
            <input type="hidden" name="old_db_name"  value="{{isset($detail_edit->database_name) ? $detail_edit->database_name : "" }}">
            
            <div class="form-group">
              <label for="Corporation" class="col-md-4 control-label">Corporation</label>
              <div class="col-md-6">
                <input type="text" class="form-control required" name="corp_name"  value="{{ old('corp_name') }}">
                @if ($errors->has('corp_name'))
                  <span class="error">{{ $errors->first('corp_name') }}</span>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label for="Database" class="col-md-4 control-label">Database Name</label>
              <div class="col-md-6">
                <input type="text" class="form-control required" name="database_name" value="{{ old('database_name') }}">
                @if ($errors->has('database_name'))
                  <span class="error">
                    {{ $errors->first('database_name') }}
                  </span>
                @endif
              </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">Business Type</label>
                <div class="col-md-6">
                  <select class="form-control required" name="corp_type">
                    <option value="">Choose Corporation Type</option>
                      @foreach ($types as $type) 
                        @if($type->corp_type != "")
                        <option {{ (old('corp_type') == $type->corp_type) ? "selected" : "" }} value="{{ $type->corp_type }}">{{ $type->corp_type }}</option>
                        @endif
                      @endforeach
                  </select>
                  @if ($errors->has('corp_type'))
                    <span class="error">
                      {{ $errors->first('corp_type') }}
                    </span>
                  @endif
                </div>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                  <a class="btn btn-default" href="{{ route('corporations.index') }}">
                      <i class="fas fa-reply"></i> Back
                  </a>
              </div>
              <div class="col-md-6">
                  <button type="submit" class="btn btn-primary pull-right save_button">
                        {{isset($detail_edit->corp_id) ? "Save " : "Create " }}
                    </button>
              </div>
            </div>
        </form>
    </div>
</div>
<script>
$(function(){
    $("#corporationform").validate();   
});
</script>
@endsection