<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function pricingShow()
    {
        $basicProductPrices = \Stripe\Price::all([
            'active' => true,
            'product' => env('STRIPE_PRODUCT_BASIC'),
            'expand' => ['data.product']
        ])->data;

        $premiumProductPrices = \Stripe\Price::all([
            'active' => true,
            'product' => env('STRIPE_PRODUCT_PREMIUM'),
            'expand' => ['data.product']
        ])->data;

        return view('checkout')
                ->with(compact('basicProductPrices', 'premiumProductPrices'));
    }

    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'price_id' => 'required'
        ]);

        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                  'price' => $request->price_id,
                  'quantity' => 1,
                ]],
                'locale' => 'es',
                'phone_number_collection' => [
                    'enabled' => true,
                ],
                'mode' => 'subscription',
                'success_url' => route('success'). '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
              ]);

              return redirect($checkout_session->url);
        } catch (\Error $e) {
           abort('404');
        }
    }

    public function webhook()
    {
        // Replace this endpoint secret with your endpoint's unique secret
        // If you are testing with the CLI, find the secret by running 'stripe listen'
        // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
        // at https://dashboard.stripe.com/webhooks
        $endpoint_secret = 'whsec_83cddf528b72bcf06a9c6fcafa0296780e072ee735842f5f04119bb83ed92ec7';

        $payload = @file_get_contents('php://input');
        $event = null;
        try {
        $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
        echo '⚠️  Webhook error while parsing basic request.';
        http_response_code(400);
        exit();
        }
        // Handle the event
        switch ($event->type) {
        case 'customer.subscription.trial_will_end':
            $subscription = $event->data->object; // contains a \Stripe\Subscription
            // Then define and call a method to handle the trial ending.
            // handleTrialWillEnd($subscription);
            break;
        case 'customer.subscription.created':
            $subscription = $event->data->object; // contains a \Stripe\Subscription
            // Then define and call a method to handle the subscription being created.
            $this->handleSubscriptionCreated($subscription);
            break;
        case 'customer.subscription.deleted':
            $subscription = $event->data->object; // contains a \Stripe\Subscription
            // Then define and call a method to handle the subscription being deleted.
            // handleSubscriptionDeleted($subscription);
            break;
        case 'customer.subscription.updated':
            $subscription = $event->data->object; // contains a \Stripe\Subscription
            // Then define and call a method to handle the subscription being updated.
            // handleSubscriptionUpdated($subscription);
            break;
        default:
            // Unexpected event type
            echo 'Received unknown event type';
        }
    }

    public function handleSubscriptionCreated($subscription)
    {
        // return response('Hello World', 200)
        // ->header('Content-Type', 'text/plain');
        dd($subscription->data);
        Mail::to('zstrikke@gmail.com')->send(new \App\Mail\SubscriptionCreated($subscription));
    }

}
