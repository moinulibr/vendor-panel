<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public $transactionUtil;
    
    function __construct(TransactionUtil $transactionUtil)
    {
        // $this->middleware('permission:products.view|products.create|products.edit|products.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:products.create', ['only' => ['create','store']]);
        // $this->middleware('permission:products.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:products.delete', ['only' => ['destroy']]);
        $this->transactionUtil=$transactionUtil;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    public function dashboardData(Request $request)
    {
        $date=$request->date;
        $start=date('Y-m-d');
        $end=date('Y-m-d');
        if($date){
                
            $dates  = explode('~', $date);
            
            $start = isset($dates[0]) ? trim($dates[0]) : $start;
            $end   = isset($dates[1]) ? trim($dates[1]) : $end;
            
        }
            
        
        $data=$this->transactionUtil->getTotals(null,$start,$end);
        
        return $data;
        
    }
    
}
