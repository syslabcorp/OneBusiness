<?php

namespace App\Models\Branchs\Recommendation;

use Illuminate\Database\Eloquent\Model;
use \App\User;
class Recommendation extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection ;
    protected $primaryKey = 'txn_no';
    protected $table = 't_recommendation_rqst';
    
    public $timestamps = false;
    
    //
    protected $fillable = [
        'userID',
        'from_wage',
        'to_wage',
        'effective_date',
        'recommended_by',
        'date_recommended',
        'isApproved',
        'isDeleted',
        
    ];
    
    public function User() {
        
        return $this->belongsTo( User::class,'userID');
    }
    public function RecommendedBy() {
        
        return $this->belongsTo( User::class,'recommended_by');
    }
    
    public function toWage() {
        
         $waged_db = new Wage_tmpl8();
         $waged_db->setConnection($this->connection);
         
         return $this->belongsTo( $waged_db , 'to_wage');
    }
    
    public function fromWage() {
        
         $waged_db = new Wage_tmpl8();
         $waged_db->setConnection($this->connection);
         
         return $this->belongsTo( $waged_db , 'from_wage');
    }
    
    public function h_docs() {
        
         $h_docks_db = new H_docs();
         $h_docks_db->setConnection($this->connection);
         
         return $this->hasMany($h_docks_db, 'emp_id' , 'userID');
         
    }
    
    public function deleteRecommendation(){
        $this->isDeleted = 1;
        $this->save();
    }
    
    public function approveRecommendation(){
        $this->isApproved = 1;
        $this->save();
    }
    
}
