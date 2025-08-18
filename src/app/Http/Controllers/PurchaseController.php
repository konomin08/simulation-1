<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Stripe
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\Order;
use Stripe\PaymentIntent;

class PurchaseController extends Controller
{
    // 購入画面の表示
    public function show($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user    = Auth::user();

        $address = DeliveryAddress::where('user_id', $user->id)
            ->where('product_id', $item_id)
            ->first();

        return view('purchase.show', compact('product', 'user', 'address'));
    }

    // 支払い方法の保存（プルダウンのonChangeで呼ばれる）
    public function setPaymentMethod(Request $request, $item_id)
    {
        $request->validate([
            'payment_method' => 'required|in:convenience_store,credit_card',
        ]);
        session(['payment_method' => $request->payment_method]);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

    // 購入処理（Checkout作成 → Stripeへ遷移）
    public function store(Request $request, $item_id)
    {
        $user = auth()->user();
        $product = Product::findOrFail($item_id);
    
        $method = session('payment_method'); // 'convenience_store' | 'credit_card'
        if (!in_array($method, ['convenience_store','credit_card'], true)) {
            return redirect()->route('purchase.show', ['item_id'=>$product->id])
                ->withErrors(['payment_method'=>'支払い方法を選択してください。']);
        }
    
        Stripe::setApiKey(config('services.stripe.secret'));
    
        $types = $method === 'convenience_store' ? ['konbini'] : ['card'];
    
        // ★ success_url は Pending にする（session_id クエリ必須）
        $successUrl = route('purchase.pending', ['item_id'=>$product->id], true)
                    . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl  = route('purchase.show', ['item_id'=>$product->id], true);
    
        $params = [
            'mode' => 'payment',
            'payment_method_types' => $types,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $product->name],
                    'unit_amount' => (int)$product->price,
                ],
                'quantity' => 1,
            ]],
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
            'customer_email' => $user->email,
            'metadata' => [
                'product_id' => (string)$product->id,
                'user_id'    => (string)$user->id,
                'method'     => $method,
            ],
        ];
    
        if ($method === 'convenience_store') {
            $params['payment_method_options'] = [
                'konbini' => ['expires_after_days' => 3],
            ];
        }
    
        $session = CheckoutSession::create($params);
    
        // ★ 注文を pending で記録
        Order::create([
            'user_id'           => $user->id,
            'product_id'        => $product->id,
            'amount'            => (int)$product->price,
            'status'            => 'pending',
            'stripe_session_id' => $session->id, // cs_test_***
        ]);
    
        return redirect()->away($session->url, 303);
    }
    
    public function pending(Request $request, $item_id)
    {
        $product   = Product::findOrFail($item_id);
        $sessionId = $request->query('session_id'); // cs_***
    
        $order = Order::where('stripe_session_id', $sessionId)->first();
    
        if ($order && $order->status === 'paid') {
            return redirect()->route('purchase.success', ['item_id'=>$item_id]);
        }
    
        return view('purchase.pending', compact('product','sessionId','order'));
    }
    
    // 手動チェック（Pending画面のボタンから）
    public function checkStatus(Request $request, $item_id)
    {
        $sessionId = $request->input('session_id');
        if (!$sessionId) return back();
    
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
    
        if (($session->payment_status ?? null) === 'paid') {
            Order::where('stripe_session_id', $sessionId)->update(['status'=>'paid']);
            return redirect()->route('purchase.success', ['item_id'=>$item_id]);
        }
    
        if ($session->payment_intent) {
            $pi = PaymentIntent::retrieve($session->payment_intent);
            if ($pi->status === 'succeeded') {
                Order::where('stripe_session_id', $sessionId)->update([
                    'status'=>'paid', 'stripe_pi'=>$pi->id,
                ]);
                return redirect()->route('purchase.success', ['item_id'=>$item_id]);
            }
        }
    
        return back()->with('status', 'まだ入金が確認できていません。数分後に再度お試しください。');
    }
    
    public function success($item_id)
    {
        $product = Product::findOrFail($item_id);
        return view('purchase.success', compact('product'));
    }
    
    // cancel は購入画面に戻す（そのままでOK）
    public function cancel($item_id)
    {
        return redirect()->route('purchase.show', ['item_id'=>$item_id]);
    }
}
