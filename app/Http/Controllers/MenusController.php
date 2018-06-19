<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Request;
use App\Models\Menu;

class MenusController extends Controller
{
    public function order($id)
    {

        $menus = Menu::where('parent_id', 0)->orderBy('sort')->get();

        foreach($menus as $index => $menu) {
            $menu->update([
                'sort' => $index
            ]);
        }

        $menu = Menu::findOrFail($id);

        if (request()->order == 'up') {
            $preMenu = Menu::where('parent_id', 0)->where('sort', '<', $menu->sort)
                            ->orderBy('sort', 'DESC')->first();

            if ($preMenu) {
                $menu->update([
                    'sort' => $preMenu->sort
                ]);
                $preMenu->update([
                    'sort' => $preMenu->sort + 1
                ]);
            } else {
                $menu->update([
                    'sort' => 0
                ]);
            }
        } else {
            $nextMenu = Menu::where('parent_id', 0)->where('sort', '>', $menu->sort)
                            ->orderBy('sort', 'ASC')->first();

            if ($nextMenu) {
                $menu->update([
                    'sort' => $nextMenu->sort
                ]);
                $nextMenu->update([
                    'sort' => $nextMenu->sort - 1
                ]);
            } else {
                $menu->update([
                    'sort' => count($menus) - 1
                ]);
            }
        }

        \Session::flash('success', 'Menu has been updated');

        return redirect()->back();
    }
}