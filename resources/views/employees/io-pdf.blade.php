<html>
  <head>
    <style>
      table {
        width: 100%;
      }
    </style>
  </head>
  <body>
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Position</td>
          <td>Start Date</td>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $branch => $groups)
          <tr>
            <td style="padding: 5px 10px;">
              @php $branch = \App\Branch::where('Branch', $branch)->first() @endphp
              {{ $branch ? $branch->ShortName : '' }}
            </td>
            <td></td>
            <td></td>
          </tr>
          @foreach($groups->groupBy('split_type') as $type => $group)
            <tr>
              <td style="padding: 5px 10px;">
                <div style="margin-left: 30px;">
                  <strong>
                    {{ $type }} GROUP
                  </strong>
                </div>
              </td>
              <td></td>
              <td></td>
            </tr>
            @foreach($group as $user)
              <tr>
                <td style="padding: 5px 10px;">
                  <div style="margin-left: 60px;">
                    {{ $user->UserName }}
                  </div>
                </td>
                <td>{{ $user->StartDate }}</td>
                <td>{{ $user->template }}</td>
              </tr>
            @endforeach
          @endforeach
        @endforeach
      </tbody>
    </table>
  </body>
</html>