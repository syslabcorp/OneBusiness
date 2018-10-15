<?php
namespace App\Transformers\Item;

use League\Fractal;
use App\Models\Item\Master;

class MasterTransformer extends Fractal\TransformerAbstract
{
    public function transform(Master $item)
    {
        return [
            'item_id' => (int) $item->item_id,
            'brand_name' => $item->brand ? $item->brand->description : '',
            'cat_name' => $item->category ? $item->category->description : '',
            'supplier__name' => $item->vendor ? $item->vendor->VendorName : '',
            'description' => $item->description,
            'consumable' => $item->consumable,
            'with_serialno' => $item->with_serialno,
            'jo_dept' => $item->jo_dept,
            'isActive' => $item->isActive
        ];
    }
}