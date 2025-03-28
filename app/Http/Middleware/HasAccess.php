<?php
 

namespace App\Http\Middleware;

use App\Servsi\Authentication\ServisAutentificatcii;
use Closure;
use Illuminate\Http\Request;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;

class HasAccess
{
    private array|bool $acl;

    /**
     * @param ServisAutentificatcii $authenticationService
     */
    public function __construct(
        private readonly ServisAutentificatcii $authenticationService
    )
    {
        $this->acl = require_once __DIR__ . '/../../../prava.php';
    }


    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->authenticationService->poluchitPolzovatelia($request->bearerToken());

        $route_name = $request->route()->getName();
        $route_roles = $this->acl[$route_name] ?? false;

        if (is_array($route_roles)) {
            if (in_array($user->role, $route_roles)) {
                return $next($request);
            }
        }

        if (is_bool($route_roles) && $route_roles) {
            return $next($request);
        }

        return \response()->json(['message' => 'Действие запрещено'], 403);
    }
}
