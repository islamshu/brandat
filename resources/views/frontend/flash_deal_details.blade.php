@extends('frontend.layouts.app')

@section('content')
@php
$slider_error = json_decode(get_setting('error_slider'), true); 
$error_panner = json_decode(get_setting('error_panner'), true); 
$error_product = json_decode(get_setting('error_product'), true); 
$color = App\BusinessSetting::where('type','base_color')->first()->value;
@endphp
    @if($flash_deal->status == 1 && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date) 
    <div style="background-color:{{ $flash_deal->background_color }}">
        <section class="text-center mb-5">
            <img
                src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                data-src="{{ uploaded_asset($flash_deal->banner) }}"
                alt="{{ $flash_deal->title }}"
                class="img-fit w-100 lazyload"
            >
        </section>
        <section class="mb-4">
            <div class="container">
                <div class="text-center my-4 text-{{ $flash_deal->text_color }}">
                    <h1 class="h2 fw-600">{{ $flash_deal->title }}</h1>
                    <div class="aiz-count-down aiz-count-down-lg ml-3 align-items-center justify-content-center" data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                </div>
                <div class="row gutters-5 row-cols-xxl-5 row-cols-lg-4 row-cols-md-3 row-cols-2">
                    @foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product)
                        @php
                            $product = \App\Product::find($flash_deal_product->product_id);
                        @endphp
                        @if ($product->published != 0)
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

                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    </div>
    @else
        <div style="background-color:{{ $flash_deal->background_color }}">
            <section class="text-center">
                <img src="{{ uploaded_asset($flash_deal->banner) }}" alt="{{ $flash_deal->title }}" class="img-fit w-100">
            </section>
            <section class="pb-4">
                <div class="container">
                    <div class="text-center text-{{ $flash_deal->text_color }}">
                        <h1 class="h3 my-4">{{ $flash_deal->title }}</h1>
                        <p class="h4">{{  translate('This offer has been expired.') }}</p>
                    </div>
                </div>
            </section>
        </div>
    @endif
@endsection
