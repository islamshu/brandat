@extends('frontend.layouts.app')

@section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('shop.visit', $shop->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $shop->name }}" />
@endsection

@section('content')
    <!-- <section>
        <img loading="lazy"  src="https://via.placeholder.com/2000x300.jpg" alt="" class="img-fluid">
    </section> -->

    @php
        $total = 0;
        $rating = 0;
        $color = App\BusinessSetting::where('type','base_color')->first()->value;

        foreach ($shop->user->products as $key => $seller_product) {
            $total += $seller_product->reviews->count();
            $rating += $seller_product->reviews->sum('rating');
        }
    @endphp
    @php
    $slider_error = json_decode(get_setting('error_slider'), true); 
    $error_panner = json_decode(get_setting('error_panner'), true); 
    $error_product = json_decode(get_setting('error_product'), true); 
    @endphp
    <section class="pt-5 mb-4 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="d-flex justify-content-center">
                        <img
                            height="70"
                            class="lazyload"
                            src="{{ uploaded_asset($error_product)  }}"
                            data-src="@if ($shop->logo !== null) {{ uploaded_asset($shop->logo) }} @else {{ uploaded_asset($error_product)  }} @endif"
                            alt="{{ $shop->name }}"
                        >
                        <div class="pl-4 text-left">
                            <h1 class="fw-600 h4 mb-0">{{ $shop->shop_name() }}
                                @if ($shop->user->seller->verify == 1)
<img src="{{ static_asset('khemarcss/icons/icons8-verified-account-48.png')}}" style="height: 20px;">                          
                                @endif
                            </h1>
                            <div class="rating rating-sm mb-1">
                                @if ($total > 0)
                                    {{ renderStarRating($rating/$total) }}
                                @else
                                    {{ renderStarRating(0) }}
                                @endif
                            </div>
                            <div class="location opacity-60">{{ $shop->shop_name() }}</div>

                            
                        </div>
                      
                        <div class="location opacity-60" id="add_button">
                            @php
                            $count = App\Follower::where('seller_id',$shop->user_id)->count();

                            @endphp
                            <div class="rating rating-sm mb-1">
                                {{ translate('Followers') }}&nbsp;	({{ $count }})
                            </div>
                            @guest
                            <input  style="background:#2d4278;color:white"    type="button" name="butt" 
                            value="{{ translate('Follow')}}"
                            naa="{{ $shop->user_id }}" class="btn btn-custom btn-block btn-theme myButton1 login"  id=""></input>
                          @else

                            @php
                                $is_follow = App\Follower::where('user_id',Auth::id())->where('seller_id',$shop->user_id)->first();
                                
                            @endphp
                           
            <input  style="background:{{  $is_follow == null ? '#2d4278' : 'red' }};color:white"    type="button" name="butt" 
                                            @if($is_follow == null)
                                            value="{{ translate('Follow')}}"
                                            true="0"
                                            @else
                                            value="{{ translate('Un Follow')}}"
                                            true="1"
                                            @endif
                                            naa="{{ $shop->user_id }}" class="btn follow_sellerr " my_id="{{ Auth::id() }}" value="Follow" ></input>
           
                            @endguest
                        </div>

                    </div>
                </div>
            </div>
            <div class="border-bottom mt-5"></div>
            <div class="row align-items-center">
                <div class="col-lg-6 order-2 order-lg-0">
                    <ul class="list-inline mb-0 text-center text-lg-left">
                        <li class="list-inline-item ">
                            <a class="text-reset d-inline-block fw-600 fs-15 p-3 @if(!isset($type)) border-bottom border-primary border-width-2 @endif" href="{{ route('shop.visit', $shop->slug) }}">{{ translate('Store Home')}}</a>
                        </li>
                        <li class="list-inline-item ">
                            <a class="text-reset d-inline-block fw-600 fs-15 p-3 @if(isset($type) && $type == 'top_selling') border-bottom border-primary border-width-2 @endif" href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'top_selling']) }}">{{ translate('Top Selling')}}</a>
                        </li>
                        <li class="list-inline-item ">
                            <a class="text-reset d-inline-block fw-600 fs-15 p-3 @if(isset($type) && $type == 'all_products') border-bottom border-primary border-width-2 @endif" href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'all_products']) }}">{{ translate('All Products')}}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 order-1 order-lg-0">
                    <ul class="text-center text-lg-right mt-4 mt-lg-0 social colored list-inline mb-0">
                        @if ($shop->facebook != null)
                            <li class="list-inline-item">
                                <a href="{{ $shop->facebook }}" class="facebook" target="_blank">
                                    <i class="lab la-facebook-f"></i>
                                </a>
                            </li>
                        @endif
                        @if ($shop->twitter != null)
                            <li class="list-inline-item">
                                <a href="{{ $shop->twitter }}" class="twitter" target="_blank">
                                    <i class="lab la-twitter"></i>
                                </a>
                            </li>
                        @endif
                        @if ($shop->instagram != null)
                            <li class="list-inline-item">
                                <a href="{{ $shop->instagram }}" class="instagram" target="_blank">
                                    <i class="lab la-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if ($shop->youtube != null)
                            <li class="list-inline-item">
                                <a href="{{ $shop->youtube }}" class="youtube" target="_blank">
                                    <i class="lab la-youtube"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

    @if (!isset($type))
        <section class="mb-5">
            <div class="container">
                <div class="aiz-carousel dots-inside-bottom" data-arrows="true" data-dots="true" data-autoplay="true">
                    @if ($shop->sliders != null)
                        @foreach (explode(',',$shop->sliders) as $key => $slide)
                            <div class="carousel-box">
                                <img class="d-block w-100 lazyload rounded h-200px h-lg-380px img-fit" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($slide) }}" alt="{{ $key }} offer">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        <section class="mb-4">
            <div class="container">
                <div class="text-center mb-4">
                    <h3 class="h3 fw-600 border-bottom">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Featured Products')}}</span>
                    </h3>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="aiz-carousel gutters-10" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-autoplay='true' data-infinute="true" data-dots="true">
                            @foreach ($shop->user->products->where('published', 1)->where('featured', 1) as $key => $product)
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="mb-4">
        <div class="container">
            <div class="mb-4">
                <h3 class="h3 fw-600 border-bottom">
                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">
                        @if (!isset($type))
                            {{ translate('New Arrival Products')}}
                        @elseif ($type == 'top_selling')
                            {{ translate('Top Selling')}}
                        @elseif ($type == 'all_products')
                            {{ translate('All Products')}}
                        @endif
                    </span>
                </h3>
            </div>
            <div class="row gutters-4 row-cols-xxl-5 row-cols-lg-5 row-cols-md-3 row-cols-2">
                @php
                    if (!isset($type)){
                        $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->orderBy('created_at', 'desc')->paginate(24);
                    }
                    elseif ($type == 'top_selling'){
                        $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->orderBy('num_of_sale', 'desc')->paginate(24);
                    }
                    elseif ($type == 'all_products'){
                        $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->paginate(24);
                    }
                @endphp
                @foreach ($products as $key => $product)
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
            </div>
            <div class="aiz-pagination aiz-pagination-center mb-4">
                {{ $products->links() }}
            </div>
        </div>
    </section>


@endsection
@section('script')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

@php
    $my_id = Auth::id();
@endphp
<script>
    $(document).ready(function() {
        $(".login").click(function(){
            var seller_id = $(this).attr('naa');
           
            AIZ.plugins.notify('warning', '{{ translate('Please login first') }}');

        });
        $(".follow_sellerr").click(function(){
          
            var seller_id = $(this).attr('naa');
            var my_id = $(this).attr('my_id');
              var is_ture = $(this).attr('true');
        
            var thisContext = this;
            if(seller_id == my_id){
                AIZ.plugins.notify('warning', '{{ translate('You cannot follow yourself ') }}');
            }else{
                if(is_ture == 0){
                     $.ajax({
                type: "post",
                url: "{{ route('follow_seller') }}", // need to create this route
                data: { "_token": "{{ csrf_token() }}",'seller_id':seller_id },
                success: function (data) {
                         AIZ.plugins.notify('success', '{{ translate('Follow') }}');

                        $(thisContext).val("{{ translate('Un Follow') }}");  
                        $(thisContext).css("background","red");     
                        $(thisContext).removeAttr("disabled"); 
                        $(thisContext).attr('true', 1)
                    
                    
                   
                }
            });
                }else if(is_ture == 1){
              
                    swal({
  title: "{{translate('Are you sure to Unfollow ?')}}",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      
            var seller_id = $(this).attr('naa');
            var my_id = $(this).attr('my_id');
              var is_ture = $(this).attr('true');
      
            var thisContext = this;

                     $.ajax({
                type: "post",
                url: "{{ route('follow_seller') }}", // need to create this route
                data: { "_token": "{{ csrf_token() }}",'seller_id':seller_id },
                success: function (data) {
          
AIZ.plugins.notify('danger', '{{ translate('UnFollow') }}');
                            $(thisContext).val("{{ translate('Follow') }}");  
                            $(thisContext).css("background","#2d4278");     
                            $(thisContext).removeAttr("disabled");
                            $(thisContext).attr('true', 0)

                }
            });
   
  }
});
                    
                    
                    
            
                }
           
        }
        });
    });
</script>
    
@endsection