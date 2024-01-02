<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
            $payload = auth()->user();
            $request->headers->set("payload", $payload);
        } catch (Exception $error) {
            
            if ($error instanceof TokenInvalidException) {
                return $this->response("token is invalid", 401);
            } else if ($error instanceof TokenExpiredException) {
                return $this->response("token is expired", 401);
            } else {
                return $this->response("token not found", 401);
            }
        }
        return $next($request);
    }

    private function response(string $message, int $code = 200)
    {
        return response()->json([
            "code" => $code,
            "message" => $message,
            "data" => null
        ],$code);
    }
}
