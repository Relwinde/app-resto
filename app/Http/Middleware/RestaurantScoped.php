<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantScoped
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $restaurantId = $request->route('restaurantId');

        if (!$restaurantId) {
            return abort(404);
        }

        $user = auth()->user();

        if (!$user->canAccessRestaurant($restaurantId)) {
            return abort(403, 'Unauthorized access to this restaurant.');
        }

        $request->attributes->add(['current_restaurant_id' => $restaurantId]);

        return $next($request);
    }
}
