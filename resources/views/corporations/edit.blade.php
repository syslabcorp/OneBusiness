@extends('layouts.custom')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">Edit Corporation</div>
    <div class="panel-body">
      <form class="form-horizontal" method="POST" action="{{ route('corporations.update', $corporation) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            
            <div class="form-group">
              <label for="Corporation" class="col-md-4 control-label">Corporation</label>
              <div class="col-md-6">
                <input type="text" class="form-control required" name="corp_name"  value="{{ old('corp_name') ?: $corporation->corp_name }}">
                @if ($errors->has('corp_name'))
                  <span class="error">{{ $errors->first('corp_name') }}</span>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label for="Database" class="col-md-4 control-label">Database Name</label>
              <div class="col-md-6">
                <input type="text" class="form-control required" name="database_name" value="{{ old('database_name') ?: $corporation->database_name }}">
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
                        <option {{ (old('corp_type') ?: $corporation->corp_type) == $type->corp_type ? "selected" : "" }} value="{{ $type->corp_type }}">{{ $type->corp_type }}</option>
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
                  <button type="submit" class="btn btn-success pull-right save_button">
                    Save
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