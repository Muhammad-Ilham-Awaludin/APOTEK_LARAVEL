<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;



class isGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() == FALSE) {
            // jika checknya false (artinya belum login, perbolehkan mengakses)
            return $next($request);
        } else {
            // jika checknya true (uda login, balikin lagi ke halaman home)
            return redirect()->route('home.page')->with('failed', 'ANDA TELAH LOGIN');
        }
    }
}
