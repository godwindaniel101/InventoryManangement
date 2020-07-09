<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Purchase;
use App\SalesRecord;
use App\Transaction;
use App\PurchaseRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\CountValidator\Exception;

class MakePurchaseContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPurchase(Request $request){
        $purchaseData = $request->input();
        // return $purchaseData;
       try { 
        $gross = 0;
        foreach($purchaseData['purchaseArray'] as $unitData){
            $product = Product::where('id' , $unitData['product_id'])->first();
            $product_name = $product->product_name;
            $productCount = $product->product_count;
            $productNewCount = ( $productCount + $unitData['product_quantity']);
            $unitGross = $unitData['product_unitTotal'] - ($unitData['product_quantity'] * $product->product_cost);
            $gross += $unitGross;
            $product->update(['product_count' => $productNewCount]);
            Purchase::create([
                'transaction_id' => $purchaseData['transactionId'],
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
            'transaction_id' => $purchaseData['transactionId'],
            'branch' => 'Default Branch',
            'total' => $purchaseData['netTotal'],
            'transaction_type' => 'sales',
            'gross'=> $gross]);
    
            PurchaseRecord::create([
                'transaction_id' =>  $purchaseData['transactionId'],
                'branch' => 'Default Branch',
                'net_total' =>  $purchaseData['netTotal'],
                'total_quantity' => $purchaseData['netQuantity'], 
                'status' => 1,
            ]);
            $data['success'] = true; 
            $data['message'] = 'Purchase Made ';
            return json_encode($data);  
        }catch(Exception $e){
            $data['success'] = false; 
            $data['message'] = 'Purchase Could not be Made';
            return json_encode($data);  
        }
    }
    public function purchaseRecord()
    {
        return  PurchaseRecord::latest()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
