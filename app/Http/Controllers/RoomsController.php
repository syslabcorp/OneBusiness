<?php

namespace App\Http\Controllers;

use App\Mac;
use App\Branch;
use Illuminate\Http\Request;
use Validator;

class RoomsController extends Controller
{

  public function store(Request $request, Branch $branch)
  {
    if(!\Auth::user()->checkAccessById(2, "E"))
    {
      \Session::flash('error', "You don't have permission"); 
      return redirect(route('branchs.index')); 
    }

    $this->validate($request, [
      'room.*.RmTag' => 'max:15|nullable'
    ]);
    $roomParams = $request->get('room');
    foreach($roomParams as $key => $roomParam) {
      $room = $branch->rooms()
                     ->where('RmIndex', '=', $key)->first();
      $room->RmTag = $roomParam['RmTag'];
      $room->save();
    }

    \Session::flash('success', "Room Staus has been updated.");
    return redirect(route('branchs.edit', [$branch, '#room']));
  }
}
