<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionLine;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TransactionPayment;
use App\Models\Product;
use App\Models\Location;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\ContactNextPayment;
use Carbon\Carbon;
use App\Utils\TransactionUtil;


class ReportController extends Controller
{
    public $transactionUtil;
    
    function __construct(TransactionUtil $transactionUtil)
    {
        
        $this->transactionUtil=$transactionUtil;
    }
    
    
    public function productSTock(Request $request){

        if ($request->ajax()) {

            $location_id=$request->location_id;
            $category_id=$request->category_id;
            $brand_id=$request->brand_id;
            $search=$request->search;

            $query=DB::table('variations as v')
                    ->join('products as p','p.id','v.product_id')
                    ->leftjoin('categories as c','p.category_id','c.id')
                    ->leftjoin('units','p.unit_id','units.id')
                    ->join('product_stocks as ps','ps.variation_id','v.id')
                    ->select('p.name','p.sku','v.sell_price','p.image','units.name as unit_name',
                        'v.purchase_price','v.sub_sku','c.name as category',
                        DB::raw("SUM(ps.qty_available) as stock"),
                        DB::raw("SUM(ps.qty_available *v.purchase_price) as stock_price")

                )->groupBy('v.id');
            if ($search) {
                $query->where(function($row) use($search){
                    $row->where('p.name', 'LIKE', '%'. $search. '%');
                    $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');

                });
            }

            if ($location_id) {
                $query->where('ps.location_id', $location_id);
            }

            if ($category_id) {
                $query->where('p.category_id', $category_id);
            }

            if ($brand_id) {
                $query->where('p.brand_id', $brand_id);
            }

            $items=$query->paginate(40);
            
            return view('reports.partials.product_stock_data', compact('items'))->render();
        }

        $data['cats'] = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $data['locations'] = Location::whereIsNew(0)->get();
        $data['brands'] = Brand::whereIsNew(0)->get();
        
        return view('reports.product_stock', $data);

    }
    
    public function getSales(Request $request){
        if ($request->ajax()) {
            $date=$request->date;
            $location_id=$request->location_id;
            $category_id=$request->category_id;
            $brand_id=$request->brand_id;
            $search=$request->search;
            
            $query = TransactionLine::from('transaction_lines as tl')
                    ->join('transactions as t','t.id','tl.transaction_id')
                    ->join('products as p','p.id','tl.product_id')
                    ->leftjoin('brands','p.brand_id','brands.id')
                    ->leftjoin('categories','p.category_id','categories.id')
                    ->Leftjoin('product_stocks as ps','ps.product_id','p.id')
                    ->where(['t.type'=>'sell','t.quotation'=>0])
                    ->select('p.name','p.sku','p.image','categories.name as category_name','brands.name as brand_name','p.stock_manage','t.invoice_no','tl.price',
                        'tl.product_id',
                        'tl.created_at',
                        DB::raw('SUM(quantity) as sold_qty'),
                        DB::raw('SUM(quantity * price) as sold_amount'),
                        DB::raw("SUM(ps.qty_available) as stock")
                    )
                ->groupBy('tl.id');
    
            if ($search) {
                $query->where(function($row) use($search){
                    $row->where('p.name', 'LIKE', '%'. $search. '%');
                    $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');

                });
            }
                
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : $start;
                $end   = isset($dates[1]) ? trim($dates[1]) : $end;
                $query->whereDate('t.transaction_date', '>=', $start)->whereDate('t.transaction_date', '<=', $end);
                
            }
            
            

            if ($location_id) {
                $query->where('ps.location_id', $location_id);
            }

            if ($category_id) {
                $query->where('p.category_id', $category_id);
            }

            if ($brand_id) {
                $query->where('p.brand_id', $brand_id);
            }
    
            $items = $query->paginate(10);
    
            return view('reports.partials.sale_report_data', compact('items'))->render();
        }
    
        
        $data['cats'] = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $data['locations'] = Location::whereIsNew(0)->get();
        $data['brands'] = Brand::whereIsNew(0)->get();
        
        return view('reports.sales', $data);
    }

    
    public function annualReport(Request $request){
        
        if ($request->ajax()) {
            
            $search=$request->q;
            $type=$request->type;
            $location_id=$request->location_id;
            $payment_status=$request->payment_status;
            $date=$request->date;
            
            $query = Transaction::from('transactions as t')
                    ->latest()->where(['is_new'=>0,'quotation'=>0])
                    ->select('t.category_id', 
                                DB::raw("SUM(IF(t.type = 'sell', t.final_amount, 0)) as total_sell_amount"),
                                DB::raw("SUM(IF(t.type = 'purchase', t.final_amount, 0)) as total_purchase_amount"),
                                DB::raw("SUM(IF(t.type = 'expense', t.final_amount, 0)) as total_expense_amount"),
                                DB::raw("SUM(IF(t.type = 'income', t.final_amount, 0)) as total_income_amount"),
                                DB::raw("SUM(IF(t.type = 'sell',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_sell_paid"),
                                DB::raw('MONTH(t.transaction_date) as month'),
                                DB::raw('YEAR(t.transaction_date) as year'));
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('t.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('t.note', 'LIKE', '%'. $search. '%');
                    });
                }
                
            if($type){
                $query->where('type', $type);
            }
            
            if($location_id){
                $query->where('location_id', $location_id);
            }
            
            
            
            if($payment_status){
                $query->where('payment_status', $payment_status);
            }
            
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : null;
                $end   = isset($dates[1]) ? trim($dates[1]) : null;
                $query->whereDate('transaction_date', '>=', $start)->whereDate('transaction_date', '<=', $end);
                
            }
            
            
            $items=$query
            ->groupBy(
                DB::raw('YEAR(t.transaction_date)'),
                DB::raw('MONTH(t.transaction_date)')
            )
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'ASC')
            ->paginate(30);

            return view('reports.partials.annual_data',compact('items', 'type'))->render();
            
        }
        
        $data['locations'] = Location::whereIsNew(0)->get();
        
        return view('reports.annual', $data);
        
    }
    
    public function profitLoss(Request $request){
        
        if($request->ajax()){
            
            $date=$request->date;
            $start=date('Y-m-d');
            $end=date('Y-m-d');
            if($date){
                    
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : $start;
                $end   = isset($dates[1]) ? trim($dates[1]) : $end;
                
            }
                
            
            $data=$this->transactionUtil->getTotals(null,$start,$end);
           
            return view('reports.partials.profit_data', $data)->render();
        }
        
        return view('reports.profit_loss');
    }
    
    public function purchaseReport(Request $request){
        
        if ($request->ajax()) {
            $date=$request->date;
            $location_id=$request->location_id;
            $category_id=$request->category_id;
            $brand_id=$request->brand_id;
            $search=$request->search;
            
            $query = TransactionLine::from('transaction_lines as tl')
                    ->join('transactions as t','t.id','tl.transaction_id')
                    ->join('products as p','p.id','tl.product_id')
                    ->leftjoin('brands','p.brand_id','brands.id')
                    ->leftjoin('categories','p.category_id','categories.id')
                    ->Leftjoin('product_stocks as ps','ps.product_id','p.id')
                    ->where(['t.type'=>'purchase'])
                    ->select('p.name','p.sku','p.image','categories.name as category_name','brands.name as brand_name','p.stock_manage','t.invoice_no','tl.price',
                        'tl.product_id',
                        'tl.created_at',
                        DB::raw('SUM(quantity) as purchase_qty'),
                        DB::raw('SUM(quantity * price) as purchase_amount'),
                        DB::raw("SUM(ps.qty_available) as stock")
                    )
                ->groupBy('tl.id');
    
            if ($search) {
                $query->where(function($row) use($search){
                    $row->where('p.name', 'LIKE', '%'. $search. '%');
                    $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');

                });
            }
                
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : $start;
                $end   = isset($dates[1]) ? trim($dates[1]) : $end;
                $query->whereDate('t.transaction_date', '>=', $start)->whereDate('t.transaction_date', '<=', $end);
                
            }
            
            

            if ($location_id) {
                $query->where('ps.location_id', $location_id);
            }

            if ($category_id) {
                $query->where('p.category_id', $category_id);
            }

            if ($brand_id) {
                $query->where('p.brand_id', $brand_id);
            }
    
            $items = $query->paginate(10);
    
            return view('reports.partials.purchase_report_data', compact('items'))->render();
        }
    
        
        $data['cats'] = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $data['locations'] = Location::whereIsNew(0)->get();
        $data['brands'] = Brand::whereIsNew(0)->get();
        
        return view('reports.purchase', $data);
    }
    
    public function productReport(Request $request){
        
        if ($request->ajax()) {
            $date=$request->date;
            $location_id=$request->location_id;
            $category_id=$request->category_id;
            $brand_id=$request->brand_id;
            $search=$request->search;
            
            $query = TransactionLine::from('transaction_lines as tl')
                    ->join('transactions as t','t.id','tl.transaction_id')
                    ->join('products as p','p.id','tl.product_id')
                    ->leftjoin('brands','p.brand_id','brands.id')
                    ->leftjoin('categories','p.category_id','categories.id')
                    ->Leftjoin('product_stocks as ps','ps.product_id','p.id')
                    ->where(['t.type'=>'sell','t.quotation'=>0])
                    ->select('p.name','p.sku','p.image','categories.name as category_name','brands.name as brand_name','p.stock_manage',
                        'tl.product_id',
                        'tl.created_at',
                        DB::raw('SUM(quantity) as sold_qty'),
                        DB::raw('SUM(quantity * price) as sold_amount'),
                        DB::raw("SUM(ps.qty_available) as stock")
                    )
                ->groupBy('tl.product_id');
    
            if ($search) {
                $query->where(function($row) use($search){
                    $row->where('p.name', 'LIKE', '%'. $search. '%');
                    $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');

                });
            }
                
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : $start;
                $end   = isset($dates[1]) ? trim($dates[1]) : $end;
                $query->whereDate('t.transaction_date', '>=', $start)->whereDate('t.transaction_date', '<=', $end);
                
            }
            
            

            if ($location_id) {
                $query->where('ps.location_id', $location_id);
            }

            if ($category_id) {
                $query->where('p.category_id', $category_id);
            }

            if ($brand_id) {
                $query->where('p.brand_id', $brand_id);
            }
    
            $items = $query->paginate(20);
    
            return view('reports.partials.sale_product', compact('items'))->render();
        }
    
        
        $data['cats'] = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $data['locations'] = Location::whereIsNew(0)->get();
        $data['brands'] = Brand::whereIsNew(0)->get();
        
        return view('reports.product', $data);
    }
    
    public function getIncomes(Request $request){
        $cats = TransactionCategory::where(['is_new'=>0,'type'=>'income'])->get();
        
        $type='income';
        return view('reports.expense', compact('cats', 'type'));
    }
    
    public function getExpense(Request $request){
        
        
        if ($request->ajax()) {
            
            $search=$request->q;
            $type=$request->type;
            $category_id=$request->category_id;
            $payment_status=$request->payment_status;
            $date=$request->date;
            
            $query = Transaction::from('transactions as t')
                    ->latest()->where(['is_new'=>0])
                    ->select('t.category_id', DB::raw("SUM(IF(t.type != '', t.final_amount, 0)) as total_amount"),
                                DB::raw("SUM(IF(t.type != '',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_paid"));;
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('t.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('t.note', 'LIKE', '%'. $search. '%');
                    });
                }
                
            if($type){
                $query->where('type', $type);
            }
            
            if($category_id){
                $query->where('category_id', $category_id);
            }
            
            
            
            if($payment_status){
                $query->where('payment_status', $payment_status);
            }
            
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : null;
                $end   = isset($dates[1]) ? trim($dates[1]) : null;
                $query->whereDate('transaction_date', '>=', $start)->whereDate('transaction_date', '<=', $end);
                
            }
            
            
            $items=$query->groupBy('t.category_id')->paginate(30);

            return view('reports.partials.expense_data',compact('items', 'type'))->render();
            
        }
        
        $cats = TransactionCategory::where(['is_new'=>0,'type'=>'expense'])->get();
        
        $type='expense';
        return view('reports.expense', compact('cats', 'type'));
        
    }
    
    public function getBestSeller(Request $request){
        
        if ($request->ajax()) {
            $date=$request->date;
            $location_id=$request->location_id;
            $category_id=$request->category_id;
            $brand_id=$request->brand_id;
            $search=$request->search;
            
            $query = TransactionLine::from('transaction_lines as tl')
                    ->join('transactions as t','t.id','tl.transaction_id')
                    ->join('products as p','p.id','tl.product_id')
                    ->leftjoin('brands','p.brand_id','brands.id')
                    ->leftjoin('categories','p.category_id','categories.id')
                    ->Leftjoin('product_stocks as ps','ps.product_id','p.id')
                    ->where(['t.type'=>'sell','t.quotation'=>0])
                    ->select('p.name','p.sku','p.image','categories.name as category_name','brands.name as brand_name','p.stock_manage',
                        'tl.product_id',
                        'tl.created_at',
                        DB::raw('SUM(quantity) as sold_qty'),
                        DB::raw('SUM(quantity * price) as sold_amount'),
                        DB::raw("SUM(ps.qty_available) as stock")
                    )
                ->orderBy('sold_qty','desc')
                ->groupBy('tl.product_id');
    
            if ($search) {
                $query->where(function($row) use($search){
                    $row->where('p.name', 'LIKE', '%'. $search. '%');
                    $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');

                });
            }
                
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : $start;
                $end   = isset($dates[1]) ? trim($dates[1]) : $end;
                $query->whereDate('t.transaction_date', '>=', $start)->whereDate('t.transaction_date', '<=', $end);
                
            }
            
            

            if ($location_id) {
                $query->where('ps.location_id', $location_id);
            }

            if ($category_id) {
                $query->where('p.category_id', $category_id);
            }

            if ($brand_id) {
                $query->where('p.brand_id', $brand_id);
            }
    
            $items = $query->paginate(30);
    
            return view('reports.partials.sale_product', compact('items'))->render();
        }
    
        
        $data['cats'] = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $data['locations'] = Location::whereIsNew(0)->get();
        $data['brands'] = Brand::whereIsNew(0)->get();
        
        return view('reports.best_seller', $data);
    }
    
    public function getSupplier(Request $request){
        
        $type='supplier';
        return view('reports.customer',compact('type'));
    }
    
    public function getCustomer(Request $request){
        
        $type='customer';
        return view('reports.customer',compact('type'));
    }
    
    public function getSupplierDue(Request $request){
        
        $type='supplier';
        return view('reports.customer_due', compact('type'));
        
        
    }
    public function getCustomerDue(Request $request){
        
        if($request->ajax()){
            $type=$request->type;
            $id=$request->contact_id;
            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            $is_due  = $request->is_due;
            
            $query = Contact::Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                                ->select(
                                DB::raw("SUM(IF(t.type != '', t.final_amount, 0)) as total_sell"),
                                DB::raw("SUM(IF(t.type != '',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_sell_paid"),
                                
                                DB::raw("
                                    SUM(
                                        IF(t.type != '',
                                            t.final_amount - 
                                            (SELECT IFNULL(SUM(tp.amount),0)
                                             FROM transaction_payments tp
                                             WHERE tp.transaction_id = t.id),
                                        0)
                                    ) AS total_due
                                "),
                                
                                'contacts.*'
                            );
                            
                        if($is_due){
                        $query->whereRaw("
                                t.final_amount > (
                                    SELECT IFNULL(SUM(tp.amount),0)
                                    FROM transaction_payments tp
                                    WHERE tp.transaction_id = t.id
                                )
                            ");
                            
                        }
                            
    
                    
                        $query->where('contacts.type',$type)
                                ->groupBy('contacts.id')
                                ->where('contacts.is_new',0);
                    
                        
                        if ($search) {
                            $query->where(function ($row) use ($search) {
                                $row->where('contacts.name', 'LIKE', '%' . $search . '%')
                                    ->orWhere('contacts.address', 'LIKE', '%' . $search . '%')
                                    ->orWhere('contacts.email', 'LIKE', '%' . $search . '%')
                                    ->orWhere('contacts.mobile', 'LIKE', '%' . $search . '%');
                            });
                        }
                        
                        if($status!=''){
                            $query->where('contacts.status', $status);
                        }
                        
                        if ($sort == 'asc') {
                            $query->orderBy('contacts.id', 'asc');
                        } elseif ($sort == 'desc') {
                            $query->orderBy('contacts.id', 'desc');
                        } else {
                            $query->latest();
                        }
                    
            $items=$query->paginate(30);
            return view('reports.partials.contact_data',compact('items','type'))->render();
        }
        
        $type='customer';
        return view('reports.customer_due', compact('type'));
    }
    
    public function productQuantityAlert(Request $request){
        $data['cats'] = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $data['locations'] = Location::whereIsNew(0)->get();
        $data['brands'] = Brand::whereIsNew(0)->get();
        
        if($request->ajax()){
            
            
            $location_id=$request->location_id;
            $category_id=$request->category_id;
            $brand_id=$request->brand_id;
            $search=$request->search;

            $query=Product::from('products as p')
                    ->leftjoin('categories as c','p.category_id','c.id')
                    ->leftjoin('units','p.unit_id','units.id')
                    ->join('product_stocks as ps','ps.product_id','p.id')
                    ->select('p.name','p.sku','p.sell_price','p.image','units.name as unit_name','p.stock_alert','p.id',
                        'p.purchase_price','c.name as category',
                        DB::raw("SUM(ps.qty_available) as stock")

                )
                ->havingRaw('SUM(ps.qty_available) <= p.stock_alert')
                ->groupBy('p.id');
            if ($search) {
                $query->where(function($row) use($search){
                    $row->where('p.name', 'LIKE', '%'. $search. '%');
                    $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');

                });
            }

            if ($location_id) {
                $query->where('ps.location_id', $location_id);
            }

            if ($category_id) {
                $query->where('p.category_id', $category_id);
            }

            if ($brand_id) {
                $query->where('p.brand_id', $brand_id);
            }

            $items=$query->paginate(40);
            
            return view('reports.partials.alert_quantity_data', compact('items'))->render();
            
        }
        
        return view('reports.product_quantity_alert', $data);
    }
    
    
    public function customerDuePayment(Request $request){
        
        
        if ($request->ajax()) {
            
            $search=$request->search;
            $type=$request->type;
            $category_id=$request->category_id;
            $payment_status=$request->payment_status;
            $date=$request->date;
            
            $query = TransactionPayment::from('transaction_payments as tp')
                    ->join('transactions as t','tp.transaction_id','t.id')
                    ->join('contacts as c','t.contact_id','c.id')
                    ->where('tp.is_due',1)
                    ->orderByDesc('tp.paid_on')
                    ->select('c.name','c.mobile','t.invoice_no','tp.amount','tp.method','tp.paid_on');
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('t.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('c.name', 'LIKE', '%'. $search. '%');
                        $row->orwhere('c.mobile', 'LIKE', '%'. $search. '%');
                    });
                }
                
            if($type){
                $query->where('c.type', $type);
            }
            
            if($date){
                $dates  = explode('~', $date);
                $start = isset($dates[0]) ? trim($dates[0]) : null;
                $end   = isset($dates[1]) ? trim($dates[1]) : null;
                $query->whereDate('tp.paid_on', '>=', $start)->whereDate('tp.paid_on', '<=', $end);
                
            }
            
            $items=$query->paginate(30);
            return view('reports.partials.payment',compact('items', 'type'))->render();
        }
        
        $type='expense';
        return view('reports.customer_due_payment', compact('type'));
        
    }
    
    public function customerPaymentDate(Request $request){
        
        if ($request->ajax()) {
            
            $search=$request->search;
            $type=$request->type;
            $date=$request->date;
            
            $query = ContactNextPayment::from('contact_next_payments as cnp')
                    ->leftjoin('contacts as c','cnp.contact_id','c.id')
                 
                    ->where('cnp.next_payment_date','>=', date('Y-m-d'))
                    ->orderBy('cnp.next_payment_date')
                    ->select('c.name','c.mobile','cnp.*');
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('c.name', 'LIKE', '%'. $search. '%');
                        $row->orwhere('c.mobile', 'LIKE', '%'. $search. '%');
                    });
                }
                
            if($type){
                $query->where('c.type', $type);
            }
            
            if($date){
                $dates  = explode('~', $date);
                $start = isset($dates[0]) ? trim($dates[0]) : null;
                $end   = isset($dates[1]) ? trim($dates[1]) : null;
                $query->whereDate('cnp.next_payment_date', '>=', $start)->whereDate('cnp.next_payment_date', '<=', $end);
                
            }
            
            $items=$query->paginate(30);
            return view('reports.partials.payment_date_data',compact('items', 'type'))->render();
        }
        
        $type='customer';
        return view('reports.customer_payment_date', compact('type'));
        
        
    }
    
    
    

}
