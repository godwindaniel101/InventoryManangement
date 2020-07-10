<?php

namespace App\Http\Controllers\api;

use App\ProductStock;
use PHPUnit\Exception;
use Illuminate\Http\Request;
use App\Traits\GeneralScopes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductStockController extends Controller
{
    use GeneralScopes;


    public function checkProductStock(Request $request)
    {
        $validate_input = $this->validate($request, [
            "branch_id" => "required",
            "product_id" => "required",
        ]);
        if ($validate_input) {
            $checkForProduct = ProductStock::where('branch_id', $request->input('branch_id'))
                ->where('product_id', '=', $request->input('product_id'))->first();
        }
        if (!empty($checkForProduct)) {
            throw ValidationException::withMessages(['product_id' => "This Product Is already in selected location"]);
        }
        $response['status'] = true;
        return $response;
    }
    public function createProductStock(Request $request)
    {


        $validate_input = $this->validate($request, [
            "branch_id" => "required",
            "product_id" => "required",
            "product_warn" => "required",
            "product_max" => "required|gt:product_warn",
            "product_count" => "sometimes",

        ]);
        $this->checkProductStock($request);
        try {
            $this->getAuthenticatedUser();
            $validate_input['user_id'] = Auth::user()->id;
            ProductStock::create($validate_input);

            $response['status'] = true;
            $response['message'] = "Product Stock Created";
            return $response;
        } catch (Exception $e) {
            throw ValidationException::withMessages(['invalid_token' => $e->getMessage()]);
        }
    }
    public function getProductStock()
    {

        try {
            $this->getAuthenticatedUser();
            $id = Auth::user()->id;
            $productStockDetail = [];
            $data = ProductStock::where('user_id',  Auth::user()->id)->get();
            if (!empty($data)) {
                foreach ($data as $unitdata) {
                    $productStock['id'] = $unitdata->id;
                    $productStock['product_max'] = $unitdata->product_max;
                    $productStock['product_warn'] = $unitdata->product_warn;
                    $productStock['product_name'] = $unitdata->product ? $unitdata->product->product_name : 'Old Product';
                    $productStock['branch_name'] = $unitdata->branch ? $unitdata->branch->name : 'Old Branch';
                    $productStock['product_count'] = $unitdata->product_count;
                    array_push($productStockDetail, $productStock);
                }
            }
            $response['status'] = true;
            $response['data'] = $productStockDetail;
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }


    public function getEditProductStock($id)
    {
        try {
            $data =  ProductStock::where('id', $id)->first();
            $formated_data['id'] = $id;
            $formated_data['branch_id'] = $data->product_id;
            $formated_data['product_id'] =  $data->branch_id;
            $formated_data['name'] =  $data->branch ?  $data->branch->name : 'Old Branch';
            $formated_data['product_name'] =  $data->product ? $data->product->product_name : 'Deleted Product';
            $formated_data['product_count'] =  $data->product_count;
            $formated_data['product_max'] =  $data->product_max;
            $formated_data['product_warn'] =  $data->product_warn;

            $response['status'] = true;
            $response['data'] = $formated_data;
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }
    public function updateProductStock(Request $request, $id)
            {    $this->validate($request, [
                "product_warn" => "required",
                "product_max" => "required|gt:product_warn",

            ]);
        try {
            $formated_data = ProductStock::where('id', $id)->update([
                'product_max' => $request->input('product_max'),
                'product_warn' => $request->input('product_warn'),
            ]);
            $response['status'] = true;
            $response['data'] = $formated_data;
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function deleteProductStock($id)
    {

     
        try {
            ProductStock::where('id', $id)->delete();
            $response['status'] = true;
            $response['message'] = "ProductStock Deleted";
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return $response;
    }
}
