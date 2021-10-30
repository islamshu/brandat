<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
Use App\City2;
use App\Follower;
use App\Seller;
use Carbon\Carbon;

class ProductFollowerCollection extends ResourceCollection
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
                    'shop_name' => $data->user->shop->name,
                    'address_ar' =>City2::find($data->user->shop->address)->name,
                    'address_en' =>City2::find($data->user->shop->address)->name_en,
                    'followers' =>Follower::where('seller_id',$data->seller_id)->count(),
                    'rating'=>$this->get_rate($data),
                    'count_for_last_24_product'=>$data->user->products->where('published',1)->whereBetween('updated_at', [Carbon::today(), date('Y-m-d').' 23:59:59'])->count(),
                    'last_24_product'=>new ProductCollection($data->user->products->where('published',1)->whereBetween('updated_at', [Carbon::today(), date('Y-m-d').' 23:59:59'])),
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
