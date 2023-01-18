<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\UserBookingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Booking::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" class="view view_btn btn btn-success mr-1 btn-sm viewItem">View Detail</a>';
                    // $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editItem">Edit</a>';
                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="mt-1 btn btn-danger btn-sm deleteItem">Delete</a>';
                    return $btn;
                })
                ->addColumn('username', function($data)
                          {
                             $user = $data = User::find($data->user_id);
                             return $user->first_name .' '. $user->last_name;
                          })
                  ->addColumn('provider_name', function($data)
                          {
                              $user = $data = User::find($data->provider_id);
                            return $user->first_name .' '. $user->last_name;
                          })
                    ->addColumn('order_date', function($data)
                          {
                             
                             return $data->created_at->format('M d, Y H:i:s');
                          })
                ->rawColumns(['username','provider_name','order_date','action'])
                ->make(true);
        }
        return view('admin/pages/order');
    }
    
    
    
    public function getOrderProduct(Request $request)
    {
        if ($request->ajax()) {
            $data = BookingDetail::where('order_id',$request->orderid)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($data)
                          {
                              $name = $data = Product::find($data->product_id);
                             return $name->name;
                          })
                  ->addColumn('unit_price', function($data)
                          {
                              $unit_price = $data = Product::find($data->product_id);
                             return $unit_price->unitPrice;
                          })
                ->rawColumns(['name','unit_price','action'])
                ->make(true);
        }
        return view('admin/pages/order');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

            $details = [
                'sku' => $request->sku,
                'name' => $request->name,
                'unitPrice' => $request->unitPrice,
                'minQty' => $request->minQty,
                'multQty' => $request->multQty,
                'barcode' => $request->barcode,
                'longDesc' => $request->longDesc,
                'category' => $request->category,
                'status' => $request->status == 'on' ? 1 : 0,
            ];

        Booking::updateOrCreate(['id' => $request->Item_id], $details);

        return response()->json(['success' => 'Order saved successfully.']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Booking::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function changeStatus(Request $request)
    {
        $product_id = $request->product_id;
        $product_data = Booking::find($product_id);
        $is_update = 0;
        if ($product_data->status == 1) {
            $product_data->update(['status' => 0]);
            $is_update = 0;
        } else {
            $product_data->update(['status' => 1]);
            $is_update = 1;
        }

        return response()->json([
            'message' => "You Update a Order Status Successfully",
            'is_update' => $is_update,
            'product_id' => $product_id,
            'status' => 'success'], 200);

    }


    public function destroy($id)
    {
        $item = Booking::find($id);
        $item->delete();
        return response()->json(['success' => 'Order deleted successfully.']);
    }
    public function getInvoice($id=null,$method=null)
    {
        $booking_data = Booking::find($id);
        $customer_data = User::find($booking_data->user_id);
        $provider_data =  User::find($booking_data->provider_id);
        $booking_service_data = UserBookingService::with('subservice')->where('booking_id',$id)->get();
        return response()->json([
            'message' => "You get Invoice Successfully",
            'booking'=>$booking_data,
            'customer'=>$customer_data,
            'provider'=>$provider_data,
            'booking_service_data' => $booking_service_data ,
            'status' => 'success'], 200);
    }


}
