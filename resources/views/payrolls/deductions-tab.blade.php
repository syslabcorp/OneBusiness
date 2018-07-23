<div class="rown">
  <div class="col-md-2 col-xs-6">
    <div class="row">
      <div class="col-xs-3" style="margin-top: 7px;">
        <label>Filters:</label>
      </div>
      <div class="col-xs-9">
        <select name="corpID" class="form-control changePageCompany">
          @foreach($companies as $company)
          <option value="{{ $company->corp_id }}"
            {{ $company->corp_id == $corpID ? 'selected' : '' }}>{{ $company->corp_name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <div class="col-md-2 col-xs-6">
    <div class="form-group">
      <select class="form-control" onchange="statusChange(event, 'deduct')"
        {{ $action == 'new' ? 'disabled' : '' }}>
        <option {{ $status == 1 ? 'selected' : '' }} value="1">Active</option>
        <option {{ $status == 0 ? 'selected' : '' }} value="0">Inactive</option>
      </select>
    </div>
  </div>
  <div class="col-md-12" style="margin-top: 15px;">
    <form action="{{ route('payrolls.deduct', ['corpID' => $corpID, 'tab' => $tab, 'status' => $status]) }}" method="POST">
      {{ csrf_field() }}
      <input type="hidden" name="id" value="{{ $deductItem->ID_deduct }}">
      <input type="hidden" name="active" value="0">
      <input type="hidden" name="fixed_amt" value="0">
      <input type="hidden" name="total_amt" value="0">
      <input type="hidden" name="incl_gross" value="0">
      <div class="rown">
        <div class="col-md-2 text-right">
          Deduction Name:
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <select class="form-control listDeductions" onchange="itemChange(event, 'deduct');">
              @foreach($deductItems as $item)
              <option value="{{ $item->ID_deduct }}"
                {{ $item->ID_deduct == $deductItem->ID_deduct ? 'selected' : '' }}
                >{{ $item->description }}</option>
              @endforeach
            </select>
            <input type="text" class="form-control" name="description" style="display: none;"
              value="{{ $deductItem->description }}" validation="required|max:50" validation-label="Name">
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-4 col-md-offset-2">
          <div class="form-group">
            <label>
              <input type="checkbox" name="incl_gross" onclick="return false;" value="1"
                {{ $deductItem->incl_gross ? 'checked' : '' }}>
              Included in Gross Pay & 13th Month Pay
            </label>
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-4 col-md-offset-2">
          <div class="form-group">
            <label>
              <input type="checkbox" name="active" onclick="return false;" value="1"
                {{ $deductItem->active ? 'checked' : '' }}> Active
            </label>
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-2 text-right">
          Column <br>
          (on spreadsheet report):
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <select class="form-control" name="category" disabled>
              @foreach($columnOptions as $key => $value)
                <option {{ $deductItem->category == $key + 1 ? 'selected' : '' }} 
                  value="{{ $key + 1 }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-2 text-right">
          Type of Deduction:
        </div>
      </div>
      <div class="rown">
        <div class="col-md-8 col-md-offset-2 form-group">
          <div class="rown">
            <div class="col-md-2">
              <label>
                <input type="hidden" name="type" value="0">
                <input type="radio" name="type" onclick="return false;" value="3"
                  {{ $deductItem->type == 3 ? 'checked' : '' }}> Equals
              </label>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" disabled validation="number" 
                value="{{ $deductItem->type == 3 ? number_format($deductItem->fixed_amt, 2, '.', '') : '0.00' }}" name="fixed_amt">
            </div>
            <div class="col-md-2">
              out of
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" disabled validation="number"
                value="{{ number_format($deductItem->total_amt, 2, '.', '') }}" name="total_amt">
            </div>
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-8 col-md-offset-2 form-group">
          <div class="rown">
            <div class="col-md-2">
              <label>
                <input type="radio" name="type" onclick="return false;" value="2"
                  {{ $deductItem->type == 2 ? 'checked' : '' }}> Equals
              </label>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" disabled validation="number"
                value="{{$deductItem->type == 2 ? number_format($deductItem->fixed_amt, 2, '.', '') : '0.00'}}" name="fixed_amt">
            </div>
            <div class="col-md-2">
              pesos of
            </div>
            <div class="col-md-6">
            </div>
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-8 col-md-offset-2 form-group">
          <div class="rown">
            <div class="col-md-6">
              <label>
                <input type="radio" name="type" onclick="return false;" value="4"
                  {{ $deductItem->type == 4 ? 'checked' : '' }}> Refer to table below
              </label>
            </div>
            <div class="col-md-4 has-group-line">
              <div class="groupLine">
                <div class="vertical"></div>
                <div class="horizontal"></div>
              </div>
              <select name="period" class="form-control" disabled>
                @foreach($periodOptions as $key => $value)
                <option value="{{ $key + 1 }}"
                  {{ $key + 1 == $deductItem->period ? 'selected' : '' }}
                  >{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="rown table-wages" style="display: {{ $deductItem->type == 4 ? 'block' : 'none' }};">
        <div class="col-md-8 col-md-offset-2">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>From</th>
                  <th>To</th>
                  <th>Multiplier</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($deductItem->details as $key => $detail)
                <tr>
                  <td>
                    <input type="text" class="form-control" value="{{ number_format($detail->range1, 2, '.', '') }}"
                    readonly name="details[{{ $key }}][range1]">
                  </td>
                  <td>
                    <input type="text" class="form-control" value="{{ number_format($detail->range2, 2, '.', '') }}" 
                    readonly name="details[{{ $key }}][range2]"  validation="number">
                  </td>
                  <td>
                    <input type="text" class="form-control" value="{{ number_format($detail->multi, 2, '.', '') }}"
                    readonly name="details[{{ $key }}][multi]"  validation="number">
                  </td>
                  <td>
                    <button class="btn btn-md btn-danger btn-remove-row" title="Delete" type="button" disabled>
                      <i class="far fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
                @if(count($deductItem->details) == 0)
                <tr class="empty">
                  <td colspan="5">
                    No items
                  </td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
          <div>
            <button class="btn btn-xs btn-default btn-reset" type="button" disabled>
              Reset Table
            </button>
            <button class="btn btn-xs btn-default btn-add" type="button" disabled>
              Add Row(F2)
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>