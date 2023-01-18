<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;


class ServiceController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Service::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"   data-id="' . $row->id . '" data-original-title="View" id="sendnoti' . $row->id . '" class="view view_btn btn btn-success mr-1 btn-sm viewItem">View</a>';
                    $btn = $btn. '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editItem">Edit</a>';
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
                 ->addColumn('icon', function ($row) {
                    if ($row->icon)
                        $icon = "<img width='50' height='50' src='$row->icon' >";
                    else
                        $icon = "<img align='center' width='40' height='40' src='https://via.placeholder.com/150' >";
                    return $icon;
                })
                ->rawColumns(['icon','image','action'])
                ->make(true);
        }

        return view('admin/pages/service');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

      $image = $this->uploadImage($request, 'service_hidden_image', 'image') ;
       $icon = $this->uploadImage($request, 'service_hidden_icon', 'icon');
     
        $details = [
            'title' => trim($request->title),
            'description' => $request->description,
            'order_by' => $request->order_by,
            'image'=>$image,
            'icon'=>$icon,
            'status' => $request->status,
        ];

        $Services = Service::updateOrCreate(['id' => $request->Item_id], $details);

        return response()->json(['success' => 'Services saved successfully.']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Service::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {

        $fileArray = array('image','icon');
        $item = Service::find($id);
        if($item->image != null || $item->icon != null)
        {
            $this->deleteImage($item, $fileArray);
        }

        Services::find($id)->delete();
        return response()->json(['success' => 'Services deleted successfully.']);
    }

    protected function uploadImage($requests, $hiddenname, $filename)
    {

        if (isset($requests)) {

            if ($requests->file($filename)) {
      
              $files = $requests->file($filename);
                $ran = rand(1000, 9999);
                $destinationPath = public_path() . '/assets/images/services'; // upload path
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
            $destinationPath = public_path() . '/assets/images/services'; // upload path
            $deletefile = $destinationPath . '/' . basename($deletefile);
            File::delete($deletefile);
        }

    }
}
