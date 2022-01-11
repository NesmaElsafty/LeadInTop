<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Order;
use App\Models\ProductStock;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Order::latest()->get();
        return response()->json([OrderResource::collection($data), 'Orders fetched.']);
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
        $validator = Validator::make($request->all(),[
            'product_quantity' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }
        
        $pivot = ProductStock::where('product_id', $request->product_id)->first();
        $stock = Stock::where('id' , $pivot->stock_id)->first();

        // dd($stock->product_quantity);
        if ($request->product_quantity > $stock->product_quantity) {
            return response()->json(['this quantity is out of stock']);
        }else{
            $order = Order::create([
                'product_id' => $request->product_id,
                'product_quantity' => $request->product_quantity,
            ]);

            $product_quantity = $stock->product_quantity;
            $product_quantity -= $request->product_quantity;

            $stock->product_quantity =  $product_quantity;
            $stock->save();
        return response()->json(['Order created successfully.', new OrderResource($order)]);
        }
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
        $order = Order::find($id);
        if (is_null($order)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new OrderResource($order)]);
    }
}
