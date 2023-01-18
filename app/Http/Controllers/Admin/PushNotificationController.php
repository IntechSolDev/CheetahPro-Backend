<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;


class PushNotificationController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = PushNotification::get();
            return Datatables::of($data)
                ->addIndexColumn()
                
                ->addColumn('action', function ($row) {
                    $row->last_send_at != null ?  $message = "Re-send Notification" : $message = "Send Notification";
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"   data-id="' . $row->id . '" data-original-title="Send" id="sendnoti' . $row->id . '" class="view view_btn btn btn-success mr-1 btn-sm sendNoti">' . $message . '</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-warning btn-sm editItem">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin/pages/push-notification');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        
        
        $details = [
            'title' => trim($request->title),
            'subtitle' => trim($request->subtitle),
            'description' => $request->description,
        ];
       
        $cat = PushNotification::updateOrCreate(['id' => $request->Item_id], $details);
        return response()->json(['success' => 'Notification saved successfully.']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = PushNotification::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        //
    }


   public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        $item = PushNotification::find($request->id); 
        $item->update(['last_send_at'=>$request->last_send_at]);
        $SERVER_API_KEY = env('FIRE_BASE_SERVER_API_KEY');
  
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $item->title,
                "body" => $item->subtitle,  
            ],
            "data"=> ['description'=>$item->description,'type'=>'user_notification']
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
  
        return response()->json(['success' => 'Notification send successfully.']);
    }
    
    
    public function destroy($id)
    {
   
        PushNotification::find($id)->delete();
        return response()->json(['success' => 'Notification deleted successfully.']);
    }

   
}

