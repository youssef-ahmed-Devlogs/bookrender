<?php

namespace App\Listeners\Auth;

use App\Models\Plan;
use IlluminateAuthEventsRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Registered;

class AssignFreeSubscription
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        $freePlan = Plan::firstOrCreate([
            'is_free' => 1,
            'status' => 'active',
            'price' => 0,
        ], [
            'name' => 'Free Plan',
            'description' => 'This is a free plan for new users.',
            'book_number' => 5,
            'word_number' => 1000,
            'paddle_product_id' => 1,
            'paddle_price_id' => 1,
        ]);

        $user->subscriptions()->create([
            'user_id' => $user->id,
            'plan_id' => $freePlan->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'active',
        ]);
    }
}
