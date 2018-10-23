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
        
        $details = $item->details;

        $type = $item->type == 'Com Proper' ? 'Company Property' : $item->type;
        
        return [
            'asset_id' => $item->asset_id,
            'description' => $item->description,
            'type' => $type,
            'isActive' => $item->isActive,
            'qty' => $item->details->sum('qty')
        ];
    }
}
