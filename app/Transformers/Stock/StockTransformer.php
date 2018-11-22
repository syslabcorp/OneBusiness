<?php
namespace App\Transformers\Stock;

use League\Fractal;
use App\Stock;

class StockTransformer extends Fractal\TransformerAbstract
{
    public function transform(Stock $stock)
    {
        return [
            'txn_no' => $stock ? (int) $stock->txn_no : '',
            'RR_No' =>  $stock ? (int) $stock->RR_No : '',
            'RcvDate' => $stock->RcvDate ? $stock->RcvDate->format('M,d,Y') : '',
            'TotalAmt' => $stock ? number_format($stock->TotalAmt,2) : '',
            'Payment_ID' => $stock ? $stock->Payment_ID : '',
            'VendorName' => $stock->vendor ? $stock->vendor->VendorName : '',
            'DateSaved' => $stock->DateSaved ? $stock->DateSaved->format('M,d,Y h:m:s A') : '',
            'RcvdBy' => $stock ? $stock->RcvdBy : '',
        ];
    }
}