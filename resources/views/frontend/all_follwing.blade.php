@extends('frontend.layouts.app')




    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" />

    <link rel="styleSheet" href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css>
    
    <link rel="stylesheet" href="{{ static_asset('khemarcss/style.css') }}" />
@section('content')
@php 
$color = App\BusinessSetting::where('type','base_color')->first()->value;

@endphp
<section class="pt-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 text-center text-lg-left">
                <h1 class="fw-600 h4"  style="color :{{$color}} ">{{ translate('All Vendors') }}</h1>
            </div>
            <div class="col-lg-4 text-center text-lg-left">
                <div class=" bg-white">
                    <div class="position-relative flex-grow-1">
                        <form action="{{ route('seller_seacrh') }}" method="get" class="stop-propagation">
                            
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="input-group">
                                
                                    <input type="text" class="border-0 border-lg form-control" id="search" @if ($request != null) value="{{$request->name}}" @endif   name="name" placeholder="{{translate('Seller Name')}}" autocomplete="off">
                                    <div class="input-group-append  d-lg-block">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="la la-search la-flip-horizontal fs-18"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="/all_vendors">"{{ translate('All Vendors') }}"</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
  @php
                $slider_error = json_decode(get_setting('error_slider'), true); 
                $error_panner = json_decode(get_setting('error_panner'), true); 
                $error_product = json_decode(get_setting('error_product'), true); 
                $error_vendor = json_decode(get_setting('error_vendor'), true); 
                $lang = Session()->get('locale');

                 $color = App\BusinessSetting::where('type','base_color')->first()->value;
                @endphp
    <div class="items">
        <div class="container">
            <div class="row">
            @foreach($vendors as  $following)
   
                <div class="col" >
                    <div class="item">
                        <div class="item-circle">
                            <img src="@if ($following->user->shop->logo !== null) {{ uploaded_asset($following->user->shop->logo) }} @else {{ uploaded_asset($error_vendor)  }} @endif" />
                        </div>
                        <div class="item-body">
                            
                            <span @if($following->verify != 1 ) style="visibility: hidden;" @endif     ><img src="{{ static_asset('khemarcss/icons/icons8-verified-account-48.png')}}"></span>
                            <h2><a style="color:white" href="{{ route('shop.visit', $following->user->shop->slug) }}">{{ $following->user->shop->shop_name() }}</a></h2>
                            <p>{{ App\City2::find($following->user->shop->address)->longName()}}</p>
                           <div class="star" style="text-al">
                                    <div class="middle">
                               @php
                                            $following->seller_id = \App\Seller::find($following->id);
                                            $total = 0;
                                            $rating = 0;
                                            foreach ($following->user->products as $key => $following->seller_product) {
                                                $total += $following->seller_product->reviews->count();
                                                $rating += $following->seller_product->reviews->sum('rating');
                                            }
                                            @endphp
                                     <div class="rating rating-sm mb-1">
                                                        @if ($total > 0)
                                                            {{ renderStarRating($rating/$total) }}
                                                        @else
                                                            {{ renderStarRating(0) }}
                                                        @endif
                                                    </div>
                                                                   
                                    
                                    </div>
                                </div>
                            <ul>
                                
                                @if( $following->user->shop->twitter != null)
                                                                <li><a href="{{$following->user->shop->twitter}}"><i class="fab fa-twitter fa-2x"></i></a></li>
                                @endif
                                @if( $following->user->shop->instagram != null)
                                                                <li><a href="{{$following->user->shop->instagram}}"><i class="fab fa-instagram fa-2x"></i></a></li>
                                @endif
                                    @if( $following->user->shop->snapchat != null)
                                                                <li><a href="{{$following->user->shop->snapchat}}"><i class="fab fa-snapchat fa-2x"></i></a></li>
                                @endif
                                 @if( $following->user->shop->tiktok != null)
                                                                <li><a href="{{$following->user->shop->tiktok}}"><i class="fab fa-tiktok fa-2x"></i></a></li>
                                @endif
                                
                                
                               
                            </ul>
                            <div class="btns" style="display:flex">
                                <div class="cover stor"><button><a style="color:white" href="{{ route('shop.visit', $following->user->shop->slug) }}">{{ translate('vist store new') }}</a></button></div>
                                
                                
                                  @php
                    $shop = $following->user->shop;
                    @endphp
                     @guest
                         <div class="cover stor">   <input  style="background:#6c7897;color:white"    type="button" name="butt" 
                            value="{{ translate('Follow')}}"
                            naa="{{ $shop->user_id }}" class=" myButton1 login float-right"  id=""></input></div>
                          @else

                            @php
                                $is_follow = App\Follower::where('user_id',Auth::id())->where('seller_id',$shop->user_id)->first();
                                
                            @endphp
                           
          <div class="cover stor">      <input  style="background:{{  $is_follow == null ? '#6c7897' : 'red' }};color:white"      type="button" name="butt" 
                                            @if($is_follow == null)
                                            value="{{ translate('Follow')}}"
                                            true="0"
                                            @else
                                            value="{{ translate('Un Follow')}}"
                                            true="1"
                                            @endif
                                            naa="{{ $shop->user_id }}" class=" follow_sellerrr float-right " my_id="{{ Auth::id() }}" value="Follow" ></input></div>
           
                            @endguest
                                
                            
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            
            </div>
            
                                        {{ $vendors->links() }}

        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@php
    $my_id = Auth::id();
@endphp
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script script src="{{ static_asset('khemarcss/fontawesome-free-5.15.3-web/fontawesome-free-5.15.3-web/js/all.min.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>
    $(document).ready(function() {
        $(".login").click(function(){
            var seller_id = $(this).attr('naa');
           
            AIZ.plugins.notify('warning', '{{ translate('Please login first') }}');

        });
        $(".follow_sellerrr").click(function(){
          
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
                            $(thisContext).css("background","#6c7897");     
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

    
