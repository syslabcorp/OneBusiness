<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Footer;
use Illuminate\Http\Request;

class FootersController extends Controller
{

    public function store(Request $request, Branch $branch)
    {
        if(!\Auth::user()->checkAccess("Stub Footer", "A")) {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }

        $this->validate($request,[
            'content' => 'required',
        ]);
        $footer = $branch->footers()->orderBy('sort', 'DESC')->first();
        $sort = 1;
        if($footer)
        {
            $sort = $footer->sort + 1;
        }
        $params = [];
        $params['Foot_Text'] = $request->get('content');
        $params['sort'] = $sort;
        $status = $branch->footers()->create($params);

        \Session::flash('success', "Stub Footer has been created!");

        return redirect(route('branchs.edit', [$branch, '#stub-footer']));
    }

    public function show(Post $post)
    {
        //
    }

    public function edit(Branch $branch)
    {
        return view('branchs.edit', [
            'branch' => $branch
        ]);
    }

    public function update(Request $request, Branch $branch, $id)
    {
        if(!\Auth::user()->checkAccess("Stub Footer", "E")) {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }

        $footer = $branch->footers()->find($id);

        if($request->get('sort'))
        {
            if($request->get('sort') == 'up')
            {
                $exchangeFooter = $branch->footers()->where('sort', '<', $footer->sort)
                    ->orderBy('sort', 'DESC')->first();
            }else
            {
                $exchangeFooter = $branch->footers()->where('sort', '>', $footer->sort)
                    ->orderBy('sort', 'ASC')->first();
            }

            if($exchangeFooter)
            {
                $tempPosition = $footer->sort;
                $footer->update(['sort' => $exchangeFooter->sort]);
                $exchangeFooter->update(['sort' => $tempPosition]);
            }

            \Session::flash('success', "Stub Footer #{$footer->Foot_ID} has been updated!");
        }else
        {
            if(!empty($request->get('content')) && $footer->update(['Foot_Text' => $request->get('content')]))
            {
                \Session::flash('success', "Stub Footer #{$footer->Foot_ID} has been updated!");
            }else
            {
                \Session::flash('error', "Stub Footer #{$footer->Foot_ID} update failed!");
            }
        }
        

        return redirect(route('branchs.edit', [$branch, '#stub-footer']));
    }

    public function destroy(Request $request, Branch $branch, $id)
    {
        if(!\Auth::user()->checkAccess("Stub Footer", "D")) {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }

        $footer = $branch->footers()->find($id);

        if($footer->delete())
        {
            \Session::flash('success', "Stub Footer #{$footer->Foot_ID} has been removed!");
        }else
        {
            \Session::flash('error', "Stub Footer #{$footer->Foot_ID} remove failed!");
        }

        return redirect(route('branchs.edit', [$branch, '#stub-footer'])); 
    }

    public function copy(Request $request, Branch $branch, $id)
    {
        if(!\Auth::user()->checkAccess("Stub Footer", "E")) {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }

        $targetBranch = Branch::find($request->get('target'));

        $footer = $branch->footers()->find($id);
        
        if(!$targetBranch) {
            \Session::flash('error', "Branch can't be blank"); 
            return redirect(route('branchs.edit', [$branch, '#stub-footer'])); 
        }

        if($targetBranch->Branch == $branch->Branch)
        {
            \Session::flash('error', "Can't copy to current branch");
            return redirect(route('branchs.edit', [$branch, '#stub-footer'])); 
        }
        $targetBranch->footers()->delete();
        $sort = 1;

        foreach($branch->footers()->orderBy('sort', 'ASC')->get() as $footer)
        {
            $targetBranch->footers()->create([
                'Foot_Text' => $footer->Foot_Text,
                'sort' => $sort
            ]);
            $sort += 1;
        }
            
        \Session::flash('success', "Stub Footer has been copied to {$targetBranch->ShortName}!");

        return redirect(route('branchs.edit', [$branch, '#stub-footer'])); 
    }
}
