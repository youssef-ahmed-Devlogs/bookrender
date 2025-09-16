<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReachedMaximumBooks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->user()->subscriptions as $subscription) {
            if ($subscription->used_books >= $subscription->plan->book_number) {
                return redirect()->route('dashboard.books.index')->with('error', 'You have reached the maximum number of books for your subscription.');
            }
        }

        return $next($request);
    }
}
