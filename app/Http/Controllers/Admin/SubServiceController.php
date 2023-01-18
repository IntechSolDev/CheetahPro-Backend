<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;


class SubServiceController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = SubService::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"   data-id="' . $row->id . '" data-original-title="View" id="sendnoti' . $row->id . '" class="view view_btn btn btn-success mr-1 btn-sm viewItem">View</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editItem">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Delete</a>';
                    return $btn;
                })
                ->addColumn('image', function ($row) {
                    if ($row->image)
                        $image = "<img width='50' height='50' src='$row->image' >";
                    else
                        $image = "<img align='center' width='40' height='40' src='https://via.placeholder.com/150' >";
                    return $image;
                })
                ->addColumn('service', function ($row) {
                    if($row->main_service_id != null)
                    {
                        $cat = Service::find($row->main_service_id);
                        return $cat->title;
                    }
                    return null;
                })
                ->rawColumns(['service','image', 'action'])
                ->make(true);
        }
        $services = Service::get();
        return view('admin/pages/sub-service',['services'=>$services]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $image = $this->uploadImage($request, 'service_hidden_image', 'image'); //upload file
        $details = [
            'title' => trim($request->title),
            'description' => $request->description,
            'main_service_id' => $request->main_service_id,
            'order_by' => $request->order_by,
            'bg_color' => $request->bg_color,
            'image' => $image,
            'status' => $request->status,
        ];

        $Services = SubService::updateOrCreate(['id' => $request->Item_id], $details);

        return response()->json(['success' => 'Sub Services saved successfully.']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = SubService::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {

        $fileArray = array('image');
        $item = SubService::find($id);
        if ($item->image != null) {
            $this->deleteImage($item, $fileArray);
        }

        SubService::find($id)->delete();
        return response()->json(['success' => 'Sub Services deleted successfully.']);
    }

  
    protected function uploadImage($requests, $hiddenname, $filename)
    {

        if (isset($requests)) {

            if ($requests->file($filename)) {
              $files = $requests->file($filename);
                $ran = rand(1000, 9999);
                $destinationPath = public_path() . '/assets/images/subServices'; // upload path
                $deletefile = $destinationPath . '/' . $requests->$hiddenname;
                File::delete($deletefile);
                $upImage = date('YmdHis') . $ran . "." . $files->getClientOriginalExtension(); //name convert to unique
                $files->move($destinationPath, $upImage);  // upload image
                return $upImage;
            } else {
                $upImage = $requests->$hiddenname;
                return $upImage;
            }
        } else {
            $upImage = $requests->$hiddenname;
            return $upImage;
        }

    }





    protected function deleteImage($queryObj, $files)
    {

        foreach ($files as $file) {
            $deletefile = $queryObj->$file;
            $destinationPath = public_path() . '/assets/images/subServices'; // upload path
            $deletefile = $destinationPath . '/' . basename($deletefile);
            File::delete($deletefile);
        }

    }
}

