<?php

namespace App\Http\Controllers;

use App\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Seller;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user() == null){
            
            flash(translate('Please login first'))->warning();
            return redirect(route('home'));
        }else{
        $followers = Follower::where('user_id',Auth::id())->with('seller')->with('user')->paginate(15);
        // dd($followers->user->shop->products);

 
        return view('frontend.all_follwing')->with('vendors',$followers)->with('request',$request);
        }
    }
    public function product_vendor_follow(){
        if(Auth::user() == null){
            
            flash(translate('Please login first'))->warning();
            return redirect(route('home'));
        }else{
        $followers =  Follower::where('user_id',Auth::id())->with('seller')->with('user')->get();
        

 
        return view('frontend.vendor_follow')->with('followers',$followers);
    }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $follow = Follower::where('user_id',Auth::id())->where('seller_id',$request->seller_id)->first();
        if($follow){
            $follow->delete();
            return 1;
        }else{
            $follow = new Follower();
            $follow->user_id = Auth::id();
            $follow->seller_id=$request->seller_id;
            $follow->save();
            return 2;
 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function show(Follower $follower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function edit(Follower $follower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Follower $follower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Follower $follower)
    {
        //
    }
}
