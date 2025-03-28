<?php
 

namespace App\Http\Middleware;

use App\Http\AuthStatusCode;
use App\Khranilischa\Authentication\Token\TokenRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserActivated
{
    public function __construct(
        private TokenRepositoryInterface $tokenRepository
    )
    {
    }


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
