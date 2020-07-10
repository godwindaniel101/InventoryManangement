<?php

namespace App\Http\Controllers\api;

use App\Sales;
use App\Product;
use Carbon\Carbon;
use App\SalesRecord;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MakeSalesResource;
use Mockery\CountValidator\Exception;

class MakeSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
       return  Sales::latest()->paginate(10);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $salesData = $request->input();
        
        foreach($salesData as $unitData){
         $product = Product::where('id' ,$unitData['product_id']);
         $product_name = $product->first()->product_name;
         $product_in_count = $product->first()->product_count;
         $product_price = $product->first()->product_price;
         $unitNet = $unitData['unitTotal'] - ($product_price*$unitData['product_quantity']);
         $product_new_count = $product_in_count - $unitData['product_quantity'];
        //  return($product_new_count);
         $product->update(['product_count' => $product_new_count]);
         Sales::create([
            'product_quantity'=>$unitData['product_quantity'],
            'product_cost'=>$unitData['product_cost'],
            'product_id'=>$unitData['product_id'],
            'product_name' => $product_name,
            'product_discount'=>$unitData['product_discount'],
            'product_unitTotal'=>$unitData['product_unitTotal'],
            'unitGross' => $unitNet,

         ]);
        }
    }
    public function store2(Request $request)
    {
        $salesData = $request->input();
        // return ($salesData);
        foreach($salesData['salesArray'] as $unitData){
            $product = Product::where('id' , $unitData['product_id'])->first();
            $productCount = $product->product_count;
            $productNewCount = ( $productCount - $unitData['product_quantity']);
            $unitGross = $unitData['product_unitTotal'] - ($unitData['product_quantity'] * $product->product_cost);
        
        };
        $gross = 0;
       try{ foreach($salesData['salesArray'] as $unitData){
            $product = Product::where('id' , $unitData['product_id'])->first();
            $product_name = $product->product_name;
            $productCount = $product->product_count;
            $productNewCount = ( $productCount - $unitData['product_quantity']);
            $unitGross = $unitData['product_unitTotal'] - ($unitData['product_quantity'] * $product->product_cost);
            $gross += $unitGross;
            $product->update(['product_count' => $productNewCount]);
            Sales::create([
                'transaction_id' => $salesData['transactionId'],
                'product_quantity'=>$unitData['product_quantity'],
                'product_price'=>$unitData['product_price'],
                'product_id'=>$unitData['product_id'],
                'product_name' => $product_name,
                'product_discount'=>$unitData['product_discount'],
                'product_unitTotal'=>$unitData['product_unitTotal'],
                'unitGross' => $unitGross,
    
             ]);
        }
        Transaction::create([
            'transaction_id' => $salesData['transactionId'],
            'branch_id' => 'Default Branch',
            'total' => $salesData['netTotal'],
            'transaction_type' => 'sales',
            'gross'=> $gross]);
    
        SalesRecord::create([
                'transaction_id' =>  $salesData['transactionId'],
                'branch_id' => 'Default Branch',
                'net_total' =>  $salesData['netTotal'],
                'total_quantity' => $salesData['netQuantity'], 
                'status' => 1,
            ]);
        $data['success'] = true; 
        $data['message'] = 'Sales Made ';
        return json_encode($data);  
        }catch(Exception $e){
            $data['success'] = false; 
            $data['message'] = 'Purchase Could not be Made ';
            return json_encode($data);  
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function salesRecord(){
        return  SalesRecord::latest()->paginate(10);
    }
    
    public function salesRecordEdit($id){
       $salesRecord = Sales::where('transaction_id', $id)->get();
       $data = [];
       foreach($salesRecord as $unitsales){
             $unitData = collect($unitsales);  
             $result = $unitData->merge(['product_count' => $unitsales->product->product_count]);
             $result->all();
             $data[] = $result;
       }
       return $data;
    }
    public function reverseSales($id){
        $sales = Sales::where('transaction_id', $id)->get();
        
       try { 
           foreach($sales as $unitsales)
             {
                $solditem = $unitsales->product_quantity;
                $itemcount = $unitsales->product->product_count;
                $b = $itemcount + $solditem;
               $unitsales->product->update(['product_count' => $b]);
            }
            SalesRecord::where('transaction_id', $id)->update(['status' => 0]);
            $data['success'] = true; 
            $data['message'] = 'Sales Reversed';
            return json_encode($data);  
        }catch(Exception $e){     
                $data['success'] = false; 
                $data['message'] = 'Sales Could not be reversed';
                return json_encode($data);          
        }
    }
    public function restoreSales($id){
        $sales = Sales::where('transaction_id', $id)->get();
        // restorecheck
        foreach($sales as $unitsales)
             {
                $solditem = $unitsales->product_quantity;
                $itemcount = $unitsales->product->product_count;
                if($itemcount < $solditem){
                    $itemname = $unitsales->product->product_name;
                    $data['success'] = false; 
                    $data['message'] = $itemname .' '.'no Longer Available';
                    return json_encode($data);  
                }
             }
       try { 
           foreach($sales as $unitsales)
             {
                $solditem = $unitsales->product_quantity;
                $itemcount = $unitsales->product->product_count;
                if($itemcount < $solditem){
                    $itemname = $unitsales->product->product_name;
                }
                $b = $itemcount - $solditem;
               $unitsales->product->update(['product_count' => $b]);
            }
            SalesRecord::where('transaction_id', $id)->update(['status' => 1]);
            $data['success'] = true; 
            $data['message'] = 'Sales Restored';
            return json_encode($data);  
        }catch(Exception $e){     
                $data['success'] = false; 
                $data['message'] = 'Sales Could not be restored';
                return json_encode($data);          
        }
    }
    public function getTotalSalesRecords($id){
     
        $data =(Sales::where('transaction_id' , $id)->get());
        $data2 =(SalesRecord::where('transaction_id' , $id)->first());
       
        return (['sales'=> $data , 'salesDetail' => $data2]);
    }
    public function deleteSales($id){
       Sales::where('transaction_id' , $id)->delete();
       SalesRecord::where('transaction_id' , $id)->delete();
       Transaction::where('transaction_id' , $id)->delete();
    }
    public function deleteReverseSales($id){
        $sales = Sales::where('transaction_id', $id)->get();
         $reservedStatus = SalesRecord::where('transaction_id' , $id)->first()->status;
        //  return $reservedStatus;
        if($reservedStatus !== '1')        
            { 
                try { 
                    foreach($sales as $unitsales)
                    {
                        $solditem = $unitsales->product_quantity;
                        $itemcount = $unitsales->product->product_count;
                        $b = $itemcount + $solditem;
                        $unitsales->product->update(['product_count' => $b]);
                    }
                    Sales::where('transaction_id' , $id)->delete();
                    SalesRecord::where('transaction_id' , $id)->delete();
                    Transaction::where('transaction_id' , $id)->delete();
                    $data['success'] = true; 
                    $data['message'] = 'Sales Reverted and Deleted';
                    return json_encode($data);  
                }catch(Exception $e){     
                        $data['success'] = false; 
                        $data['message'] = 'Sales Could not be Deleted';
                        return json_encode($data);          
                }
            }else{
                Sales::where('transaction_id' , $id)->delete();
                SalesRecord::where('transaction_id' , $id)->delete();
                Transaction::where('transaction_id' , $id)->delete();
                $data['success'] = true; 
                $data['message'] = 'Sales Deleted';
                return json_encode($data); 
            }
     }
    public function updateSales(Request $request){
     
        $salesData = $request->input();
        $id = $salesData['transactionId'];
        $sales = Sales::where('transaction_id', $id)->get();
        
        try { 
            foreach($sales as $unitsales)
              {
                 $solditem = $unitsales->product_quantity;
                 $itemcount = $unitsales->product->product_count;
                 $b = $itemcount + $solditem;
                $unitsales->product->update(['product_count' => $b]);
                Sales::where('id', $unitsales->id)->delete();
                
             }
            //  return $salesData['salesArray'] ;
            $deleteSalesRecord = SalesRecord::where('transaction_id', $id)->delete();
            $deleteTransactionRecord = Transaction::where('transaction_id', $id)->delete();
            
           if($deleteSalesRecord ){  $gross = 0;
             foreach($salesData['salesArray'] as $unitData){
                 $product = Product::where('id' , $unitData['product_id'])->first();
                 $product_name = $product->product_name;
                 $productCount = $product->product_count;
                 $productNewCount = ( $productCount - $unitData['product_quantity']);
                 $unitGross = $unitData['product_unitTotal'] - ($unitData['product_quantity'] * $product->product_cost);
                 $gross += $unitGross;
                 $product->update(['product_count' => $productNewCount]);
                 Sales::create([
                     'transaction_id' => $salesData['transactionId'],
                     'product_quantity'=>$unitData['product_quantity'],
                     'product_price'=>$unitData['product_price'],
                     'product_id'=>$unitData['product_id'],
                     'product_name' => $product_name,
                     'product_discount'=>$unitData['product_discount'],
                     'product_unitTotal'=>$unitData['product_unitTotal'],
                     'unitGross' => $unitGross,
         
                  ]);
             }
             Transaction::create([
                 'transaction_id' => $salesData['transactionId'],
                 'branch' => 'Default Branch',
                 'total' => $salesData['netTotal'],
                 'transaction_type' => 'sales',
                 'gross'=> $gross]);
         
             SalesRecord::create([
                     'transaction_id' =>  $salesData['transactionId'],
                     'branch' => 'Default Branch',
                     'net_total' =>  $salesData['netTotal'],
                     'total_quantity' => $salesData['netQuantity'], 
                     'status' => 1,
                 ]);}
                 $data['success'] = true; 
                 $data['message'] = 'Sales Could not be Edited';
                 return json_encode($data);   
         }catch(Exception $e){     
                 $data['success'] = false; 
                 $data['message'] = 'Sales Could not be reversed';
                 return json_encode($data);          
         }
    }
    

    public function destroy($id)
    {
        Sales::where('id' , $id)->delete();
        return ['success'=>'true'];
    }
    public function loadSales(){
       return  count(Sales::all());       
    }

    public function todaySales(){
       return  Sales::whereDate('created_at', Carbon::today())->get();
    }
}
