<?php
namespace App\Traits;
use JWTAuth;
use App\Sales;
use App\SalesRecord;
use App\Transaction;
use App\ProductStock;
use PHPUnit\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\Providers\Auth;

trait GeneralScopes 
{
        public function reverseallsales($id){

        $sales = Sales::where('transaction_id', $id)->get();

        try {
            foreach ($sales as $unitsales) {
                $solditem = $unitsales->product_quantity;
                $itemcount = $unitsales->productstock->product_count;
                $b = $itemcount + $solditem;
                $unitsales->productstock->update(['product_count' => $b]);
            }
            SalesRecord::where('transaction_id', $id)->update(['status' => 0]);
            $data['success'] = true;
            $data['message'] = 'Sales Reversed';
            return json_encode($data);
        } catch (Exception $e) {
            $data['success'] = false;
            $data['message'] = 'Sales Could not be reversed';
            return json_encode($data);
        }

}


public function checksales($allSales){
   try{ 
           $checkStatus = true;

           foreach($allSales as $unitData){
                $solditem = $unitData['product_quantity'];
                $productstockcount = ProductStock::where('id' , $unitData['product_stock_id'])->first()->product_count;
                if($productstockcount < $solditem){
                        $checkStatus =  false; 
                }
           }
           return $checkStatus;
   }catch(Exception $e){
        
   }
}




public function reversesales($unitData){
     try{   
        $solditem = $unitData->product_quantity;
        $productstock = ProductStock::where('id' , $unitData->product_stock_id);
        $productstockFirst = $productstock->first();
        $itemcount = $productstockFirst->product_count;
        $b = $itemcount + $solditem;
        
        $productstock->update(['product_count' => $b]);
        $data['success'] = true; 
        $data['message'] = 'Sales Reversed ';
        return json_encode($data);

}
        catch(Exception $e){
                $data['success'] = false; 
                $data['message'] = 'Sales Could not be reserved ';
                return json_encode($data);  
        }
}





 //       
public function makesales($salesData){
try{
        
        $salesData['user_id'] =  $this->getAuthenticatedUser()->id;
        $gross = 0;
        foreach($salesData['salesArray'] as $unitData){
        $product = ProductStock::where('id' , $unitData['product_stock_id'])->first();
        $productCount = $product->product_count;
        $productNewCount = ( $productCount - $unitData['product_quantity']);
        $unitGross = $unitData['product_unitTotal'] - ($unitData['product_quantity'] * $product->product_cost);
        $gross += $unitGross;
        $product->update(['product_count' => $productNewCount]);
        Sales::create([
            'user_id' =>  $salesData['user_id'] ,
            'branch_id' => $salesData['selectedBranch'],
            'transaction_id' => $salesData['transactionId'],
            'product_quantity'=>$unitData['product_quantity'],
            'product_cost'=>$unitData['product_cost'],
            'product_stock_id'=>$unitData['product_stock_id'],
            'product_id'=>$unitData['product_id'],
            'product_discount'=>$unitData['product_discount'],
            'product_unitTotal'=>$unitData['product_unitTotal'],
            'unitGross' => $unitGross,

         ]);
    }
    Transaction::create([
        'user_id' =>  $salesData['user_id'] ,
        'transaction_id' => $salesData['transactionId'],
        'branch_id' => $salesData['selectedBranch'],
        'total' => $salesData['netTotal'],
        'transaction_type' => 'sales',
        'gross'=> $gross]);

    SalesRecord::create([
            'user_id' =>  $salesData['user_id'] ,
            'transaction_id' =>  $salesData['transactionId'],
            'branch_id' => $salesData['selectedBranch'],
            'net_total' =>  $salesData['netTotal'],
            'total_quantity' => $salesData['netQuantity'], 
            'status' => 1,
        ]);
    $data['success'] = true; 
    $data['message'] = 'Sales Made ';
    return json_encode($data);  
    
    }catch(Exception $e){
        $data['success'] = false; 
        $data['message'] = 'Sales Could not be Made ';
        return json_encode($data);  
    }




}


















 public function reduceUnitStock($unitData){
    try{ $product = ProductStock::where('id' , $unitData['product_id'])->first();
        $productCount = $product->product_count;
        $productNewCount = ( $productCount - $unitData['product_quantity']);
        $product->update(['product_count' => $productNewCount]);
        return $product;
       }catch(Exception $e){
        $data['success'] = false; 
        $data['message'] = 'Sales Could not be Made ';
        return json_encode($data);  
    }
    }
    public function salesgross($unitData){
      return  $unitData->product_unitTotal - $unitData->product_quantity * $unitData->product_cost;
    }

    public function getAuthenticatedUser()
    {
            try {

                    if (! $user = JWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

        //     return response()->json(compact('user'));
            return $user;
    }
}