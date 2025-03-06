<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $isApiKeyValid = Device::where('deviceAPIKey',$apiKey)->exists();
        if (!$apiKey || !$isApiKeyValid) {
            return response()->json(['error' => 'API key is missing or invalid'], 401);
        }
        return $next($request);
    }
}
