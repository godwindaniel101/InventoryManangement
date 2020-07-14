<?php

namespace App\Http\Controllers\api;

use App\Product;
use App\ProductStock;
use Illuminate\Http\Request;
use App\Traits\GeneralScopes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{       use GeneralScopes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = $this->getAuthenticatedUser()->id;
        return Product::where('user_id', $id)->paginate(1);
    }
    public function getBranchProduct($id)
    {
        $autId = $this->getAuthenticatedUser()->id;
        $data = ProductStock::where('user_id', $autId)->where('branch_id' , $id)->get();
        // return $data;
        // $data = ProductStock::where('user_id', $autId)->where('branch_id' , $id)->where('product_count', '>' , 0)->get();
        $dataArray = [];
        foreach($data as $unitdata){
            $formatted_data['id'] = $unitdata->id;
            $formatted_data['product_id'] = $unitdata->product_id;
            $formatted_data['product_name'] = $unitdata->product ? $unitdata->product->product_name : 'Old Product';
            array_push($dataArray , $formatted_data);
        }
        return $dataArray;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $request;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        
        
        ;
    
       $validator = $this->validate($request , [
            'product_name' => 'required',
            'product_cost' => 'required',
            'product_supplier' => 'required',
            'product_price' => 'required',
        ]);
        $validator['user_id'] = $this->getAuthenticatedUser()->id;
        Product::create($validator);
        return ['success' => 'true'];
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
        return Product::where('id',$id)->first();
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
        $this->validate($request, [
            'product_name' => 'required',
            'product_cost' => 'required',
            'product_supplier' => 'required',
        ]);

        Product::where('id' , $id)->update($request->all());
        return ['success'=>'true'] ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::where('id' , $id)->delete();
        return ['success'=>'true'];
    }
    public function getCost($product_id ,$branch_id){
       $formatted_data = ProductStock::where('branch_id' , $branch_id)->where('product_id' , $product_id)->first();
    //    return $formatted_datas;
       $formatted_data['product_cost'] = $formatted_data->product->product_cost ? $formatted_data->product->product_cost: '';
       return $formatted_data;
    }
    
}
