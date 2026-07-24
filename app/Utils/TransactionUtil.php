<?php
namespace App\Utils;
use App\Utils\Util;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\ProductStock;
use App\Models\Transaction;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;

class TransactionUtil extends Util
{
    


    public function getTotals($location_id=null, $start_date=null, $end_date=null,$user_id=null){

        $query = Transaction::from('transactions as t')
                    ->where('t.is_new', 0)
                    ->where('t.quotation', 0)
                    ->select(
                        DB::raw("
                            SUM(IF(t.type = 'sell', t.final_amount, 0)) as total_sell,
                            SUM(IF(t.type = 'sell',
                                (SELECT SUM(tp.amount) 
                                 FROM transaction_payments tp 
                                 WHERE tp.transaction_id = t.id),
                            0)) as total_sell_paid,
                            
                            SUM(IF(t.type = 'sell_return', t.final_amount, 0)) as total_sell_return,
                            SUM(IF(t.type = 'sell_return',
                                (SELECT SUM(tp.amount) 
                                 FROM transaction_payments tp 
                                 WHERE tp.transaction_id = t.id),
                            0)) as total_sell_return_paid,
                            
                            SUM(IF(t.type = 'purchase', t.final_amount, 0)) as total_purchase,
                            SUM(IF(t.type = 'purchase',
                                (SELECT SUM(tp.amount) 
                                 FROM transaction_payments tp 
                                 WHERE tp.transaction_id = t.id),
                            0)) as total_purchase_paid,
                            
                            SUM(IF(t.type = 'expense', t.final_amount, 0)) as total_expense,
                            SUM(IF(t.type = 'expense',
                                (SELECT SUM(tp.amount) 
                                 FROM transaction_payments tp 
                                 WHERE tp.transaction_id = t.id),
                            0)) as total_expense_paid,
                            
                            SUM(IF(t.type = 'income', t.final_amount, 0)) as total_income,
                            SUM(IF(t.type = 'income',
                                (SELECT SUM(tp.amount) 
                                 FROM transaction_payments tp 
                                 WHERE tp.transaction_id = t.id),
                            0)) as total_income_paid
                            
                            
                        ")
                    );
            
            if($location_id){
                $query->where('t.location_id',$location_id);
            }
            
            if($user_id){
                $query->where('t.user_id',$user_id);
            }
            
            if($start_date && $end_date){
                $query->whereDate('t.transaction_date','>=',$start_date)->whereDate('t.transaction_date','<=',$end_date);
            }
            
        $item = $query->first();
        
        $data=[
                'total_sell'=>priceFormate($item->total_sell),
                'total_sell_return'=>priceFormate($item->total_sell_return),
                'total_sell_return_due'=>priceFormate($item->total_sell_return - $item->total_sell_return_paid),
                'total_sell_due'=>priceFormate($item->total_sell- $item->total_sell_paid),
                'total_purchase'=>priceFormate($item->total_purchase),
                'total_purchase_due'=>priceFormate($item->total_purchase- $item->total_purchase_paid),
                'total_expense'=>priceFormate($item->total_expense),
                'total_expense_due'=>priceFormate($item->total_expense- $item->total_expense_paid),
                'total_income'=>priceFormate($item->total_income),
                'total_income_due'=>priceFormate($item->total_income- $item->total_income_paid),
                'total_income_due'=>priceFormate($item->total_income- $item->total_income_paid),
                'net_profit'=>priceFormate( $item->total_sell + $item->total_income - $item->total_expense - $item->total_purchase - $item->total_sell_return),
            ];
        
        return $data;
        
    }
    
    
    public function getProductDiscount($product) {
        
        $now = date('Y-m-d');
        $discount = Discount::where('status', 1)
            ->whereDate('start', '<=', $now)
            ->whereDate('end', '>=', $now)
            ->where(function ($q) use ($product) {
    
                // Brand OR Category discount
                $q->where('brand_id', $product->brand_id)
                  ->orWhere('category_id', $product->category_id);
    
                // Specific product discount
                $q->orWhereHas('discount_prodcuts', function ($sub_q) use ($product) {
                    $sub_q->where('product_id', $product->id);
                });
    
            })
            ->orderBy('priority')
            ->first();
    
        $discount_price = 0;
        
        if ($discount) {
            if ($discount->discount_type == 'percentage') {
                $discount_price = ($product->sell_price * $discount->amount) / 100;
            } else { // fixed
                $discount_price = $discount->amount;
            }
        }
        
    
        return [
            'discount_price' => $discount_price,
            'discount'       => $discount
        ];
    }



}

