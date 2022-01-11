<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Stock;
use App\Models\Product;
use App\Http\Resources\StockResource;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Stock::latest()->get();
        return response()->json([StockResource::collection($data), 'Stocks fetched.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'product_quantity' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }
    
        $stock = Stock::create([
            'name' => $request->name,
            'product_quantity' => $request->product_quantity
         ]);
        
         $product = Product::find(1); //in find() Pass Product id which is belong to the stock 
         $product->stock()->attach($product->id);

        return response()->json(['Stock created successfully.', new StockResource($stock)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock = Stock::find($id);
        if (is_null($stock)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new StockResource($stock)]);
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
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'product_quantity' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $stock->name = $request->name;
        $stock->save();
        
        return response()->json(['Stock updated successfully.', new StockResource($stock)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Stock->delete();

        return response()->json('Stock deleted successfully');
    }
}
