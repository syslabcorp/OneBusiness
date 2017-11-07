@if($branch->company->corp_type == 'INN')
  <form action="{{ route('branchs.misc', [$branch, '#misc']) }}" method="POST" class="col-md-12" novalidate>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <div class="row">
        <div class="col-md-6">
          <h3>CHARGING OPTIONS</h3>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group {{ $errors->has('CancelAllowance') ? 'has-error' : '' }}">
                <div class="row">
                  <div class="col-xs-9">
                    <label class=" control-label">Cancel Check-in Allowance:</label>
                  </div>
                  <div class="col-xs-3">
                    <input type="text" class="form-control"  name="CancelAllowance" value="{{ !empty(old("CancelAllowance")) ? old("CancelAllowance") : $branch->CancelAllowance }}"
                      {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                  </div>
                </div>
                @if($errors->has('CancelAllowance'))
                  <span class="help-block">{{ preg_replace("/cancel allowance/", "Cancel Check-in Allowance",$errors->first("CancelAllowance")) }}</span>
                @endif
              </div>
              <div class="form-group {{ $errors->has('TrnsfrAllowance') ? 'has-error' : '' }}">
                <div class="row">
                  <div class="col-xs-9">
                    <label class=" control-label">Transfer Room Allowance:</label>
                  </div>
                  <div class="col-xs-3">
                    <input type="text" class="form-control"  name="TrnsfrAllowance" value="{{ !empty(old("TrnsfrAllowance")) ? old("TrnsfrAllowance") : $branch->TrnsfrAllowance }}"
                      {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                  </div>
                </div>
                @if($errors->has('TrnsfrAllowance'))
                  <span class="help-block">{{ $errors->first("TrnsfrAllowance") }}</span>
                @endif
              </div>
              <div class="form-group {{ $errors->has('RmTimerAlert') ? 'has-error' : '' }}">
                <div class="row">
                  <div class="col-xs-9">
                    <label class=" control-label">Alert Room Time Expires Before:</label>
                  </div>
                  <div class="col-xs-3">
                    <input type="text" class="form-control"  name="RmTimerAlert" value="{{ !empty(old("RmTimerAlert")) ? old("RmTimerAlert") : $branch->RmTimerAlert }}"
                      {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                  </div>
                </div>
                @if($errors->has('RmTimerAlert'))
                  <span class="help-block">{{ $errors->first("RmTimerAlert") }}</span>
                @endif
              </div>
              <div class="form-group {{ $errors->has('RmOffAllowance') ? 'has-error' : '' }}">
                <div class="row">
                  <div class="col-xs-9">
                    <label class=" control-label">Room Power Off Delay:</label>
                  </div>
                  <div class="col-xs-3">
                    <input type="text" class="form-control"  name="RmOffAllowance" value="{{ !empty(old("RmOffAllowance")) ? old("RmOffAllowance") : $branch->RmOffAllowance }}"
                      {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                  </div>
                </div>
                @if($errors->has('RmOffAllowance'))
                  <span class="help-block">{{ $errors->first("RmOffAllowance") }}</span>
                @endif
              </div>

              <div class="form-group {{ $errors->has('CarryOverMins') ? 'has-error' : '' }}">
                <div class="row">
                  <div class="col-xs-9">
                    <label class=" control-label">Charge Next Hour Allowance:</label>
                  </div>
                  <div class="col-xs-3">
                    <input type="text" class="form-control"  name="CarryOverMins" value="{{ !empty(old("CarryOverMins")) ? old("CarryOverMins") : $branch->CarryOverMins }}"
                      {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                  </div>
                </div>
                @if($errors->has('CarryOverMins'))
                  <span class="help-block">{{ $errors->first("CarryOverMins") }}</span>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group {{ $errors->has('MinimumChrg_Mins') ? 'has-error' : '' }}">
                <div class="row">
                  <div class="col-xs-12">
                    <input id="Chrg_Min" type="checkbox" name="Chrg_Min" {{ $branch->Chrg_Min == 1 ? "checked" : "" }} value="1"/>
                    <label class="control-label" for="Chrg_Min">Charge Minimum (mins):</label>

                    <input type="text" style="width: 80px;display:inline-block;" class="form-control"  name="MinimumChrg_Mins" value="{{ !empty(old("MinimumChrg_Mins")) ? old("MinimumChrg_Mins") : $branch->MinimumChrg_Mins }}"
                      {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                  </div>
                </div>
                @if($errors->has('MinimumChrg_Mins'))
                  <span class="help-block">{{ $errors->first("MinimumChrg_Mins") }}</span>
                @endif
              </div>
              <div class="form-group">
                <input type="checkbox" name="ChkInOveride" id="ChkInOveride" {{ $branch->ChkInOveride == 1 ? 'checked' : ''}} value="1">
                <label for="ChkInOveride">Check-in Override Request</label>
              </div>
              <div class="form-group">
                <input type="checkbox" name="ChkOutOveride" id="ChkOutOveride" {{ $branch->ChkOutOveride == 1 ? 'checked' : ''}} value="1">
                <label for="ChkOutOveride">Check-out Override Request</label>
              </div>
            </div>
          </div>
          
        </div>
        <div class="col-md-6">
          <h3>STUB SETTINGS</h3>
          <div class="form-group {{ $errors->has('StubHdr') ? 'has-error' : '' }}">
            <label class="control-label">Stub Header:</label>
            <input type="text" class="form-control"  name="StubHdr" value="{{ !empty(old("StubHdr")) ? old("StubHdr") : $branch->StubHdr }}"
                {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
            @if($errors->has('StubHdr'))
                <span class="help-block">{{ preg_replace("/stub hdr/", "Stub Header",$errors->first("StubHdr")) }}</span>
            @endif
          </div>
          <div class="form-group">
            <label class="control-label">Stub Message:</label>
            <textarea name="StubMsg" cols="30" rows="5" class="form-control"
                {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>{{ $branch->StubMsg }}</textarea>
          </div>
          <div class="form-group">
            <div class="control-checkbox">
                <input type="checkbox" id="print-active" name="StubPrint" value="1" {{ $branch->StubPrint == 1 ? 'checked' : ''}}>
                <label for="{{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "print-active" : "" }}">Enable Stub Printing</label>
            </div>
          </div>
        </div>

        <div class="col-md-12" style="margin-bottom: 15px;">
            <hr>
            <a href="{{ route('branchs.index', ['corpID' => $branch->corp_id]) }}" class="btn btn-default pull-left">
                <i class="fa fa-reply"></i> Back
            </a>
            @if(\Auth::user()->checkAccess("Miscellaneous Settings", "E"))
                <button type="submit" class="btn btn-success pull-right">Update</button>
            @endif
        </div>
    </div>
  </form>
@else
<form action="{{ route('branchs.misc', [$branch, '#misc']) }}" method="POST" class="col-md-12" novalidate>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <div class="row">
        <div class="col-md-6">
            <h3>STUB SETTINGS</h3>
            <div class="form-group {{ $errors->has('StubHdr') ? 'has-error' : '' }}">
                <label class="control-label">Stub Header:</label>
                <input type="text" class="form-control"  name="StubHdr" value="{{ !empty(old("StubHdr")) ? old("StubHdr") : $branch->StubHdr }}"
                    {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                @if($errors->has('StubHdr'))
                    <span class="help-block">{{ preg_replace("/stub hdr/", "Stub Header",$errors->first("StubHdr")) }}</span>
                @endif
            </div>
            <div class="form-group">
                <label class="control-label">Stub Message:</label>
                <textarea name="StubMsg" cols="30" rows="5" class="form-control"
                    {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>{{ $branch->StubMsg }}</textarea>
            </div>
            <div class="form-group">
                <div class="control-checkbox">
                    <input type="checkbox" id="print-active" name="StubPrint" value="1" {{ $branch->StubPrint == 1 ? 'checked' : ''}}>
                    <label for="{{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "print-active" : "" }}">Enable Stub Printing</label>
                </div>
            </div>
            <h3>LOAD CENTRAL</h3>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label class="control-label">Load Central UID:</label>
                    </div>
                    <div class="col-xs-6">
                        <input type="text" class="form-control"  name="lc_uid" value="{{ $lc_uid }}"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label class=" control-label">Load Central Password:</label>
                    </div>
                    <div class="col-xs-6">
                        <input type="password" class="form-control"  name="lc_pwd" placeholder="******"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('receiving_mobile_number') ? 'has-error' : '' }}">
                <div class="row">
                    <div class="col-xs-6">
                        <label class=" control-label">Receiving Mobile Number:</label>
                    </div>
                    <div class="col-xs-6">
                        <input type="text" class="form-control"  name="receiving_mobile_number" value="{{ $branch->to_mobile_num }}"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                        @if($errors->has('receiving_mobile_number'))
                        <span class="help-block">{{ $errors->first('receiving_mobile_number') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label class=" control-label">Max. Load Limit per Branch:</label>
                    </div>
                    <div class="col-xs-6">
                        <input type="number" class="form-control"  name="max_eload_amt" value="{{ $branch->max_eload_amt }}"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h3>OTHERS</h3>
            <div class="form-group {{ $errors->has('MAC_Address') ? 'has-error' : '' }}">
                <label class=" control-label">Bus Center MAC Address:</label>
                <input type="text" class="form-control" placeholder="00-00-00-00-00"  name="MAC_Address" value="{{ !empty(old("MAC_Address")) ? old("MAC_Address") : $branch->MAC_Address }}"
                    {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                @if($errors->has('MAC_Address'))
                    <span class="help-block">{{ preg_replace("/m a c/", "Mac",$errors->first("MAC_Address")) }}</span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('cashier_ip') ? 'has-error' : '' }}">
                <label class=" control-label">Cashier IP Address:</label>
                <input type="text" class="form-control" name="cashier_ip" value="{{ !empty(old("cashier_ip")) ? old("cashier_ip") : $branch->cashier_ip }}"
                    {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                @if($errors->has('cashier_ip'))
                    <span class="help-block">{{ preg_replace("/cashier ip/", "Cashier IP",$errors->first("cashier_ip")) }}</span>
                @endif
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">Cancel Station Allowance: (mins)</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="RollOver" value="{{ $branch->RollOver }}"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">Block Cancel-Transfer Duration: (mins)</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="TxfrRollOver" value="{{ $branch->TxfrRollOver }}"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">POS Printer Port:</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="PosPtrPort" value="{{ $branch->PosPtrPort }}"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">Vacant Online Station Logging Timeout: (mins)</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="susp_ping_timeout" value="{{ $branch->susp_ping_timeout }}"
                            {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12" style="margin-bottom: 15px;">
            <hr>
            <a href="{{ route('branchs.index', ['corpID' => $branch->corp_id]) }}" class="btn btn-default pull-left">
                <i class="fa fa-reply"></i> Back
            </a>
            @if(\Auth::user()->checkAccess("Miscellaneous Settings", "E"))
                <button type="submit" class="btn btn-success pull-right">Update</button>
            @endif
        </div>
    </div>
</form>
@endif