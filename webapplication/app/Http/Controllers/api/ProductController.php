<?php

namespace App\Http\Controllers\api;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\GeneralScopes;
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
        return Product::all();
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
        
        
        $this->getAuthenticatedUser();
    
       $validator = $this->validate($request , [
            'product_name' => 'required',
            'product_cost' => 'required',
            'product_supplier' => 'required',
            'product_price' => 'required',
        ]);
        $validator['user_id'] = Auth::user()->id;
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
    public function getCost($id){
        return Product::where('id' , $id)->first();
    }
}
