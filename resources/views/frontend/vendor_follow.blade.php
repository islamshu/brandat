@extends('frontend.layouts.app')
@section('css')
    <style>
        .follow{
            width: 110% !important;
        }
    </style>
@endsection
@section('content')
<section class="mb-4">
    <div class="container">
                
        @php 
            $arr= [];
            $arrss= [];
        @endphp
        @if($followers->count()  != 0)
   
        @foreach ($followers as $key=>$follow)
            <!--$arrss[$key]= 1;-->


        @if($follow->user->products->where('published',1)->count() == 0)
        @php
        $arr[$key] =1;
        @endphp
        
        @continue
        
         @else 
         
         @php
         
         
         @endphp
        <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
            <div class="d-flex mb-3 align-items-baseline border-bottom">
                <h3 class="h5 fw-700 mb-0">
                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ $follow->user->shop->shop_name() }}</span>
                    
                </h3>
            </div>
            
            <div class="aiz-carousel gutters-10 half-outside-arrow"  data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
          
                       
                   
                @php
          $slider_error = json_decode(get_setting('error_slider'), true); 
          $error_panner = json_decode(get_setting('error_panner'), true); 
          $error_product = json_decode(get_setting('error_product'), true); 
        $color = App\BusinessSetting::where('type','base_color')->first()->value;
      $products=  App\Product::where('user_id',$follow->user->id)->where('published',1)->latest()->take(5)->get()

          @endphp
          
            
                @foreach ( $products as $key => $product)

                                                   <div class="col mb-4">
    
        <div class="shell-card card">
          <div class="shell-inner card-bodyy">
            <div class="icons">
     <a  href="javascript:void(0)" onclick="addToWishList({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}" data-placement="left"><i class="fas fa-heart fa-2x"></i></a>
                         <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left"><i class="far fa-repeat-alt fa-2x"></i></a>
            </div>

          <a href="{{ route('product', $product->slug) }}">
            <div class="product-img">
              <img src="@if($product->thumbnail_img != null) {{ uploaded_asset($product->thumbnail_img) }} " @else {{ uploaded_asset($error_product) }} " @endif">
            </div>
          </a>

            <div class="points">
              <p>{{ translate('Club Point') }}</p>
              <span>{{ $product->earn_point }}</span>
            </div>

      
          <a href="{{ route('product', $product->slug) }}">
            <div class="card-bodyy">
              <h2>  {{ $product->langname()   }}</h2>
              <p>
                  @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                <del>{{ home_base_price($product->id) }}</del> 
                                @endif
                  
               {{ home_discounted_base_price($product->id) }}        </p>
  <div class="star" style="    color: #991d5b;">
                                   <span> <i class="fas fa-star"></i></span>     {{ $product->rating }} 
                            </div>
              
            </div>
          </a>
        
            
            <div class="cart-1">
              <a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to cart') }}" data-placement="left"><i class="far fa-cart-plus fa-2x"></i></a>
            </div>

          </div>
        </div>
      
        </div>

                @endforeach
                @endif
               
            </div>
        </div>
        @endforeach
    @else
        <div class="row" style="margin-top: 20px;">
                <div class="col-xl-8 mx-auto">
                    <div class="shadow-sm bg-white p-4 rounded">
                        <div class="text-center p-3">
                            <i class="las la-frown la-3x opacity-60 mb-3"></i>
                            <h3 class="h4 fw-700">{{translate('There are no recent products for today')}}</h3>
                            <a href="/all_vendors">{{translate('Follow more Vendors')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
           
            
    </div>
</section>


@endsection