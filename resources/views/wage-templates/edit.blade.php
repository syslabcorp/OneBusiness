@extends('layouts.custom')

@section('content')
<form action="{{ route('wage-templates.update', [$template, 'corpID' => request()->corpID]) }}" method="POST">
  {{ csrf_field() }}
  <input type="hidden" name="_method" value="PUT">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="rown">
        <div class="col-md-6">
          <h4>Edit {{ $template->code }}</h4>
        </div>
        <div class="col-md-6 text-right">
        </div>
      </div>
    </div>
    
    <div class="panel-body">
      <div class="rown">
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-2 text-right form-group">
              <label style="margin-top: 8px;">Code:</label>
            </div>
            <div class="col-sm-10 form-group">
              <input type="text" class="form-control" name="code" value="{{ old('code') ?: $template->code }}">
              @if($errors->has('code'))
                <span class="error">{{ $errors->first('code') }}</span>
              @endif
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-2 text-right form-group">
              <label style="margin-top: 8px;">Position:</label>
            </div>
            <div class="col-sm-10 form-group">
              <input type="text" class="form-control" name="position" value="{{ old('position') ?: $template->position }}">
              @if($errors->has('position'))
                <span class="error">{{ $errors->first('position') }}</span>
              @endif
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-2 text-right form-group">
              <label style="margin-top: 8px;">Base Rate:</label>
            </div>
            <div class="col-sm-10 form-group">
              <input type="text" class="form-control" name="base_rate" value="{{ old('base_rate') ?: number_format($template->base_rate, 2, '.', '') }}">
              @if($errors->has('base_rate'))
                <span class="error">{{ $errors->first('base_rate') }}</span>
              @endif
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-2 text-right form-group">
              <label style="margin-top: 8px;">Department:</label>
            </div>
            <div class="col-sm-10 form-group">
              <select name="dept_id" class="form-control">
                @foreach($departments as $department)
                  <option value="{{ $department->dept_ID }}"
                    {{ old('dept_id') ?: $template->dept_id == $department->dept_ID ? 'selected' : '' }}
                    >{{ $department->department }}</option>
                @endforeach
              </select>
              @if($errors->has('dept_id'))
                <span class="error">{{ $errors->first('dept_id') }}</span>
              @endif
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-2"></div>
            <div class="col-sm-10 form-group">
              <div class="rown">
                <div class="col-sm-6 text-center">
                  <label>
                    <input type="checkbox" value="1" name="entry_level"
                      {{ old('entry_level') ?: $template->entry_level == 1 ? 'checked' : '' }}>
                    Entry Level
                  </label>
                </div>
                <div class="col-sm-6 text-center">
                  <label>
                    <input type="checkbox" value="1" name="active"
                      {{ old('active') == 1 ?: $template->active ? 'checked' : '' }}>
                    Active
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Notes:</label>
            <textarea name="notes" rows="6" class="form-control" 
              value="{{ old('notes') ?: $template->notes }}">{{ $template->notes }}</textarea>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="table-responsive" style="max-height: 500px;">
            <table class="table table-striped table-bordered">
              <tbody>
                @foreach($benfItems as $item)
                <tr>
                  @if($loop->index == 0)
                  <td rowspan="{{ count($benfItems) }}" style="vertical-align: middle;">
                    Benefits
                  </td>
                  @endif
                  <td style="width: 30px;">
                    <input type="checkbox" name="details[benf][{{ $item->ID_benf }}]" value="1"
                      {{ $template->details()->where('ID', $item->ID_benf)->where('pay_db', \App\Models\Py\BenfMstr::class)->first() ? 'checked' : '' }}>
                  </td>
                  <td>{{ $item->description }}</td>
                </tr>
                @endforeach
                @foreach($expItems as $item)
                <tr>
                  @if($loop->index == 0)
                  <td rowspan="{{ count($expItems) }}" style="vertical-align: middle;">
                    Benefits
                  </td>
                  @endif
                  <td style="width: 30px;">
                    <input type="checkbox" name="details[exp][{{ $item->ID_exp }}]" value="1"
                      {{ $template->details()->where('ID', $item->ID_exp)->where('pay_db', \App\Models\Py\ExpMstr::class)->first() ? 'checked' : '' }}>
                  </td>
                  <td>{{ $item->description }}</td>
                </tr>
                @endforeach
                @foreach($deductItems as $item)
                <tr>
                  @if($loop->index == 0)
                  <td rowspan="{{ count($deductItems) }}" style="vertical-align: middle;">
                    Benefits
                  </td>
                  @endif
                  <td style="width: 30px;">
                    <input type="checkbox" name="details[deduct][{{ $item->ID_deduct }}]" value="1"
                      {{ $template->details()->where('ID', $item->ID_deduct)->where('pay_db', \App\Models\Py\DeductMstr::class)->first() ? 'checked' : '' }}>
                  </td>
                  <td>{{ $item->description }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="panel-footer">
      <div class="rown">
        <div class="col-sm-6">
          <a class="btn btn-default" href="{{ route('wage-templates.index', ['corpID' => request()->corpID]) }}">
            <i class="fas fa-reply"></i> Back
          </a>
        </div>
        <div class="col-sm-6 text-right">
          <button class="btn btn-success">
            <i class="fas fa-save"></i> Save
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('pageJS')
<script type="text/javascript">
  (() => {
    
  })()
</script>
@endsection