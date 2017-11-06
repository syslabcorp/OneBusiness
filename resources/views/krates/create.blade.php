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
              <li role="presentation" class="active">
                <a href="#template" aria-controls="template" role="tab" data-toggle="tab">Rates Template</a>
              </li>
            </ul>

            <div class="tab-content">
              <form action="{{ route('branchs.krates.store', [$branch]) }}" method="POST">
                  {{ csrf_field() }}
                <div class="col-md-12 text-center">
                  <div class="form-group {{ $errors->has('tmplate_name') ? 'has-error' : '' }}"
                      style="display:inline-block;">
                    <label for="">Template name:</label>
                    <input type="text" class="form-control" name="tmplate_name" style="width: 300px;display:inline-block;" value="{{ old('tmplate_name') }}">
                    @if($errors->has('tmplate_name'))
                      <span class="help-block">{{ preg_replace("/tmplate/", "template", $errors->first('tmplate_name')) }}</span>
                    @endif
                  </div>
                  <div class="form-group" style="display:inline-block;margin-left: 10px;vertical-align:top;">
                    <input id="active" type="checkbox" name="active" {{ $branch->active == 1 ? 'checked' : ''}} value="1"/>
                    <label for="active">Active</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table borderred">
                      <thead>
                        <tr>
                          <th>Room</th>
                          <th>1Hr</th>
                          @for($i = 2; $i <= 24; $i++)
                          <th>{{ $i }}Hrs</th>
                          @endfor
                          <th>MinChrg</th>
                          <th>nKey</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($branch->macs()->get() as $mac)
                        <tr>
                          <td>
                            {{ $loop->index + 1 }}
                          </td>
                          @for($i = 1; $i <= 24; $i++)
                          <td>
                            <input style="width: 55px" type="text" class="form-control" name="detail[{{ $loop->index }}][Hr_{{ $i }}]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true" value="0.00">
                          </td>
                          @endfor
                          <td>
                          <input style="width: 55px" type="text" class="form-control" name="detail[{{ $loop->index }}][MinAmt1]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true" value="0.00">
                          </td>
                          <td>
                            <input type="hidden" name="detail[{{ $loop->index }}][nKey]" value="{{ $mac->nKey }}">
                            {{ $mac->nKey }}
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                @if(\Auth::user()->checkAccessById(2, "E"))
                  <div class="box-assign nohide">
                    <hr>
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table borderred">
                          <thead>
                            <tr>
                              <th></th>
                              <th>1Hr</th>
                              @for($i = 2; $i <= 24; $i++)
                              <th>{{ $i }}Hrs</th>
                              @endfor
                              <th>MinChrg</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <button class="btn btn-sm btn-success" type="button">
                                  <i class="fa fa-magic"></i>
                                </button>
                              </td>
                              @for($i = 1; $i <= 25; $i++)
                              <td>
                                <input style="width: 60px" type="text" step="any" class="form-control" placeholder="0.00">
                              </td>
                              @endfor
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  @endif
                  <hr class="col-md-12">
                  @if(\Auth::user()->checkAccessById(2, "E"))
                  <div class="col-md-12 text-right">
                    <button class="btn btn-sm btn-success btn-save">
                      <i class="fa fa-save"></i> Create
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