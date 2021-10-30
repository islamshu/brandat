@if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
    @php
        $array = array();
        foreach ($sellers as $key => $seller) {
            if($seller->user != null && $seller->user->shop != null){
                $total_sale = 0;
                foreach ($seller->user->products as $key => $product) {
                    $total_sale += $product->num_of_sale;
                }
                $array[$seller->id] = $total_sale;
            }
        }
        asort($array);
    @endphp
    @if(!empty($array))
        <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="d-flex mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Best Sellers')}}</span>
                    </h3>
                    <a href="javascript:void(0)" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('Top 20') }}</a>
                </div>
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3">
                                @php
                        $count = 0;
                        $slider_error = json_decode(get_setting('error_slider'), true); 
                        $error_panner = json_decode(get_setting('error_panner'), true); 
                        $error_product = json_decode(get_setting('error_product'), true); 
                      

                    @endphp
                    @foreach ($array as $key => $value)
                        @if ($count < 20)
                            @php
                                $count ++;
                                $seller = \App\Seller::find($key);
                                $total = 0;
                                $rating = 0;
                                foreach ($seller->user->products as $key => $seller_product) {
                                    $total += $seller_product->reviews->count();
                                    $rating += $seller_product->reviews->sum('rating');
                                }
                            @endphp
   <div class="col mb-4" >
           
            <div class="card-flip ">
                <div class="card-body inner">
            
                <div class="front">
                @if($seller->verify == 1)
  <span class="float-right" @if($seller->verify != 1 ) style="visibility: hidden;" @endif     ><img src="{{ static_asset('khemarcss/icons/icons8-verified-account-48.png')}}"></span>
  @endif
                    <div class="img-cover float-left">
                <img
                                                src="{{ uploaded_asset($error_product)  }}"
                                                data-src="@if ($seller->user->shop->logo !== null) {{ uploaded_asset($seller->user->shop->logo) }} @else {{ uploaded_asset($error_product)  }} @endif"
                                                alt="{{ $seller->user->shop->name }}"
                                                class="img-fluid lazyload"
                                            >
                    </div>
                    <div class="clearfix1"></div>
                    <h2 style="@if($ll->rtl == 0)     text-align: right; @else     text-align: left; @endif">{{ $seller->user->shop->shop_name() }}</h2>

                </div>
            
                <div class="back">
                      @if ($total > 0)
                       <ul class="float-right">
                                                    {{ renderStarRatingtt($rating/$total) }}
                                                    </ul>
                                                @else
                                                                      <ul class="float-right">
                                                    {{ renderStarRatingtt(0) }}
                                                    </ul>
                                                @endif
                    <h2 class="float-left">{{ $seller->user->shop->shop_name() }}  </h2>
                    <div class="clearfix"></div>
                    <p >{{ App\City2::find($seller->user->shop->address)->longName()}}</p>
                    @php
                    $shop = $seller->user->shop;
                    @endphp
                     @guest
                            <input  style="background:#2d4278;color:white"    type="button" name="butt" 
                            value="{{ translate('Follow')}}"
                            naa="{{ $shop->user_id }}" class=" myButton1 login float-right"  id=""></input>
                          @else

                            @php
                                $is_follow = App\Follower::where('user_id',Auth::id())->where('seller_id',$shop->user_id)->first();
                                
                            @endphp
                           
            <input  style="background:{{  $is_follow == null ? '#2d4278' : 'red' }};color:white"      type="button" name="butt" 
                                            @if($is_follow == null)
                                            value="{{ translate('Follow')}}"
                                            true="0"
                                            @else
                                            value="{{ translate('Un Follow')}}"
                                            true="1"
                                            @endif
                                            naa="{{ $shop->user_id }}" class=" follow_seller float-right " my_id="{{ Auth::id() }}" value="Follow" ></input>
           
                            @endguest
                </div>


                    </div>
                </div>
            </div>                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif
@endif

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
        $(".follow_seller").click(function(){
          
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
