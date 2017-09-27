<?php

namespace App\Http\Controllers;

use App\Checkbook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get records
        $checkbooks = Checkbook::orderBy('used', 'ASC')
            ->orderBy('order_num', 'ASC')
            ->get();
        return view('checkbooks.index')
            ->with('checkbooks', $checkbooks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Checkbook  $checkbook
     * @return \Illuminate\Http\Response
     */
    public function show(Checkbook $checkbook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checkbook  $checkbook
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkbook $checkbook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checkbook  $checkbook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkbook $checkbook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checkbook  $checkbook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkbook $checkbook)
    {
        //
    }
}
