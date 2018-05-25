<?php
namespace App\Transformers\T;

use League\Fractal;
use App\Models\T\Depts;

class DeptsTransformer extends Fractal\TransformerAbstract
{
    public function transform(Depts $item)
    {
        return [
            'dept_ID' => (int) $item->dept_ID,
            'department' => $item->department,
            'main' => (int) $item->main
        ];
    }
}