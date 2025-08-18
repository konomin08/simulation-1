<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature failed: '.$e->getMessage());
            return response('invalid', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object; // \Stripe\Checkout\Session
                Order::where('stripe_session_id', $session->id)
                    ->update([
                        'status'    => $session->payment_status === 'paid' ? 'paid' : 'pending',
                        'stripe_pi' => $session->payment_intent ?? null,
                    ]);
                break;

            case 'payment_intent.succeeded':
                $pi = $event->data->object; // \Stripe\PaymentIntent
                Order::where('stripe_pi', $pi->id)
                    ->orWhere(function($q){ $q->whereNull('stripe_pi'); })
                    ->where('status','pending')
                    ->update(['status'=>'paid', 'stripe_pi'=>$pi->id]);
                break;
        }

        return response('ok', 200);
    }
}

