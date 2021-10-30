<?php

namespace App\Http\Resources\V3;

use App\Follower;
use Illuminate\Http\Resources\Json\JsonResource;
use Share;

class SellerResource extends JsonResource
{
    public function toArray($request)
    {
        $city = $this->user->shop->city;
        $total = $this->user->seller_reviews->count();
        $rating = $this->user->seller_reviews->sum('rating');
        if ($total > 0)
            $rate = $rating/$total;
        else
            $rate = 0;
        return [
            'name_ar' =>   $this->user->shop->name_ar,
            'name_en' => $this->user->shop->name,


            'user' => [
                'id'=>$this->user_id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'avatar' => $this->user->avatar,
                'avatar_original' => $this->user->avatar_original
            ],
            'package'=>[
                'title_ar'=>$this->user->seller->vendor_package->title,
                'title_en'=>$this->user->seller->vendor_package->title_en,
            ],
            'share_links'=>$this->share($this),

            'shop_fetures'=>$this->verify,
            'logo' => api_asset($this->user->shop->logo),
            'sliders' => $this->convertPhotos(explode(',', $this->user->shop->sliders)),
            'address_ar' => @$city->name,
            'address_en' => @$city->name_en,
            'rate'=>$rate,
            'following_number'=>$this->follwoing($this),
            'facebook' => $this->user->shop->facebook,
            // 'google' => $this->user->shop->google,
            'twitter' => $this->user->shop->twitter,
            'youtube' => $this->user->shop->youtube,
            'instagram' => $this->user->shop->instagram,
            'is_follow'=>$this->is_follow($this),

            // 'rating'=>
            'links' => [
                'featured' => route('shops.featuredProducts', $this->user->shop->id),
                'top' => route('shops.topSellingProducts',  $this->user->shop->id),
                'new' => route('shops.newProducts', $this->user->shop->id),
                'all' => route('shops.allProducts', $this->user->shop->id),
                'brands' => route('shops.brands', $this->user->shop->id),
            ]
        ];
    }
    public function follwoing($seller){
    $follow=    Follower::where('seller_id',$seller->user_id)->count();
    return $follow;
    }
    public function share($data){
        // dd();
        $link = 'https://www.tujjar-oman.com/shop/'.$data->user->shop->slug;
        	$links= Share::load($link, 'seller in tujjar oman store')->services('facebook', 'whatsapp', 'twitter');
        	$links['link']=$link;
        	return $links;

    }
    public function is_follow($seller){
        $follow = Follower::where('user_id',auth('api')->id())->where('seller_id',$seller->user_id)->first();
        if($follow){
            $data = 1;
        }else{
            $data = 0;
        }
        return $data;
    }
    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }

    protected function convertPhotos($data){
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, api_asset($item));
        }
        return $result;
    }
}
