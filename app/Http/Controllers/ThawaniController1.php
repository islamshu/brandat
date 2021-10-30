<?php

namespace App\Http\Controllers;
use App\Classes\thawani;
use Illuminate\Http\Request;
use Session;
use App\Order;
use Auth;
use App\Http\Controllers\WalletController;

use App\Seller;
use App\BusinessSetting;

class ThawaniController extends Controller
{
    public function thawani(Request $request){
        // dd($request);
    $is_test =     BusinessSetting::where('type', 'thawani_sandbox')->first()->value;
    if($is_test == 1){
        $thawani = new thawani([  
            'isTestMode' => 1, ## set it to 0 to use the class in production mode  
            'public_key' => 'HGvTMLDssJghr9tlN9gr4DVYt0qyBy',  
            'private_key' => 'rRQ26GcsZzoEhbrP2HZvLYDbn9C9et',  
          ]);
        }else{
            $thawani = new thawani([  
                'isTestMode' => 0, ## set it to 0 to use the class in production mode  
                'public_key' => env('thawani_Publishable_key'),  
                'private_key' => env('thawani_Secret_key'),  
              ]);
        }


          if (Session::has('cart') && count(Session::get('cart')) > 0) {
            $qu =0;
           foreach(Session::get('cart') as $key =>$val){
       
            $qu += $val['quantity'];
           }
            $order = Order::findOrFail(Session::get('order_id'));
            $amount = $order->grand_total * 1000;
            $order_id =   Session::get('order_id');
          
          
          
          $customer_name = session::get('shipping_info')['name'];
           $customer_phone = session::get('shipping_info')['phone'];
           $customer_email=   session::get('shipping_info')['email'];
          
          
        }elseif(Session::get('payment_type') == 'wallet_payment' && (Session::get('payment_data')['amount']) > 0){
            $amount =  Session::get('payment_data')['amount'] * 1000;
          $order_id =  rand(0, 99999);
            $customer_name = Auth::user()->name;
           $customer_phone =Auth::user()->phone;
           $customer_email=   Auth::user()->email;
            
            }else {
            $amount =  Session::get('amount_pakege') * 1000;
          $order_id =  rand(0, 99999);
            $customer_name = Auth::user()->name;
           $customer_phone =Auth::user()->phone;
           $customer_email=   Auth::user()->email;
        }
     
            
          $request->op = !isset($request->op)? '' :$request->op; ## to avoid PHP notice message
          switch ($request->op){
              default: ## Generate payment URL
                  $orderId =    $order_id; ## order number based on your existing system
                  $input = [
                      'client_reference_id' => rand(1000, 9999).$orderId, ## generating random 4 digits prefix to make sure there will be no duplicate ID error
                      'products' => [
   
                          ['name' => 'products from '. env('APP_NAME'), 'unit_amount' => $amount, 'quantity' => 1],
                      ],
          //            'customer_id' => 'cus_xxxxxxxxxxxxxxx', ## TODO: enable this when its activate from Thawani Side
          'success_url' => route('thawani.done'), ## Put the link to next a page with the method checkPaymentStatus()
          'cancel_url' => route('thawani.cancel'), 
                      
                      'metadata' => [
                          'order_id' => $order_id,
                          'customer_name' => $customer_name,
                          'customer_phone' => 656565656,
                          'customer_email' =>  $customer_email,
                      ]
                  ];
            
                  $url = $thawani->generatePaymentUrl($input);
                  
                  echo '<pre dir="ltr">' . print_r($thawani->responseData, true) . '</pre>';
                  $request->session()->put($_SERVER['REMOTE_ADDR'],$thawani->payment_id);

                  if(!empty($url)){
                      ## method will provide you with a payment id from Thawani, you should save it to your order. You can get it using this: $thawani->payment_id
                      ## header('location: '.$url); ## Redirect to payment page
                    return redirect($url);
                    }
                  break;
              case 'callback': ## handle Thawani callback, you need to update order status in your database or file system, in Thawani V2.0 you need to add a link to this page in Webhooks
                  $result = $thawani->handleCallback(1);
                  /*
                   * $results contain some information, it will be like:
                   * $results = [
                   *  'is_success' => 0 for failed, 1 for successful
                   *  'receipt' => receipt ID, generate for transaction
                   *  'raw' => [ SESSION DATA ]
                   * ];
                   */
                  if($thawani->payment_status == 1){
                      ## successful payment
                  }else{
                      ## failed payment
                  }
                  break;
              case 'checkPayment':
                  $session =$request->session()->get($_SERVER['REMOTE_ADDR']);
             
                  $check = $thawani->checkPaymentStatus($session);
                  dd(  $check);
                  if($thawani->payment_status == 1){
                    ## successful payment
                    echo '<h2>successful payment</h2>';
                }else{
                    ## failed payment
                    echo '<h2>payment failed</h2>';
                }
                $thawani->iprint_r($check);
                break;
            case 'createCustomer':
                $customer = $thawani->createCustomer('me@alrashdi.co');
                $thawani->iprint_r($customer);
                break;
            case 'getCustomer':
                $customer = $thawani->getCustomer('me@alrashdi.co');
                $thawani->iprint_r($customer);
                break;
            case 'deleteCustomer':
                $customer = $thawani->deleteCustomer('cus_xxxxxxxxxxxxxxx');
                $thawani->iprint_r($customer);
                break;
            case 'home':
                echo 'Get payment status from database';
                break;
        }
    
        }
        public function errorUrl(){
         
            flash(translate('error Occer'))->error();
            return redirect()->route('home');
        }
        public function successUrl(){
            if( Session::get('order_id')  == null  && Session::has('amount_pakege') ){
                

                 $seller = Seller::where('user_id',Auth::id())->first();
            
            $seller->paid = 1;
            $seller->save();
     
            session()->forget('amount_pakege');
            session()->forget('seller_id');
             flash(translate('Your Shop has been created successfully!'))->success();
                        $lang = Session()->get('locale');

    return redirect()->route('shops.index',['lang'=>$lang]); 
   
            }elseif (Session::get('payment_type') == 'wallet_payment') {
            $walletController = new WalletController;
            return $walletController->wallet_payment_done(Session::get('payment_data'), 'thawani');
            
             }else{
            $checkoutController = new CheckoutController;
            $payment = 'thwani';
            flash(translate('success'))->success();
            return $checkoutController->checkout_done(Session::get('order_id'), $payment);    
    }
            
        }
}
