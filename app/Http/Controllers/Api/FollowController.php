<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\FollowingResource;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class FollowController extends Controller
{

    //Follow and Unfollow Api
    public function follow(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $following = User::find($request->following_id);
        if(!$following)
        {
            return response()->json([
                'message' => "user not found",
                'status' => 'error'],404);
        }
        if($request->following_id == $user_id)
        {
            return response()->json([
                'message' => "Not Allowed",
                'status' => 'error'],404);
        }
        $result= Follow::where('following_id',$request->following_id)->where('follower_id',$user_id)->exists();
        if ($result) {
            Follow::where('following_id',$request->following_id)->where('follower_id',$user_id)->delete();
            return response()->json([
                'message' => "Unfollow",
                'unfollow'=>true,
                'follow'=>false,
                'status' => 'success'],200);
        }
        else
        {
            $user=new Follow();
            $user->following_id = $request->following_id;
            $user->follower_id = $user_id;
            $result=$user->save();
            if ($result) {
                return response()->json([
                    'message' => "follow",
                    'unfollow'=>false,
                    'follow'=>true,
                    'status' => 'success'],200);

            }else{
                return response()->json([
                    'message' => "You already follow this person",
                    'status' => 'error'],404);
            }
        }

    }

    //counter follower and following
    public function countFollow(){
        $user = Auth::user();
        $user_id = $user->id;
        $follower = Follow::where('following_id',$user_id)->count();
        $following  = Follow::where('follower_id',$user_id)->count();
        return response(['message'=>'All count follower and following','following'=>$following,'follower'=>$follower,'status'=>'success'],200);

    }

    //Show follower List
    public function getListfollower(){
        $user = Auth::user();
        $user_id = $user->id;
        $follower = Follow::with('follower')->where('following_id',$user_id)->get();
        if($follower->isNotEmpty())
        {
            $followerResource= FollowerResource::collection($follower);
            return response(['message'=>'All follower data','follower_data'=>$followerResource,'status'=>'success'],200);
        }
     else
        {
            return response()->json([
                'message' => "No Follower Found",
                'status' => 'error'],404);

        }

    }

    //Show following List
    public function getListfollowing(){
        $user = Auth::user();
        $user_id = $user->id;
        $following = Follow::with('following')->where('follower_id',$user_id)->get();
        if($following->isNotEmpty())
        {
        $followingResource= FollowingResource::collection($following);
        return response(['message'=>'All following data','following_data'=>$followingResource,'status'=>'success'],200);
         }
        else
        {
        return response()->json([
        'message' => "No Follower Found",
        'status' => 'error'],404);
        }
    }


}
