<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\JWTAuth;
use App\Models\ActiveStatus as ModelsActiveStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!empty($request->header('Authorization'))){
            $token = JWTAuth::verifyToken($request->header('Authorization'),false);
            ModelsActiveStatus::updateOrCreate(
                [
                    'user_id' => $token->id
                ],
                [
                    'user_id' => $token->id,
                    'status' => 'active',
                    'active_note' => 'active now'
                ]
            );
        }
        return $next($request);
    }
}
