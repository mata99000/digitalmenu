<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleIsWaiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Proverava da li je ulogovani korisnik waiter
         if (auth()->check() && auth()->user()->role == 'waiter') {
            return $next($request);
        }

        // Ako korisnik nije waiter, redirektuj ga na početnu stranicu ili gde god želiš
        return redirect('/')->with('error', 'Nemate pristup ovoj stranici!');
    }
}
