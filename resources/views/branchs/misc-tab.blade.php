<form action="{{ route('branchs.misc', [$branch, '#misc']) }}" method="POST" class="col-md-12" novalidate>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <div class="row">
        <div class="col-md-6">
            <h3>STUB SETTINGS</h3>
            <div class="form-group">
                <label class="control-label">Stub Header:</label>
                <input type="text" class="form-control"  name="StubHdr" value="{{ $branch->StubHdr }}"
                    {{ \Auth::user()->checkAccess("Miscellaneous Settings", "E") ? "" : "readonly" }}>
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