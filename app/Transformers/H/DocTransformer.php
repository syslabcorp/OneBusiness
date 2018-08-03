<?php
namespace App\Transformers\H;

use League\Fractal;
use App\Models\Py\EmpHistory;
use App\Models\Py\EmpRate;
use App\HDocs;
use App\Branch;
use DB;

class DocTransformer extends Fractal\TransformerAbstract
{
    public function transform(HDocs $item)
    {
      return [
        'txn_id' => $item->txn_no,
        'Series' => $item->series_no,
        'Approval' => (int) $item->approval_no,
        'Branch' => $item->branch()->first() ? $item->branch()->first()->ShortName : '',
        'Category' => $item->category ? $item->category()->first()->description : "",
        'Document' => $item->subcategory ? $item->subcategory()->first()->description : "",
        'Notes' => $item->notes,
        'Expiry' => $item->doc_exp,
        'Image' => $item->img_file,
        'DateArchived' => $item->doc_date ? (new \DateTime($item->doc_date))->format('m/d/Y') : '',
      ];
    }
}
