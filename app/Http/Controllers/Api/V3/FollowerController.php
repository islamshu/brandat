<?php

namespace App\Http\Controllers\Api\V3;

use App\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Seller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\FollowerCollection;
use App\Http\Resources\ProductFollowerCollection;
use App\Http\Resources\V3\SellerResource;
use App\User;
use App\Models\V3\Seller ;
class FollowerController extends BaseController
{
 
    public function index(Request $request)
    {

        $followers =  Follower::where('user_id',auth('api')->id())->with('seller')->whereHas('user' ,function($q){
            $q->whereHas('publish_products');
        } )->get();

        foreach($followers as $follow){
            if(@$follow->user->seller == null){
                Follower::where('seller_id',$follow->seller_id)->first()->delete();
            }
        }
        $message = new FollowerCollection($followers);
        return $this->sendResponse($message, translate('this is all follower'));

        
    }
    public function seller_type($type){
        $q = Seller::query()->where('verification_status',1);
        if($type == 'brandat'){
            $q->where('vendor_pakege_id',2);  
        }elseif($type == 'famous'){
            $q->where('vendor_pakege_id',5);  
        }
      $data =SellerResource::collection($q->get());
      return $this->sendResponse($data,'get seller by type');
    }
    public function users_count(){
        
        $success = User::count();
        return $this->sendResponse($success,'this is all user');
 
    }
    public function product_vendor_follow(){
        $followers =  Follower::where('user_id',auth('api')->id())->with('seller')->with('user')->get();
        foreach($followers as $follow){
            if(@$follow->user->seller == null){
                Follower::where('seller_id',$follow->seller_id)->first()->delete();
            }
        }
        $message = new ProductFollowerCollection($followers);

        return $this->sendResponse($message, translate('this is all product for following'));

    }
    public function following_seller(){
        $array = [];
        $followes = Follower::where('user_id',auth('api')->id())->get();
        foreach($followes as $follow ){
            array_push($array,$follow->seller_id);
        }
        $data =SellerResource::collection(Seller::whereIn('user_id',$array)->where('verification_status',1)->get());
        return $this->sendResponse($data,'get seller by type');


    }


    public function store(Request $request)
    {
        if($request->user_id == auth('api')->id()){
            return $this->sendError( translate('Cant follow yourself'));
        }
        $sell = Seller::where('user_id',$request->user_id)->where('verification_status',1)->first();

        if($sell){
            $follow = Follower::where('user_id',auth('api')->id())->where('seller_id',$request->user_id)->first();
            if($follow){
                $follow->delete();
                return $this->sendResponse('success', translate('successfuly deleted'));
    
            }else{
                $follow = new Follower();
                $follow->user_id = auth('api')->id();
                $follow->seller_id=$request->user_id;
                $follow->save();
                return $this->sendResponse('success', translate('successfuly add'));

            }
        }else{
            return $this->sendError( translate('Seller not found'));

        }
        
    }


}
