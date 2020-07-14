<?php

namespace App\Http\Controllers\api;

use App\Sales;
use App\Product;
use Carbon\Carbon;
use App\SalesRecord;
use App\Transaction;
use App\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\CountValidator\Exception;
use App\Http\Resources\MakeSalesResource;
use App\Traits\GeneralScopes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MakeSalesController extends Controller
{
    use GeneralScopes;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function store2(Request $request)
    {

        $salesData = $request->input();
        if ($this->checksales($salesData['salesArray'])) {
            // return 'here';
            $response = $this->makesales($salesData);
        } else {
            $response['success'] = false;
            $response['message'] = 'Sales Failed';
        }
        return $response;
    } /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function salesRecord()
    {
        $user_id =  $this->getAuthenticatedUser()->id;
        $data = SalesRecord::where('user_id', $user_id)->orderBy('created_at', 'desc')->with('branch')->paginate(6);
        return $data;
    }

    public function salesRecordEdit($id)
    {
        $salesRecord = Sales::where('transaction_id', $id)->get();
        $unitRecords = SalesRecord::where('transaction_id', $id)->first();
        $data = [];
        foreach ($salesRecord as $unitsales) {
            $attemptData = ProductStock::where("branch_id", $unitsales->branch_id)->where("product_id", $unitsales->product_id)->first();
            if ($attemptData) {
                $formatted_data['product_count'] = $attemptData->product_count;
            } else {
                $formatted_data['product_count'] = 0;
            }
            $formatted_data['id'] = $unitsales->id;
            $formatted_data['product_cost'] = $unitsales->product_cost;
            $formatted_data['product_discount'] = $unitsales->product_discount;
            $formatted_data['product_id'] = $unitsales->product_id;
            $formatted_data['product_stock_id'] = $unitsales->product_stock_id;
            $formatted_data['product_unitTotal'] = $unitsales->product_unitTotal;
            $formatted_data['product_quantity'] = $unitsales->product_quantity;
            $formatted_data['transaction_id'] = $unitsales->product_unitTotal;
            $formatted_data['unitGross'] = $unitsales->unitGross;
            array_push($data, $formatted_data);
        }
        //    return $salesRecord;
        return ['salesItems' => $data, 'salesDetails' => $unitRecords];
    }
    public function reverseSales($id)
    {
       return $this->reverseallsales($id);
    
    }
    public function restoreSales($id)
    {
        $sales = Sales::where('transaction_id', $id)->get();
        $checksales = $this->checksales($sales);
        if ($checksales) {
            try {
                foreach ($sales as $unitsales) {
                    $solditem = $unitsales->product_quantity;
                    $itemcount = $unitsales->productstock->product_count;
                    // if ($itemcount < $solditem) {
                    //     $itemname = $unitsales->product->product_name;
                    // }
                    $b = $itemcount - $solditem;
                    $unitsales->productstock->update(['product_count' => $b]);
                }
                SalesRecord::where('transaction_id', $id)->update(['status' => 1]);
                $data['success'] = true;
                $data['message'] = 'Sales Restored';
                return json_encode($data);
            } catch (Exception $e) {
                $data['success'] = false;
                $data['message'] = 'Sales Could not be restored';
                return json_encode($data);
            }
        } else {
            $data['success'] = false;
            $data['message'] = 'Sales Items Sold Out';
            return json_encode($data);
        }
    }
    public function getTotalSalesRecords($id)
    {
        $data = Sales::where('transaction_id', $id)->with('product')->get();
        $data2 = SalesRecord::where('transaction_id', $id)->with('branch')->with('user')->first();
        return (['sales' => $data, 'salesDetail' => $data2]);
    }
    public function deleteSales($id)
    {
        Sales::where('transaction_id', $id)->delete();
        SalesRecord::where('transaction_id', $id)->delete();
        Transaction::where('transaction_id', $id)->delete();
    }
    public function deleteReverseSales($id)
    {
        $sales = Sales::where('transaction_id', $id)->get();
        $reservedStatus = SalesRecord::where('transaction_id', $id)->first()->status;
        //  return $reservedStatus;
        if ($reservedStatus !== '1') {
            try {
                    $this->reverseSales($id);
                    Sales::where('transaction_id', $id)->delete();
                    SalesRecord::where('transaction_id', $id)->delete();
                    Transaction::where('transaction_id', $id)->delete();
                    $data['success'] = true;
                    $data['message'] = 'Sales Reverted and Deleted';
                    return json_encode($data);
    
            } catch (Exception $e) {
                $data['success'] = false;
                $data['message'] = 'Error Deleting Sold';
                return json_encode($data);
            }
        } else {
            Sales::where('transaction_id', $id)->delete();
            SalesRecord::where('transaction_id', $id)->delete();
            Transaction::where('transaction_id', $id)->delete();
            $data['success'] = true;
            $data['message'] = 'Sales Deleted';
            return json_encode($data);
        }
    }
    public function updateSales(Request $request)
    {
        $salesData = $request->input();
        $transactin_id = $salesData['transactionId'];
        try {
            $this->reverseallsales($transactin_id);
            $sales = Sales::where('transaction_id', $transactin_id)->delete();
            $deleteSalesRecord = SalesRecord::where('transaction_id', $transactin_id)->delete();
            $deleteTransactionRecord = Transaction::where('transaction_id', $transactin_id)->delete();
            $response = $this->makesales($salesData);
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = 'Sales Could not be reversed';
        }
        return $response;
    }


    public function destroy($id)
    {
        Sales::where('id', $id)->delete();
        return ['success' => 'true'];
    }
    public function loadSales()
    {
        return  count(Sales::all());
    }

    public function todaySales()
    {
        return  Sales::whereDate('created_at', Carbon::today())->get();
    }
}
