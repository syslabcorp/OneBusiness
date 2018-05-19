<div class="rown">
  <div class="col-md-2 text-right col-md-offset-10">
    <div class="form-group">
      <select class="form-control" onchange="statusChange(event, 'benefit')"
        {{ $action == 'new' ? 'disabled' : '' }}>
        <option {{ $status == 1 ? 'selected' : '' }} value="1">Active</option>
        <option {{ $status == 0 ? 'selected' : '' }} value="0">Inactive</option>
      </select>
    </div>
  </div>
  <div class="col-md-12">
    <form action="{{ route('payrolls.benefit', ['corpID' => $corpID, 'tab' => $tab, 'status' => $status]) }}" method="POST">
      {{ csrf_field() }}
      <input type="hidden" name="id" value="{{ $benfItem->ID_benf }}">
      <input type="hidden" name="active" value="0">
      <input type="hidden" name="fixed_amt" value="0">
      <input type="hidden" name="perctg" value="0">
      <input type="hidden" name="incl_gross" value="0">
      <div class="rown">
        <div class="col-md-2 text-right">
        Benefit Name:
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <select class="form-control listDeductions" onchange="itemChange(event, 'benefit');">
              @foreach($benfItems as $item)
              <option value="{{ $item->ID_benf }}"
                {{ $item->ID_benf == $benfItem->ID_benf ? 'selected' : '' }}
                >{{ $item->description }}</option>
              @endforeach
            </select>
            <input type="text" class="form-control" name="description" style="display: none;"
              value="{{ $benfItem->description }}" validation="required|max:50" validation-label="Name">
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-4 col-md-offset-2">
          <div class="form-group">
            <label>
              <input type="checkbox" name="incl_gross" onclick="return false;" value="1"
                {{ $benfItem->incl_gross ? 'checked' : '' }}>
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
                {{ $benfItem->active ? 'checked' : '' }}> Active
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
                <option {{ $benfItem->category == $key + 1 ? 'selected' : '' }} 
                  value="{{ $key + 1 }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="rown">
        <div class="col-md-2 text-right">
          Type of Benefit:
        </div>
      </div>
      <div class="rown">
        <div class="col-md-8 col-md-offset-2 form-group">
          <div class="rown">
            <div class="col-md-2">
              <label>
                <input type="hidden" name="type" value="0">
                <input type="radio" name="type" onclick="return false;" value="3"
                  {{ $benfItem->type == 3 ? 'checked' : '' }}> Plus
              </label>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" disabled validation="number" 
                value="{{ number_format($benfItem->fixed_amt, 2, '.', '') }}" name="fixed_amt">
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
                  {{ $benfItem->type == 2 ? 'checked' : '' }}> Plus
              </label>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" disabled validation="number"
                value="{{ number_format($benfItem->perctg, 2, '.', '') }}" name="perctg">
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
                  {{ $key + 1 == $deductItem->period ? 'selected' : '' }}
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
                  {{ $benfItem->type == 4 ? 'checked' : '' }}> Refer to table below
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="rown table-wages" style="display: {{ $benfItem->type == 4 ? 'block' : 'none' }};">
        <div class="col-md-8 col-md-offset-2">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Wages From</th>
                  <th>Wages To</th>
                  <th>Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($benfItem->details()->orderBy('range_1', 'ASC')->get() as $key => $detail)
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
                    <button class="btn btn-sm btn-primary btn-edit-row" title="Edit" type="button" disabled>
                      <i class="glyphicon glyphicon-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-remove-row" title="Delete" type="button" disabled>
                      <i class="glyphicon glyphicon-trash"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
                @if(count($benfItem->details) == 0)
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