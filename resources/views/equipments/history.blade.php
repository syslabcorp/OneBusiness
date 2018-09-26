<div class="tab-pane fade {{ $tab == 'history' ? 'active in' : '' }}" id="equipHistory">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    @foreach($histories as $group)
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $loop->index }}"
            style="display: block;">
            <strong>{{ $group->first()->log_at }}</strong>
          </a>
        </h4>
      </div>
      <div id="collapse-{{ $loop->index }}" class="panel-collapse collapse {{ $loop->index == 0 ? 'in' : '' }}" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <ul class="list-unstyled listHistories">
            @foreach($group as $history)
            <li>
              <a>
                <strong>
                @if($history->item)
                  Part #{{ $history->item_id }} - {{ $history->item->description }}
                @else
                  Equipment #{{ $history->equipment_id }} - {{ $history->equipment->description }}
                @endif
                </strong>
              </a>
              {{ $history->content }} by
              <strong> {{ $history->changedBy->UserName }}</strong>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>