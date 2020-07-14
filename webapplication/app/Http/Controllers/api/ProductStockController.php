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
            
            $validate_input['user_id'] = $this->getAuthenticatedUser()->id;
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
          $id = $this->getAuthenticatedUser()->id;
            $data = ProductStock::where('user_id',  $id)->with('branch')->with('product')->paginate(2);
        return $data;
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
