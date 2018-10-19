<?php
namespace App\Transformers\Equip;

use League\Fractal;
use App\Models\Equip\Hdr;
use App\Models\Corporation;

class EquipTransformer extends Fractal\TransformerAbstract
{
    private $company;

    public function __construct($corpID)
    {
        $this->company = Corporation::findOrFail($corpID);
    }

    public function transform(Hdr $item)
    {
        $deptModel = new \App\Models\T\Depts;
        $deptModel->setConnection($this->company->database_name);

        $dept = $deptModel->find($item->dept_id);

        $status = '';

        $details = $item->details;

        if ($details->count()) {
            $status = 'For Repair';

            if ($details->where('status', 1)->count() == $details->count()) {
                $status = 'In Use';
            }

            if ($details->where('status', 0)->count() == $details->count()) {
                $status = 'Retired';
            }
        }

        $type = $item->type == 'Com Proper' ? 'Company Property' : $item->type;
        
        return [
            'asset_id' => $item->asset_id,
            'description' => $item->description,
            'type' => $type,
            'status' => $status,
            'isActive' => $item->isActive,
            'qty' => $item->details->sum('qty')
        ];
    }
}
