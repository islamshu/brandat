<?php

namespace App\Http\Resources;

use App\Follower;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Seller;
use App\City2;
use App\Models\V3\Coupon;
use App\Models\V3\Product;
use Carbon\Carbon;

class FollowerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'user_id' => $data->user->id,
                    'shop_name' => $data->user->shop->name,
                    // 'shop_ptt'=> $data->user->products,
                    'address_ar' =>City2::find($data->user->shop->address)->name,
                    'address_en' =>City2::find($data->user->shop->address)->name_en,
                    'products_count'=>Product::where('user_id',$data->user->id)->where('published',1)->count(),
                    'shop_fetures'=>$data->user->seller->verify,
                    'products'=> new ProductCollection(Product::where('user_id',$data->user->id)->where('published',1)->latest()->take(5)->get()),
                    'followers' =>Follower::where('seller_id',$data->seller_id)->count(),
                    'rating'=>$this->get_rate($data),
                    'shop'=>route('shops.info',$data->user->shop->id),
                    'social ' => [
                        'twitter ' => $data->user->shop->twitter,
                        'instagram' => $data->user->shop->instagram,
                        'snapchat' =>$data->user->shop->snapchat,
                        'tiktok' =>$data->user->shop->tiktok,
                        'facebook' =>$data->user->shop->facebook,
                    ]
                ];
            })
        ];
    }
    protected function get_rate($data){
        $data->seller_id = Seller::find($data->id);
        $total = 0;
        $rating = 0;
        foreach ($data->user->products as $key => $data->seller_product) {
            $total += $data->seller_product->reviews->count();
            $rating += $data->seller_product->reviews->sum('rating');
        }
        if($total > 0){
            $rate = $rating/$total;
        }else{
            $rate = 0;
        }
        return $rate;
    }
}
