<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\Corporation;

class WageTemplatesController extends Controller
{
    protected $deptModel;

    public function __construct(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);
      
        $this->deptModel = new \App\Models\T\Depts;
        $this->deptModel->setConnection($company->database_name);
    }

    public function index()
    {
        return view('wage-templates.index', [
        ]);
    }

    public function store()
    {
        $dept = $this->deptModel->create($this->deptParams());

        \Session::flash('success', "New department {$dept->department} has been created");

        return redirect(route('departments.index', ['corpID' => request()->corpID]));
    }

    public function update($id)
    {
        $dept = $this->deptModel->findOrFail($id);
        $dept->update($this->deptParams());

        \Session::flash('success', "Department {$dept->department} has been updated");

        return redirect(route('departments.index', ['corpID' => request()->corpID]));
    }

    public function destroy($id)
    {
        $dept = $this->deptModel->findOrFail($id);
        $dept->delete();

        return response()->json([
            'success' => true
        ]);
    }

    private function deptParams()
    {
        $params = request()->only(['department', 'main']);
        $params['main'] = $params['main'] ?: 0;

        return $params;
    }

}
