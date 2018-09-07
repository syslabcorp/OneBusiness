<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Corporation;
use App\Transformers\Equip\EquipTransformer;

class EquipmentsController extends Controller
{
    public function index(Request $request)
    {
        $hdrModel = new \App\Models\Equip\Hdr;

        $items = $hdrModel->orderBy('asset_id');

        if (request()->branch) {
            $items = $items->where('branch', request()->branch);
        }

        if (request()->department) {
            $items = $items->where('dept_id', request()->department);
        }

        $items = $items->get();

        return fractal($items, new EquipTransformer(request()->corpID))->toJson();
    }

    public function destroy($id)
    {
        $item = \App\Models\Equip\Hdr::find($id);

        return response()->json([
            'success' => true
        ]);
    }
}
