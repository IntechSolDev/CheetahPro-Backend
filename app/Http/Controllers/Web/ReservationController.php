<?php


namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Reservation::where('user_id',Auth::user()->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)"  class="view btn btn-success btn-sm"><i class="fa fa-eye"></i></a>';
                    $btn = $btn . '<a href="javascript:void(0)"  class="edit btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>';
                    $btn = $btn . '<a href="javascript:void(0)"  class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('web.pages.reservation');
    }

    public function store(Request $request)
    {
        $details = [
            'user_id'=> Auth::user()->id,
            'name'=> $request->name,
            'start_date'=> $request->start_date,
            'end_date'=> $request->end_date,
            'phone_number'=> $request->phone_number,
            'package'=> $request->package,
            'details'=> $request->details

        ];
        $cat = Reservation::updateOrCreate(['id' => $request->Item_id], $details);
        return response()->json(['success' => 'Reservation submit Successfully.']);
    }


    public function edit($id)
    {
        $item = Reservation::find($id);
        return response()->json($item);
    }
}

