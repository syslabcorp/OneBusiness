<h3 class="text-center">ROOM TAG SETTINGS</h3>
<hr>

<form action="{{ route('branchs.rooms.store', [$branch, '#room']) }}" method="POST" novalidate>
  {{ csrf_field() }}
  <table class="table list-macs">
    <thead>
      <tr>
        <th>#</th>
        <th>Room</th>
        <th>Last Updated By</th>
        <th>Last Updated At</th>
      </tr>
    </thead>
      <tbody>
        @foreach($branch->rooms as $room)
        <tr>
          <td>{{ $room->RmIndex }}</td>
          <td>
              <input type="text" class="form-control" placeholder="Tag" name="room[{{ $room->RmIndex }}][RmTag]" value="{{ !empty(old("room.{$room->RmIndex}.RmTag")) ? old("room.{$room->RmIndex}.RmTag") : $room->RmTag }}"
                  {{ \Auth::user()->checkAccessById(2, "E") ? "" : "readonly" }} >
              @if($errors->has("room.{$room->RmIndex}.RmTag"))
              <i style="color:#cc0000;">{{ preg_replace("/room.{$room->RmIndex}.RmTag/", "Room Tag",$errors->first("room.{$room->RmIndex}.RmTag")) }}</i>
              @endif
          </td>
          <td>

            @if($room->updatedBy)
                  {{ $room->updatedBy->UserName }}
              @endif
          </td>
          <td>
              @if($room->last_update)
                  {{ $room->last_update->format('m/d/Y H:i') }}
              @endif
          </td>
        </tr>
        @endforeach
      </tbody>
  </table>

  <div class="col-md-12" style="margin-bottom: 15px;">
    <hr>
    <a href="{{ route('branchs.index', ['corpID' => $branch->corp_id]) }}" class="btn btn-default pull-left">
        <i class="fa fa-reply"></i> Back
    </a>
    @if(\Auth::user()->checkAccessById(2, "E"))
        <button type="submit" class="btn btn-success pull-right">Update</button>
    @endif
  </div>
</form>