<?php
namespace App\Transformers\Spo;

use League\Fractal;
use App\Models\Spo\Hdr;

class HdrTransformer extends Fractal\TransformerAbstract
{
    public function transform(Hdr $item)
    {
        return [
            'po_no' => (int) $item->po_no,
            'po_date' => $item->po_date,
            'tot_pcs' => number_format($item->tot_pcs, 2),
            'served' => $item->served,
            'total_amt' => number_format((float) $item->total_amt, 2),
            'template' => $item->template ? $item->template->po_tmpl8_desc : '',

        ];
    }
}