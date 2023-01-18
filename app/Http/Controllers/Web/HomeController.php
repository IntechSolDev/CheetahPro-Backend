<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
set_time_limit(0);
class HomeController extends Controller
{
    public function index($id=null)
    {
        $products = Product::all();

        return view('web.pages.index');
    }
}
