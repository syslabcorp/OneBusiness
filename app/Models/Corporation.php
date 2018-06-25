<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corporation extends Model
{
    protected $table = 'corporation_masters';
    protected $primaryKey = 'corp_id';
    public $timestamps = false;

    protected $fillable = [
      'corp_name', 'database_name', 'payroll_conn', 'status', 'deleted',
      'corp_type', 'wt_doc_cat', 'wt_doc_subcat'
    ];

    public function branches()
    {
      return $this->hasMany(\App\Branch::class, "corp_id", "corp_id");
    }

    public function category()
    {
        $categoryModel = new \App\HCategory;
        $categoryModel->setConnection($this->database_name);

        return $categoryModel->find($this->wt_doc_cat);
    }

    public function subcategory()
    {
        $categoryModel = new \App\HSubcategory;
        $categoryModel->setConnection($this->database_name);

        return $categoryModel->find($this->wt_doc_subcat);
    }
}
