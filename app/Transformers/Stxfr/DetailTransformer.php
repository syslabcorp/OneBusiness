<?php
namespace App\Transformers\Stxfr;

use League\Fractal;
use App\Models\Stxfr\Hdr;

class DetailTransformer extends Fractal\TransformerAbstract
{
    public function transform(Hdr $detail)
    {
        $txfrDate = $detail->Txfr_Date ? (new \DateTime($detail->Txfr_Date))->format('Y-m-d') : '';
        $dateRcvd = $detail->DateRcvd ? (new \DateTime($detail->DateRcvd))->format('Y-m-d') : '';

        return [
            'Txfr_ID' => (int) $detail->Txfr_ID,
            'DateRcvd' => $dateRcvd,
            'Rcvd' => (int) $detail->Rcvd,
            'Shift_ID' => (int) $detail->Shift_ID,
            'ReceivedBy' => $detail->shift ? ($detail->shift->user ? $detail->shift->user->UserName : '' ) : '',
            'Txfr_Date' => $txfrDate,
            'Txfr_To_Branch' => $detail->branch ? $detail->branch->ShortName : '',
            'Uploaded' => (int) $detail->Uploaded
        ];
    }
}