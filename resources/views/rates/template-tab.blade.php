@if($action == 'new')
  @include('rates.create')
@elseif($action == 'edit')
  @include('rates.edit')
@else
<div class="col-md-12 text-center">
  <label for="">Template name:</label>
  <select id="rate-template-name" class="form-control" style="width: 300px;display:inline-block;">
    @foreach($branch->rates()->get() as $template)
    <option value="{{ $template->template_id }}" {{ $rate->template_id == $template->template_id ? "selected" : "" }}
      data-href="{{ route('branchs.rates.index', [$branch, 'template_id' => $template->template_id]) }}"
      >{{ $template->template_name }}</option>
    @endforeach
  </select>
  @if($rate)
  <label for="">Color:</label>
  <div class="color-picker" style="background: {{ $rate->Color }};pointer-events: none;"></div>
  @endif
  <hr style="margin: 10px 0px 0px 0px;">
</div>

<div class="col-md-4">
  <h4>Miscellaneous</h4>
  <div class="row">
    <div class="control-radio col-xs-6">
      <input type="radio" disabled="true" {{ $rate->charge_mode == 1 ? "checked" : ""}}>
      <label>Per Min</label>
    </div>
    <div class="control-radio col-xs-6">
      <input type="radio" disabled="true" {{ $rate->charge_mode == 5 ? "checked" : ""}}>
      <label >Per 5 Mins</label>
    </div>
  </div>
  <hr style="margin: 10px 0px;">
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <div class="control-checkbox" style="margin-top: 5px;">
          <input type="checkbox" disabled="true" {{ $rate->MinimumChrg ? "checked" : ""}}>
          <label>Change Minimum </label>
        </div>
      </div>
      <div class="col-xs-6">
        <input type="number" class="form-control" style="width: 100px;display:inline-block;"
          disabled="true" value="{{ $rate->MinimumTime }}"> (mins)
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <label for="">Timezone 1:</label>
        <input type="time" class="form-control" disabled="true" value="{{ $rate->ZoneStart1 }}">
      </div>
      <div class="col-xs-6">
        <label for="">Discount 2:</label>
        <input type="number" class="form-control" disabled="true" value="{{ $rate->Discount1 }}">
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <label for="">Timezone 2:</label>
        <input type="time" class="form-control" disabled="true" value="{{ $rate->ZoneStart2 }}">
      </div>
      <div class="col-xs-6">
        <label for="">Discount 2:</label>
        <input type="number" class="form-control" disabled="true" value="{{ $rate->Discount2 }}">
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <label for="">Timezone 3:</label>
        <input type="time" class="form-control" disabled="true" value="{{ $rate->ZoneStart3 }}">
      </div>
      <div class="col-xs-6">
        <label for="">Discount 31:</label>
        <input type="number" class="form-control" disabled="true" value="{{ $rate->Discount3 }}">
      </div>
    </div>
  </div>
  <hr>
  <div class="form-group">
    <div class="row">
      <div class="col-xs-6">
        <div class="control-checkbox" style="margin-top: 25px;">
          <input type="checkbox" disabled="true" {{ $rate->DiscStubPrint ? "checked" : ""}}>
          <label>Enable Discount Stub</label>
        </div>
      </div>
      <div class="col-xs-6">
        <label for="">Discount Stub Validity:</label>
        <input type="number" class="form-control" style="width: 100px;display:inline-block;"
          disabled="true" value="{{ $rate->DiscValidity }}"> (days)
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="">Discount Stub Msg:</label>
    <input type="text" class="form-control" disabled="true" value="{{ $rate->DiscStubMsg }}">
  </div>
  <hr style="margin: 0px 0px 10px 0px;">
  <div class="form-group text-center">
    <a class="btn btn-sm btn-success" href="{{ route('branchs.rates.index', [$branch, 'action' => 'new']) }}">
      <i class="fa fa-plus"></i> New
    </a>
    @if($rate)
    <a class="btn btn-sm btn-info" href="{{ route('branchs.rates.index', [$branch, 'action' => 'edit', 'template_id' => $rate->template_id]) }}">
      <i class="fa fa-pencil"></i> Edit
    </a>
    @endif
    <a class="btn btn-sm btn-default" href="{{ route('branchs.index') }}">
      <i class="fa fa-reply"></i> Back
    </a>
  </div>
</div>
<div class="col-md-8" style="border-left: 1px solid #d2d2d2;padding: 0px;">
  @if($rate->charge_mode == 1)
  <form action="{{ route('branchs.rates.details', [$branch, $rate]) }}" method="POST">
    <input type="hidden" name="_method" value="PUT">
    {{ csrf_field() }}
    <table class="table borderred">
      <thead>
        <tr>
          <th></th>
          <th colspan="2">Time Zone 1</th>
          <th colspan="2">Time Zone 2</th>
          <th colspan="2">Time Zone 3</th>
        </tr>
        <tr>
          <th>Stn</th>
          <th>Min Change</th>
          <th>Per Minute</th>
          <th>Min Change</th>
          <th>Per Minute</th>
          <th>Min Change</th>
          <th>Per Minute</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rate->details()->get() as $detail)
        <tr>
          <td style="vertical-align: middle;">{{ $detail->nKey }}</td>
          <td>
            <input type="number" step="any" class="form-control" value="{{ $detail->MinAmt1 }}" name="detail[{{ $detail->nKey }}][MinAmt1]">
          </td>
          <td>
            <input type="number" class="form-control" value="{{ $detail->Net_2 }}" name="detail[{{ $detail->nKey }}][Net_1]">
          </td>
          <td>
            <input type="number" class="form-control" value="{{ $detail->MinAmt2 }}" name="detail[{{ $detail->nKey }}][MinAmt2]">
          </td>
          <td>
            <input type="number" class="form-control" value="{{ $detail->Net_2 }}" name="detail[{{ $detail->nKey }}][Net_2]">
          </td>
          <td>
            <input type="number" class="form-control" value="{{ $detail->MinAmt3 }}" name="detail[{{ $detail->nKey }}][MinAmt3]">
          </td>
          <td>
            <input type="number" class="form-control" value="{{ $detail->Net_3 }}" name="detail[{{ $detail->nKey }}][Net_3]">
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <hr>
    <div class="col-md-12">
      <button class="btn btn-sm btn-success">
        <i class="fa fa-save"></i> Save
      </button>
    </div>
  </form>
  @else
  <form action="{{ route('branchs.rates.details', [$branch, $rate]) }}" method="POST">
    <input type="hidden" name="_method" value="PUT">
    {{ csrf_field() }}
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#zone1" aria-controls="zone1" role="tab" data-toggle="tab">Timezone 1</a></li>
      <li role="presentation"><a href="#zone2" aria-controls="zone2" role="tab" data-toggle="tab">Timezone 2</a></li>
      <li role="presentation"><a href="#zone3" aria-controls="zone3" role="tab" data-toggle="tab">Timezone 3</a></li>
    </ul>

    <div class="tab-content">
      @for($i = 1; $i <= 3; $i++)
      <div role="tabpanel" class="tab-pane {{ $i == 1 ? 'active' : ''}}" id="zone{{ $i }}">
        <table class="table borderred">
          <thead>
            <tr>
              <th>Stn</th>
              <th>5 mins</th>
              <th>10 mins</th>
              <th>15 mins</th>
              <th>20 mins</th>
              <th>25 mins</th>
              <th>30 mins</th>
              <th>35 mins</th>
              <th>40 mins</th>
              <th>45 mins</th>
              <th>50 mins</th>
              <th>55 mins</th>
              <th>60 mins</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rate->details()->get() as $detail)
            <tr>
              <td>{{ $detail->nKey }}</td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_5"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_5]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_10"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_10]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_15"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_15]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_20"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_20]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_25"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_25]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_30"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_30]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_35"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_35]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_40"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_40]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_45"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_45]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_50"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_50]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_55"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_55]">
              </td>
              <td>
                <input type="text" class="form-control" value="{{ $detail["Z{$i}min_60"] }}" 
                  name="detail[{{ $detail->nKey }}][Z{{$i}}min_60]">
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endfor
    </div>
    <hr>
    <div class="col-md-12">
      <button class="btn btn-sm btn-success">
        <i class="fa fa-save"></i> Save
      </button>
    </div>
  </form>
  @endif
</div>
@endif
