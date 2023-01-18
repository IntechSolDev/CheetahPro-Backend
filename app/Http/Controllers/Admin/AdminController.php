<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SubService;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class AdminController extends Controller
{


    public function index()
    {
      $users = User::latest()->get();
      $user_count = User::where('type','User')->count();
      $user_provider_count = User::where('type','Service Provider')->count();
      $service = Service::count();
      $subservice = SubService::count();
      $products = Product::count();
      $orders = Order::count();
      return view('admin/pages/index',['users'=>$users,'user_count'=>$user_count,'user_provider_count'=>$user_provider_count,'service'=>$service,'subservice'=>$subservice,'products'=>$products,'orders'=>$orders]);
    }


}

