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
      <select class="form-control" onchange="statusChange(event, 'expense')"
        {{ $action == 'new' ? 'disabled' : '' }}>
        <option {{ $status == 1 ? 'selected' : '' }} value="1">Active</option>
        <option {{ $status == 0 ? 'selected' : '' }} value="0">Inactive</option>
      </select>
    </div>
  </div>
  <div class="col-md-12" style="margin-top: 15px;">
    <form action="{{ route('payrolls.expense', ['corpID' => $corpID, 'tab' => $tab, 'status' => $status]) }}" method="POST">
      {{ csrf_field() }}
      <input type="hidden" name="id" value="{{ $expItem->ID_exp }}">
      <input type="hidden" name="active" value="0">
      <input type="hidden" name="fixed_amt" value="0">
      <input type="hidden" name="perctg" value="0">
      <input type="hidden" name="incl_gross" value="0">
      <div class="rown">
        <div class="col-md-2 text-right">
          Expense Name:
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <select class="form-control listDeductions" onchange="itemChange(event, 'expense');">
              @foreach($expItems as $item)
              <option value="{{ $item->ID_exp }}"
                {{ $item->ID_exp == $expItem->ID_exp ? 'selected' : '' }}
                >{{ $item->description }}</option>
              @endforeach
            </select>
            <input type="text" class="form-control" name="description" style="display: none;"
              value="{{ $expItem->description }}" validation="required|max:50" validation-label="Name">
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-4 col-md-offset-2">
          <div class="form-group">
            <label>
              <input type="checkbox" name="incl_gross" onclick="return false;" value="1"
                {{ $expItem->incl_gross ? 'checked' : '' }}>
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
                {{ $expItem->active ? 'checked' : '' }}> Active
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
                <option {{ $expItem->category == $key + 1 ? 'selected' : '' }} 
                  value="{{ $key + 1 }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-2 text-right">
          Type of Expense:
        </div>
      </div>
      <div class="rown">
        <div class="col-md-8 col-md-offset-2 form-group">
          <div class="rown">
            <div class="col-md-2">
              <label>
                <input type="hidden" name="type" value="0">
                <input type="radio" name="type" onclick="return false;" value="3"
                  {{ $expItem->type == 3 ? 'checked' : '' }}> Less
              </label>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" disabled validation="number" 
                value="{{ number_format($expItem->fixed_amt, 2, '.', '') }}" name="fixed_amt">
            </div>
            <div class="col-md-2">
              pesos of
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
                  {{ $expItem->type == 2 ? 'checked' : '' }}> Less
              </label>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" disabled validation="number"
                value="{{ number_format($expItem->perctg, 2, '.', '') }}" name="perctg">
            </div>
            <div class="col-md-2">
              % per
            </div>
            <div class="col-md-4 has-group-line middle">
              <div class="groupLine">
                <div class="vertical"></div>
                <div class="horizontal"></div>
              </div>
              <select name="period" class="form-control" disabled>
                @foreach($periodOptions as $key => $value)
                <option value="{{ $key + 1 }}"
                  {{ $key + 1 == $expItem->period ? 'selected' : '' }}
                  >{{ $value }}</option>
                @endforeach
              </select>
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
                  {{ $expItem->type == 4 ? 'checked' : '' }}> Refer to table below
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="rown table-wages" style="display: {{ $expItem->type == 4 ? 'block' : 'none' }};">
        <div class="col-md-8 col-md-offset-2">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Wages From</th>
                  <th>Wages To</th>
                  <th>EE Share</th>
                  <th>ER Share</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($expItem->details()->orderBy('range_1', 'ASC')->get() as $key => $detail)
                <tr>
                  <td>
                    <input type="text" class="form-control" value="{{ number_format($detail->range_1, 2, '.', '') }}"
                    readonly name="details[{{ $key }}][range_1]"  validation="number">
                  </td>
                  <td>
                    <input type="text" class="form-control" value="{{ number_format($detail->range_2, 2, '.', '') }}" 
                    readonly name="details[{{ $key }}][range_2]"  validation="number">
                  </td>
                  <td>
                    <input type="text" class="form-control" value="{{ number_format($detail->emp_share, 2, '.', '') }}"
                    readonly name="details[{{ $key }}][emp_share]"  validation="number">
                  </td>
                  <td>
                    <input type="text" class="form-control" value="{{ number_format($detail->empr_share, 2, '.', '') }}"
                    readonly name="details[{{ $key }}][empr_share]"  validation="number">
                  </td>
                  <td>
                    <button class="btn btn-md btn-danger btn-remove-row" title="Delete" type="button" disabled>
                      <i class="glyphicon glyphicon-trash"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
                @if(count($expItem->details) == 0)
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