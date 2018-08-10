<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

use App\Corporation;

class ImageController extends Controller
{
    public function getImage()
    {
        $company = Corporation::find(request()->corpID);

        $file = $company->imgfile_path . '/' . request()->filename;

        if (file_exists($file)) {
            return response(file_get_contents($file))->header('Content-type','image/jpeg');
        }

        abort(404); 
    }
}
