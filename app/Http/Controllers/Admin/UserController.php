<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('type','User')->orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                   ->addColumn('online', function ($row) {
                    if ($row->isOnline())
                          $online = "<span class='badge badge-success'>Online</span>";
                            else
                          $online= "<span class='badge badge-danger'>Offline</span>";

                    return $online;
                })
                 ->addColumn('name', function ($row) {
                     return $row->first_name .' '.$row->last_name;
                 })
                ->addColumn('action', function ($row) {
                    // $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" class="view view_btn btn btn-success mr-1 btn-sm viewItem">View Referral</a>';
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editItem">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['name','online','action'])
                ->make(true);
        }

        return view('admin/pages/user');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        if($request->Item_id)
        {
            $user = User::find($request->Item_id);
            $request->password ? $pass = bcrypt($request->password) : $pass = $user->password;
        }
        else
        {
            $pass = bcrypt($request->password);
        }
        $details = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => trim($request->email),
            'password' => $pass,
//            'status'=> $request->status=='on' ? 1 : 0,
        ];
        $user_data = User::updateOrCreate(['id' => $request->Item_id], $details);
        if($user_data)
        {
            $user = User::where('email',trim($request->email))->first();
            $api_token = $user->createToken($user->email)->accessToken;
            User::where('id', $user->id)->update(['api_token' => $api_token]);
            return response()->json(['success' => 'User Updated successfully.']);
        }
        else
        {
            return response()->json(['error' => 'User was not updated.']);
        }
    }

    public function show($id)
    {
        //
    }



    public function edit($id)
    {
        $item = User::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $fileArray = array('image');
        $item = User::find($id);
        if($item->image != null)
        {
            $this->deleteImage($item, $fileArray);
        }

        User::find($id)->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

}


