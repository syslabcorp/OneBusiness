<?php
namespace App\Transformers\Stxfr;

use League\Fractal;
use App\Models\Stxfr\Hdr;

class DetailTransformer extends Fractal\TransformerAbstract
{
    public function transform(Hdr $detail)
    {
        return [
            'Txfr_ID' => (int) $detail->Txfr_ID,
            'DateRcvd' => $detail->DateRcvd,
            'Rcvd' => (int) $detail->Rcvd,
            'Shift_ID' => (int) $detail->Shift_ID,
            'Txfr_Date' => $detail->Txfr_Date,
            'Txfr_To_Branch' => $detail->branch ? $detail->branch->ShortName : '',
            'Uploaded' => (int) $detail->Uploaded
        ];
    }
}