<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" class="view view_btn btn btn-success mr-1 btn-sm viewItem">View</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editItem">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="mt-1 btn btn-danger btn-sm deleteItem">Delete</a>';
                    return $btn;
                })
                ->addColumn('category', function ($row) {
                 $cat_id = Service::find($row->category_id);
                 return $cat_id->title;
                })
                ->rawColumns(['category','action'])
                ->make(true);
        }
        return view('admin/pages/product');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if ($request->import_file) {
            \Excel::import(new ProductImport, $request->import_file);
            return response()->json(['success' => 'Store Imported successfully.']);
        } elseif ($request->sku != null) {

            $details = [
                'name' => $request->name,
                'unitPrice' => $request->unitPrice,
                'minQty' => $request->minQty,
                'multQty' => $request->multQty,
                'longDesc' => $request->longDesc,
                'category' => $request->category,
                'status' => $request->status == 'on' ? 1 : 0,
            ];
        } else {
            return response()->json(['success' => 'FIll all required field.']);
        }
        Product::updateOrCreate(['id' => $request->Item_id], $details);

        return response()->json(['success' => 'Product saved successfully.']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Product::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function changeStatus(Request $request)
    {
        $product_id = $request->product_id;
        $product_data = Product::find($product_id);
        $is_update = 0;
        if ($product_data->status == 1) {
            $product_data->update(['status' => 0]);
            $is_update = 0;
        } else {
            $product_data->update(['status' => 1]);
            $is_update = 1;
        }

        return response()->json([
            'message' => "You Update a Product Status Successfully",
            'is_update' => $is_update,
            'product_id' => $product_id,
            'status' => 'success'], 200);

    }


    public function destroy($id)
    {
        $item = Product::find($id);
        $item->delete();
        return response()->json(['success' => 'Product deleted successfully.']);
    }


}
