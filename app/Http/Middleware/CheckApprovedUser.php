<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovedUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Allow admins through regardless of status check
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // If user is declined or still pending, log out or redirect with message
        if ($user && $user->status !== 'approved') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $statusMessage = $user->status === 'declined' 
                ? 'Your registration request has been declined. Please contact administration.'
                : 'Your account is pending admin approval. You will receive access once approved.';

            return redirect()->route('login')->withErrors(['email' => $statusMessage]);
        }

        return $next($request);
    }
}