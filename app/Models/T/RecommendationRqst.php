<?php

namespace App\Models\T;

use Illuminate\Database\Eloquent\Model;

class RecommendationRqst extends Model
{
    public $timestamps = false;
    protected $table = "t_recommendation_rqst";
    protected $primaryKey = "txn_no";

    protected $fillable = [
        'userID', 'from_wage', 'to_wage', 'effective_date', 'recommended_by',
        'date_recommended', 'isApproved', 'isDeleted'
    ];

    protected $dates = [
        'effective_date', 'date_recommended'
    ];

    public function recommendedBy()
    {
        return $this->belongsTo(\App\User::class, 'recommended_by', 'UserID');
    }

    public function fromTemplate()
    {
        return $this->belongsTo(\App\Models\WageTmpl8\Mstr::class, 'from_wage', 'wage_tmpl8_id');
    }

    public function toTemplate()
    {
        return $this->belongsTo(\App\Models\WageTmpl8\Mstr::class, 'to_wage', 'wage_tmpl8_id');
    }
}
