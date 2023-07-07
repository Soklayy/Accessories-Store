<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store()
    { 
        if(Auth()->user()->address->address==null){
            return $this->sendMesssage('add your Address first',422);
        }
        $order =  Auth()->user()->order->where('payment_status',false)->first();
        if(!Auth()->user()->order->where('payment_status',false)->first()){
            $order = Order::create([
                'user_id' => Auth()->user()->id,
                'transaction_id' => Str::random(20),
            ]);
        }

        foreach($order->orderItem as $item){
            $item->delete();
        }
        
        return $this->makeOrder($order);
        
    }

    public function checkout(PaymentRequest $request)
    {
        $request->validated();
        $order = Auth()->user()->order->where('id',$request->order_id)->first();
        if (!$order) return $this->sendMesssage('Wrong order_id');

        $i = 0;
        $items = [];
        foreach ($order->orderItem as $item) {
            $items[$i] = $item;
            $i++;
        }

        $item = base64_encode(json_encode($items));
        $string = '';

        $parameter = [
            "req_time"              =>  date('YYYYmmddHis'),
            "merchant_id"           =>  config('settings.payment.merchant_id'),
            "tran_id"               =>  Str::random(20),
            "amount"                =>  $order->total,
            "items"                 =>  $item,
            "firstname"             =>  $request->firstname,
            "lastname"              =>  $request->lastname,
            "email"                 =>  $request->email,
            "phone"                 =>  $request->phone,
            "type"                  =>  'perchase',
            "payment_option"        =>  $request->payment_option,
            "return_url"            =>  base64_encode(route('webhook', $order)),
            "continue_success_url"  =>  $request->continue_success_url,
            "currency"              =>  'USD',
            "return_params"         =>  base64_encode(Auth()->user()->email),
        ];

        foreach ($parameter as $par) {
            $string .= $par;
        }

        $parameter['hash'] = base64_encode(hash_hmac("sha512", $string, config('settings.payment.public_key'), true));

        $response = Http::post(config('settings.payment.checkout_api_url'), $parameter);

        $link = $response->transferStats->getHandlerStat('url');

        return $this->sendReponse(['Checkout_link' => $link], 'Checkout link');
    }

    public function webhook(Order $order, Request $request)
    {
        $data = $request->all();
        if (isset($data['status'])) {
            if ($data['status'] == 0) {
                $order->update([
                    'payment_status' => true
                ]);

                $user = User::find($order->user_id)->first();

                foreach($user->shopping->cartItem as $item){
                    $item->delete();
                }
            }
        }
    }




    private function makeOrder(Order $order){
        DB::beginTransaction();
        try {
            $shopping = Auth()->user()->shopping;
            foreach ($shopping->cartItem as $item) {
                $product = $item->product;
                $grandTotal = 0;
                //check stock
                if ($product->inventory->instock == true) {
                    if ($product->inventory->quantity - $item->quantity >= 0) {
                        OrderItem::create([
                            'order_id'  => $order->id,
                            'product_id' => $product->id,
                            'quantity'  => $item->quantity
                        ]);

                        $grandTotal += $item->quantity * $product->price * (100 - $product->discount) / 100;
                    } elseif ($product->inventory->quantity > 0) {
                        OrderItem::create([
                            'order_id'  => $order->id,
                            'product_id' => $product->id,
                            'quantity'  => $product->inventory->quantity
                        ]);

                        $grandTotal += $product->inventory->quantity * $product->price * (100 - $product->discount) / 100;
                    }
                }
            }

            $order->update([
                'total' => $grandTotal
            ]);
            DB::commit();

            return $this->sendMesssage('added item to order');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function oldOrder(Order $order){

    }


}

