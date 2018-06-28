<?php

namespace App\Http\Controllers\Branch\Recommendation;

use Illuminate\Http\Request;
use \Yajra\Datatables;
use App\Http\Controllers\Controller;
use \App\Corporation;
use \App\Http\Models\Branch\EmployeeRequestHelper;
use \App\Models\Branchs\Recommendation\Recommendation;
use \App\Models\Branchs\Recommendation\Py_emp_rate;
use \App\Models\Branchs\Recommendation\Py_emp_hist;
use \App\Models\Branchs\Recommendation\H_docs;
use \App\Models\Branchs\Recommendation\H_Category;

class RecommendationController extends Controller
{
    //
    public function index() {
        
        session()->forget('error');
        
        try{
            $corpId = request()->corpID;
            $helper =new EmployeeRequestHelper();
            $helper->setCorpId($corpId);
            $databaseName = $helper->getDatabaseName(); // throw exception if no corpId
            
            if(!auth()->user()->checkAccessByIdForCorp($corpId, 53, 'V')){
                session()->put('error','You don\'t have permission');
            }
            
            return view('branchs.recommendationRequest.index',compact('corpId'));    
        }
        catch(\Exception $ex){
            
            session()->put('error',$ex->getMessage());
            
            return view('branchs.recommendationRequest.index',compact('corpId'));    
        }
        
   }
    protected function getAnswerCollection($recommCollect){
        
//        {data: 'name', name: 'username'},
//        {data: 'from_wage', name: 'from_wage'},
//        {data: 'to_wage', name: 'to_wage'},
//        {data: 'approved', name: 'approved'},
//        {data: 'deleted', name: 'deleted'},
//        {data: 'effective_date', name: 'effective_date'},
//        {data: 'recommended_by', name: 'recommended_by'},
//        {data: 'action', name: 'action', sortable: false, searchable: false}
        
        $user_A_right = auth()->user()->checkAccessByIdForCorp($corpId, 53, 'A');
        $user_B_right = auth()->user()->checkAccessByIdForCorp($corpId, 53, 'A');
        
        $answer = $recommCollect->get()->map( function($recommendation){
            
            return [ 'name' => $recommendation->User->UserName,
                    'from_wage' => $recommendation->fromWage->code,
                    'to_wage' => $recommendation->toWage->code,
                    'approved'=>$recommendation->isApproved,
                    'deleted'=>$recommendation->isDeleted,
                    'effective_date'=>$recommendation->effective_date,
                    'recommended_by'=>$recommendation->RecommendedBy->UserName,
                    'action' => '<span class="btn btn-success actionButton'.
                        ($user_A_right ? 
                            '" onclick="approveRequest('.
                           $recommendation->txn_no.',\''.
                           $recommendation->fromWage->code.'\',\''.
                           $recommendation->toWage->code.'\',\''.
                           $recommendation->User->UserName.'\')">'.
                           '<span class="glyphicon glyphicon-ok"></span></span>' 
                        :
                        ' disabled"><span class="glyphicon glyphicon-ok"></span></span>')
                        .'&nbsp'.
                        '<span class="btn btn-danger actionButton'. 
                        ($user_A_right ?
                        '" onclick="deleteRequest('.
                        $recommendation->txn_no.',\''.
                        $recommendation->fromWage->code.'\',\''.
                        $recommendation->toWage->code.'\',\''.
                        $recommendation->User->UserName.'\')">'.
                        '<span class="glyphicon glyphicon-remove"></span></span>'
                            :
                        ' disabled"><span class="glyphicon glyphicon-remove"></span></span>')
                ];
        });
        //requestId, fromWage , toWage , userName
        return $answer;
    }
    
    protected function getFilteredRecommendationByApproved($approveFilter,$recom) {
        
        if($approveFilter == 'any'){
            
            $recom = $recom;
            
        }
        elseif($approveFilter == 'for_approval'){
            
            $recom = $recom->where('isApproved',0)->where('isDeleted',0);
            
        }
        elseif ($approveFilter == 'approved') {
        
            $recom = $recom->where('isApproved',1);
            
        }
        
        
        return $recom;
    }
    
    
    public function getRecommendation() {
        
        $recom = $this->getRemmendationQueryByRequest();
        $approveFilter = request()->approved;
        $recom= $this->getFilteredRecommendationByApproved($approveFilter, $recom);
                
        return Datatables\Datatables::of($this->getAnswerCollection($recom)->toArray())->make('true');
    }
    protected function getRemmendationQueryByRequest() {
        
        $corpId = request()->corpId;
        $helper =new EmployeeRequestHelper();
        $helper->setCorpId($corpId);
        $databaseName = $helper->getDatabaseName();
        
        $recom = new Recommendation();
        $recom->setConnection($databaseName);
        
        return $recom;
    }
    public function getConnectionNameByRequest() {
        $corpId = request()->corpId;
        $helper =new EmployeeRequestHelper();
        $helper->setCorpId($corpId);
        $databaseName = $helper->getDatabaseName();
        
        return $databaseName;
    }
    
    private function makeTableChanges($recommendation) {

        $currentConnection = $this->getConnectionNameByRequest();
        
//      INSERT into py_emp_rate :
//      
//      prepare data
        
        $py_emp_hist = new Py_emp_hist();
        $py_emp_hist->setConnection($currentConnection);
        
        //txn_id you obtained from py_emp_hist(the latest txn_id for where empID=[recommended employee's ID])
        $newtxn_ID = $py_emp_hist->where('EmpID',$recommendation->userID)->max('txn_id');
        
        $empRate = new Py_emp_rate();
        $empRate->setConnection($this->getConnectionNameByRequest());
              
        
//      txn_ID = (latest txn_no of the employee recorded in py_emp_hist)
//      wage_tmpl8_id = recommendation_rqst.wage_to
//      effect_date = recommendation_rqst.effective_date
//      date_changed = date the record was updated

        $empRate->txn_ID = $newtxn_ID;
        $empRate->wage_tmpl8_id = $recommendation->to_wage;
        $empRate->effect_date   = $recommendation->effective_date;
        $empRate->date_changed  = \Carbon\Carbon::now();
        $empRate->approval_code = $recommendation->txn_no;
        $empRate->apprv_type    = 0;
        
        $empRate->save();


//      Insert INTO h_docs :        
        $hDocs = new H_docs();
        $hDocs->setConnection($currentConnection);

        $corporation = Corporation::find(request()->corpId);
//        series_no = last count for the doc_id,plus 1
//        doc_no = corporation_masters.wt_doc_cat(based on corp ID)
//        subcat_id = corporation_masters.wt_doc_subcat(based on corp ID)
//        emp_id = recommendation_rqst.userID
//        branch = t_users.Branch or t_users.SQ_Branch(user’s branch)
//        doc_date
//        doc_exp
//        img_file
        $wt_doc_cat = $corporation->wt_doc_cat;
        
        $latest_Series_no = $hDocs->where('doc_no',$wt_doc_cat)->max('series_no');
        $newSeries_no = $latest_Series_no + 1;
        
        $hDocs->series_no   = $newSeries_no ; 
        $hDocs->doc_no      = $corporation->wt_doc_cat; 
        $hDocs->subcat_id   = $corporation->wt_doc_subcat;
        $hDocs->emp_id      = $recommendation->userID;
        $hDocs->branch      = $recommendation->User->Branch;
        $hDocs->doc_exp     = '0000-00-00';
        $hDocs->notes       = 'Recommended by: '.auth()->user()->UserName;
        $hDocs->approval_no = $recommendation->txn_no;
        
        $hDocs->save();
        
//      UPDATE h_category.series 
//      
        $h_category = new H_Category();
        $h_category->setConnection($currentConnection);
        
        $updatedCategoryRow = $h_category->where('doc_no',$wt_doc_cat)->first();
        $updatedCategoryRow->series = $newSeries_no;
        
        $updatedCategoryRow->save();
    }
    
    public function approveRecommendation(){
        $recom = $this->getRemmendationQueryByRequest();
        
        $approveRecommendation = $recom->find(request()->recommendationId);
        
        if($approveRecommendation){
            
            $approveRecommendation->approveRecommendation();
            
            $this->makeTableChanges($approveRecommendation);
            
            return response('approved');
            
        }
        return response('error');
    }
    
    public function deleteRecommendation(){
        
        $recom = $this->getRemmendationQueryByRequest();
        $deletedRecommendation = $recom->find(request()->recommendationId);
        if($deletedRecommendation){
            
            $deletedRecommendation->deleteRecommendation();
            return response('deleted');
            
        }
        return response('error');
    }
}
