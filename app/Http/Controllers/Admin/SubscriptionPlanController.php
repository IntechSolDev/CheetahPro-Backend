<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Trial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Laravel\Cashier\Cashier;
class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubscriptionPlan::orderBy('sort_order', 'ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" class="view view_btn btn btn-success mr-1 btn-sm viewItem">View</a>';
                    $btn = $btn .'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editItem">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Delete</a>';
                    return $btn;
                })
                 ->addColumn('duration', function ($row) {
                 return $row->duration.' '.$row->plan_duration;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin/pages/subscription');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
             
          if($request->Item_id == null)   
          {
                   $st_id = strtolower(trim($request->title));
                    $duration = explode(",",$request->plan_duration);
                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $stripe = \Stripe\Plan::create(array(
                        "amount" => $request->amount * 100,
                        "interval" =>$duration[0],
                        "interval_count" =>$duration[1],
                        "product" => array("name" => $request->title),
                        "currency" => $request->currency,
                        "id" => $st_id
                    ));
                   $create_sub =  SubscriptionPlan::create([
                        'stripe_product_id'=>$stripe->product,
                        'stripe_plan'=>$st_id,
                        'title'=>$request->title,
                        'sub_title' =>$request->sub_title,
                        'description' =>$request->description,
                        'plan_duration' =>$duration[0],
                        'duration' =>$duration[1],
                        'price' => $request->amount,
                        'currency'=>$stripe->currency,
                        'sort_order' => $request->sort_order,
                    ]);  
           }
           else
           {
                $create_sub =  SubscriptionPlan::find($request->Item_id)->update([
                        'title'=>$request->title,
                        'sub_title' =>$request->sub_title,
                        'description' =>$request->description,
                        'sort_order' => $request->sort_order,
                    ]);  
           }
  
        
        
       if($create_sub)
       {
           return response()->json(['success' => 'Subscription Added Successfully.']);
       }
    else
        {
            return response()->json(['success' => 'Subscription was not added.']);
        }
         } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'],404);
        }

    }

    public function show($id)
    {
        //
    }



    public function edit($id)
    {
        $item = SubscriptionPlan::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $subscription=SubscriptionPlan::find($id);

        if ($subscription) {
            $stripe = new \Stripe\StripeClient(
                 env('STRIPE_SECRET')
            );
            $stripe->plans->delete(
              $subscription->stripe_plan,
              []
            );
            
           $subscription->delete();
            return response()->json(['success' => 'Subscription Deleted Successfully.']);
        }
        else{
            return response()->json(['success' => 'Subscription Not Deleted.']);
        }
    }
    
    
    public function getTrial()
    {
        $item = Trial::first();
        return response()->json($item);
    }
    
    
     public function createTrial(Request $request)
    {
        try {
                   $create_sub =  Trial::updateOrCreate(['id'=>$request->Item_id],[
                        'title'=>$request->title,
                        'sub_title' =>$request->sub_title,
                        'description' =>$request->description,
                        'duration' => $request->duration,
                    ]);  

       if($create_sub)
       {
           return response()->json(['success' => 'Trial Added Successfully.']);
       }
    else
        {
            return response()->json(['success' => 'Trial was not added.']);
        }
         } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'],404);
        }

    }

}

