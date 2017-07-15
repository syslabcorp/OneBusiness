<form action="{{ route('branchs.misc', [$branch, '#misc']) }}" method="POST" class="col-md-12" novalidate>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <div class="row">
        <div class="col-md-6">
            <h3>STUB SETTINGS</h3>
            <div class="form-group">
                <label class="control-label">Stub Header:</label>
                <input type="text" class="form-control"  name="StubHdr" value="{{ $branch->StubHdr }}">
            </div>
            <div class="form-group">
                <label class="control-label">Stub Message:</label>
                <textarea name="StubMsg" cols="30" rows="5" class="form-control">{{ $branch->StubMsg }}</textarea>
            </div>
            <div class="form-group">
                <div class="control-checkbox">
                    <input type="checkbox" id="print-active" name="is_enable_printing" value="1" {{ $branch->is_enable_printing == 1 ? 'checked' : ''}}>
                    <label for="print-active">Enable Stub Printing</label>
                </div>
            </div>
            <h3>LOAD CENTRAL</h3>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label class="control-label">Load Central UID:</label>
                    </div>
                    <div class="col-xs-6">
                        <input type="text" class="form-control"  name="lc_uid" value="{{ $branch->lc_uid }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label class=" control-label">Load Central Password:</label>
                    </div>
                    <div class="col-xs-6">
                        <input type="text" class="form-control"  name="lc_pwd" value="{{ $branch->lc_pwd }}">
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('receiving_mobile_number') ? 'has-error' : '' }}">
                <div class="row">
                    <div class="col-xs-6">
                        <label class=" control-label">Receiving Mobile Number:</label>
                    </div>
                    <div class="col-xs-6">
                        <input type="text" class="form-control"  name="receiving_mobile_number" value="{{ $branch->to_mobile_num }}">
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
                        <input type="number" class="form-control"  name="max_eload_amt" value="{{ $branch->max_eload_amt }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h3>OTHERS</h3>
            <div class="form-group">
                <label class=" control-label">Bus Center MAC Address:</label>
                <input type="text" class="form-control" placeholder="00-00-00-00-00"   name="MAC_Address" value="{{ $branch->MAC_Address }}">
            </div>
            <div class="form-group">
                <label class=" control-label">Cashier IP Address:</label>
                <input type="text" class="form-control" name="cashier_ip" value="{{ $branch->cashier_ip }}">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">Cancel Station Allowance: (mins)</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="RollOver" value="{{ $branch->RollOver }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">Block Cancel-Transfer Duration: (mins)</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="TxfrRollOver" value="{{ $branch->TxfrRollOver }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">POS Printer Port:</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="PosPtrPort" value="{{ $branch->PosPtrPort }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-8">
                        <label class=" control-label">Vacant Online Station Logging Timeout: (mins)</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="number" class="form-control"  name="susp_ping_timeout" value="{{ $branch->susp_ping_timeout }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    @if(\Auth::user()->checkAccess("Miscellaneous Settings", "E"))
    <div class="form-group text-right">
        <div class="col-md-12">
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </div>
    @endif
</form>