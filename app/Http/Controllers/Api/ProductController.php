<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //Response Status
    public $success = 200;
    public $error = 404;
    public $validate_error = 401;

    // View All Product
    public function viewProduct()
    {
         $user = Auth::user();
         $product_arr = [];
        $all_product = Product::get();
        if($all_product)
        {
            return response()->json([
                'data'=>$all_product,
                'message' => 'Product Data.',
                'status'=>'success',
            ],$this->success);
        }
       return response()->json([
                'data'=>['data'=>[]],
                'message' => 'Product Data not Found.',
                'status'=>'success',
            ],$this->success);
    }

    public function sellProduct(Request $request)
    {
        $user = Auth::user();
        $galleryimages = [];
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'category_id'=>'required',
            'longDesc'=>'required',
            'unitPrice'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }

        $details = [
            'user_id'=>$user->id,
            'name' => $request->name,
            'longDesc' => $request->longDesc,
            'unitPrice' => $request->unitPrice,
            'category_id' => $request->category_id
        ];
        if ($request->hasFile('image')) {
            $extension = $request->image->extension();
            $filename = time().rand(100,999) . "_." . $extension;
            $request->image->move(public_path('/assets/images/product'), $filename);
            $details['image'] = $filename;
        }
        $gallery_image = $request->gallery;
        foreach($gallery_image as $gallery)
        {
            $extension = $gallery->extension();
            $filename = time().rand(100,999) ."_." . $extension;
            $gallery->move(public_path('/assets/images/product'), $filename);
            $galleryimages[] = $filename;
        }
        $details['gallery'] = $galleryimages;
        $pro = Product::updateOrCreate(['id' => $request->product_id], $details);
        if($pro)
        {
            return response()->json(['success' => 'Product added successfully.'],$this->success);
        }
        return response()->json(['error' => 'Product not added successfully.'],$this->error);
    }

    public function offerProduct(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'product_id'=>'required',
            'offer_price'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }
         $product = Product::find($request->product_id);
        if(!$product)
        {
            return response()->json(['error' => 'Product not found'],$this->error);
        }
        $details = [
            'user_id'=>$user->id,
            'owner_id' => $product->user_id,
            'offer_price' => $request->offer_price,
        ];

        $offer = Offer::create($details);
        if($offer)
        {
            return response()->json(['success' => 'Your offer has been send'],$this->success);
        }
        return response()->json(['error' => 'Your offer  not send.'],$this->error);
    }




}
