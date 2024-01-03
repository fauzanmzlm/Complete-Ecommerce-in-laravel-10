<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use PDF;
// use Notification;
use Illuminate\Support\Facades\Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders=Order::orderBy('id','DESC')->paginate(10);
        return view('backend.order.index')->with('orders',$orders);
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
        // Get the current date
        $from_date = Carbon::now();

        // Add 7 days to the current date
        $to_date = $from_date->copy()->addDays(7);

        // Convert dates to desired format (optional)
        $from_date_formatted = $from_date->format('Y-m-d');
        $to_date_formatted = $to_date->format('Y-m-d');

        if(empty(Cart::where('user_id',auth()->user()->id)->where('order_id',null)->first())){
            session()->flash('error','Cart is Empty !');
            return back();
        }

        $order=new Order();
        $order_data=$request->all();
        $order_data['order_number']='BKG-'.strtoupper(Str::random(10));
        $order_data['user_id']=$request->user()->id;

        $order_data['quantity']=Helper::cartCount();

        $order_data['from_date'] = $from_date_formatted;
        $order_data['to_date'] = $to_date_formatted;

        $order_data['status']="pending";
        
        $order->fill($order_data);
        $status=$order->save();
        if($order)
        $users=User::where('role','admin')->first();
        $details=[
            'title'=>'New booking created',
            'actionURL'=>route('order.show',$order->id),
            'fas'=>'fa-file-alt'
        ];
        Notification::send($users, new StatusNotification($details));
        
        session()->forget('cart');
        
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

        // dd($users);        
        session()->flash('success','Your equipment successfully placed in booking waiting list');
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=Order::find($id);
        // return $order;
        return view('backend.order.show')->with('order',$order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order=Order::find($id);
        return view('backend.order.edit')->with('order',$order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order=Order::find($id);
        $this->validate($request,[
            'status'=>'required|in:pending,confirmed,completed,cancelled'
        ]);
        $data=$request->all();
        // return $request->status;
        if($request->status=='delivered'){
            foreach($order->cart as $cart){
                $product=$cart->product;
                // return $product;
                $product->stock -=$cart->quantity;
                $product->save();
            }
        }
        $status=$order->fill($data)->save();
        if($status){
            session()->flash('success','Successfully updated booking');
        }
        else{
            session()->flash('error','Error while updating booking');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                session()->flash('success','Booking Successfully deleted');
            }
            else{
                session()->flash('error','Booking can not deleted');
            }
            return redirect()->route('order.index');
        }
        else{
            session()->flash('error','Booking can not found');
            return redirect()->back();
        }
    }

    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request){
        // return $request->all();
        $order=Order::where('user_id',auth()->user()->id)->where('order_number',$request->order_number)->first();
        if($order){
            if($order->status=="pending"){
            session()->flash('success','Your reservation request has been submitted and is currently pending confirmation. We will notify you once the reservation is approved.');
            return redirect()->route('order.track');

            }
            elseif($order->status=="confirmed"){
                session()->flash('success','Congratulations! Your reservation has been approved and confirmed. The equipment is now reserved for you. Please pick it up on time.');
                return redirect()->route('order.track');
    
            }
            elseif($order->status=="completed"){
                session()->flash('success','Your reservation period has ended, and the equipment has been successfully returned. Thank you for using our service. If you have any feedback, feel free to share it with us.');
                return redirect()->route('order.track');
    
            }
            else{
                session()->flash('error','Unfortunately, your reservation has been canceled. If you have any questions or concerns, please contact our support team for assistance.');
                return redirect()->route('order.track');
    
            }
        }
        else{
            session()->flash('error','Invalid booking number please try again');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request){
        $order=Order::getAllOrder($request->id);
        // return $order;
        $file_name=$order->order_number.'-'.$order->first_name.'.pdf';
        // return $file_name;
        $pdf=PDF::loadview('backend.order.pdf',compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request){
        $year=\Carbon\Carbon::now()->year;
        // dd($year);
        $items=Order::with(['cart_info'])->whereYear('created_at',$year)->where('status','delivered')->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
            // dd($items);
        $result=[];
        foreach($items as $month=>$item_collections){
            foreach($item_collections as $item){
                $amount=$item->cart_info->sum('amount');
                // dd($amount);
                $m=intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount :$result[$m]=$amount;
            }
        }
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
}
