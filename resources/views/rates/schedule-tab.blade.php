<div class="col-md-4">
  <h4>View Rates Schedule By:</h4>
  <form method="GET">
    <div class="form-group">
      <label for="">Year</label>
      <select name="year" class="form-control">
        @for($i = 2010; $i < 2030; $i++)
        <option value="{{ $i }}" {{ $i == $year ? "selected" : "" }}>{{ $i }}</option>
        @endfor
      </select>
    </div>
    <div class="form-group">
      <label for="">Months</label>
      <div class="clearfix"></div>
      @for($i = 1; $i <= 12; $i++)
      @php $month = date_create("01-$i-2017") @endphp
      <div class="control-checkbox" style="display:inline-block;margin: 2px 20px 2px 0px;">
        <input type="checkbox" name="months[]" id="{{ $month->format('F') }}" value="{{ $month->format('n') }}"
          {{ array_search($month->format('n'), $months) !== false ? "checked" : "" }}>
        <label for="{{ $month->format('F') }}">{{ $month->format('F') }}</label>
      </div>
      @endfor
    </div>
    <hr>
    <div class="form-group text-center">
      <button class="btn btn-sm btn-primary">
        Filter
      </button>
      <button class="btn btn-sm btn-success" data-toggle="modal" type="button"
        data-target="#assign-rate-template">
        Assign Template
      </button>
      <a class="btn btn-sm btn-default" href="{{ route('branchs.index') }}">
        Back
      </a>
    </div>
  </form>
</div>
<div class="col-md-8" style="border-left: 1px solid #d2d2d2;padding: 0px;">
  <table class="table borderred">
    <thead>
    <tr>
      <th>Month</th>
      <th>Day</th>
      <th>Date</th>
      <th>Template Name</th>
    </tr>
    </thead>
    <tbody>
      @foreach($schedules as $schedule)
      <tr>
        <td>{{ $schedule->rate_date->format('F') }}</td>
        <td style="text-transform: uppercase;">
          @if($schedule->rate_date->format('l') == 'Sunday')
            <i style="font-weight: lighter;">{{ $schedule->rate_date->format('l') }}</i>
          @else
            {{ $schedule->rate_date->format('l') }}
          @endif
        </td>
        <td>{{ $schedule->rate_date->format('d') }}</td>
        <td>{{ $schedule->template->template_name }}</td>
      </tr>
      @endforeach
      @if(!count($schedules))
      <tr>
        <td colspan="4" class="text-center">
          <i>Count not found any schedules</i>
        </td>
      </tr>
      @endif
    </tbody>
  </table>
</div>


<div id="assign-rate-template" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Assign Rate Template</h4>
        </div>
        <div class="modal-body">
          <form action="{{ route('branchs.rates.assign', [$branch]) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
          <div class="row">
            <div class="col-xs-6">
              <div class="form-group">
                <label for="" style="font-weight: 500;">Date From:</label>
                <input type="date" class="form-control" name="start_date">
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label for="" style="font-weight: 500;">Date To:</label>
                <input type="date" class="form-control" name="end_date">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="" style="font-weight: 500;">Days:</label>
            <div class="clearfix">
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="all" id="all">
                <label for="all">All</label>
              </div>
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="Mon" id="mon">
                <label for="mon">Mon</label>
              </div>
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="Tue" id="tue">
                <label for="tue">Tue</label>
              </div>
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="Wed" id="wed">
                <label for="wed">Wed</label>
              </div>
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="Thu" id="thu">
                <label for="thu">Thu</label>
              </div>
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="Fri" id="fri">
                <label for="fri">Fri</label>
              </div>
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="Sat" id="sat">
                <label for="sat">Sat</label>
              </div>
              <div class="control-checkbox" style="display:inline-block;margin-right: 10px;">
                <input type="checkbox" name="days[]" value="Sun" id="sun">
                <label for="sun">Sun</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="" style="font-weight: 500;">Select Template:</label>
            <select name="template_id" class="form-control">
              <option value="">Select Name</option>
              @foreach($branch->rates()->get() as $template)
                <option value="{{ $template->template_id }}">{{ $template->template_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
              <button class="btn btn-success">Apply</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>