<h3 class="text-center">STATION MAC ADDRESS SETTINGS</h3>
<hr>

<form action="{{ route('branchs.macs.store', [$branch, '#mac']) }}" method="POST" novalidate>
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12 text-center">
            @if(\Auth::user()->checkAccess("Transfer", "E"))
            <button class="btn btn-primary btn-md" id="transfer-mac" type="button">
                Transfer
            </button>
            @endif
            @if(\Auth::user()->checkAccess("Swap", "E"))
            <button class="btn btn-primary btn-md" id="swap-station" type="button">
                Swap
            </button>
            @endif
            @if(\Auth::user()->checkAccess("Assign IP", "E"))
            <button class="btn btn-primary btn-md" id="assign-ip" type="button">
                Assign IP Series
            </button>
            <button class="btn btn-primary btn-md" id="assign-ip-range" type="button">
                Assign IP Range
            </button>
            @endif
            @if(\Auth::user()->checkAccess("MAC Addresses", "E"))
            <button class="btn btn-primary btn-md">
                Save
            </button>
            @endif
        </div>
    </div>
    <hr>

    <table class="table list-macs">
        <thead>
            <tr>
                <th>#</th>
                <th>Alias</th>
                <th>Net</th>
                <th>MAC Address</th>
                <th>IP Address</th>
                <th>Last Updated By</th>
                <th>Last Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branch->macs as $mac)
            <tr data-id="{{ $mac->txn_id }}">
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    <input type="hidden" name="mac[{{ $mac->txn_id }}][is_modify]">
                    <input type="text" class="form-control" placeholder="Alias" name="mac[{{ $mac->txn_id }}][PC_No]" value="{{ $mac->PC_No }}">
                </td>
                <td>
                    <div class="control-checkbox">
                        <input type="checkbox" id="net-{{ $loop->index }}" name="mac[{{ $mac->txn_id }}][StnType]" value="1"
                            {{ $mac->StnType ? "checked" : "" }}>
                        <label for="net-{{ $loop->index }}">&nbsp;</label>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control" placeholder="00-00-00-00-00" name="mac[{{ $mac->txn_id }}][Mac_Address]" value="{{ !empty(old("mac.{$mac->txn_id}.Mac_Address")) ? old("mac.{$mac->txn_id}.Mac_Address") : $mac->Mac_Address }}">
                    @if($errors->has("mac.{$mac->txn_id}.Mac_Address"))
                    <i style="color:#cc0000;">{{ preg_replace("/mac.{$mac->txn_id}.Mac_Address/", "Mac Address",$errors->first("mac.{$mac->txn_id}.Mac_Address")) }}</i>
                    @endif
                </td>
                <td class="ip-address">
                    <input type="text" class="form-control" placeholder="IP Address" name="mac[{{ $mac->txn_id }}][IP_Addr]" value="{{ $mac->IP_Addr }}">
                </td>
                <td>
                    @if($mac->user)
                        {{ $mac->user->Username }}
                    @endif
                </td>
                <td>
                    @if($mac->LastChgMACDate)
                        {{ $mac->LastChgMACDate->format('m/d/Y H:i') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

<div id="swap-station-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Swap Station</h4>
      </div>
      <div class="modal-body">
        <form action="{{ route('branchs.footers.swap', [$branch]) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
            <input type="hidden" name="mac_id">
            <div class="form-group">
                <label for="">Select Branch</label>
                <select name="branch" class="form-control" id="branch-select">
                    <option value="">Select Branch</option>
                    @foreach($branchs as $selectBranch)
                        <option value="{{ $selectBranch->Branch }}">{{ $selectBranch->ShortName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Select Station</label>
                <select name="target_id" class="form-control" id="station-select">
                    <option value="">Select Station</option>
                    @foreach(\App\Mac::all() as $mac)
                        <option value="{{ $mac->txn_id }}" data-branch="{{ $mac->Branch }}">{{ $mac->PC_No }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-success">Swap</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="transfer-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Transfer Mac</h4>
      </div>
      <div class="modal-body">
        <form action="{{ route('branchs.footers.transfer', [$branch]) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
            <input type="hidden" name="mac_id">
            <div class="form-group">
                <label for="">Select Branch</label>
                <select name="branch_id" class="form-control branch-select">
                    <option value="">Select Branch</option>
                    @foreach($branchs as $selectBranch)
                        <option value="{{ $selectBranch->Branch }}">{{ $selectBranch->ShortName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Select Station</label>
                <select name="target_id" class="form-control station-select">
                    <option value="">Select Station</option>
                    @foreach(\App\Mac::all() as $mac)
                        <option value="{{ $mac->txn_id }}" data-branch="{{ $mac->Branch }}">{{ $mac->PC_No }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>New MAC Address</label>
                <input type="text" name="Mac_Address" placeholder="00-00-00-00-00-00" class="form-control">
            </div>
            <div class="form-group">
                <button class="btn btn-success">Transfer</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="assign-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Ip Series</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="">Start IP Address</label>
            <input type="text" class="form-control">
        </div>
        <div class="form-group">
            <button class="btn btn-success">Apply</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="assign-range-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Ip Range</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="">Field Range <i>(1-20, 27, 30,...)</i></label>
            <input type="text" class="form-control" name="range">
        </div>
        <div class="form-group">
            <label for="">Start IP Address</label>
            <input type="text" class="form-control" name="IP_Addr">
        </div>
        <div class="form-group">
            <button class="btn btn-success">Apply</button>
        </div>
      </div>
    </div>
  </div>
</div>