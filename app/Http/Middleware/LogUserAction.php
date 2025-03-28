<?php
 

namespace App\Http\Middleware;

use App\Http\Services\ServicDeistviiPolzovatelei;
use App\Servsi\Authentication\ServisAutentificatcii;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function in_array;

class LogUserAction
{
    public function __construct(
        private readonly ServicDeistviiPolzovatelei $loggingService,
        private readonly ServisAutentificatcii      $authenticationService,
    )
    {
    }


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($bearerToken = $request->bearerToken()) !== null) {
            $user = $this->authenticationService->poluchitPolzovatelia($bearerToken);
            $route_name = $request->route()->getName();

            $pass = [
                'auth.login', 'account.create', 'account.password_change_request',
                'logs.list', 'log.filter', 'logs.ipsinfo', 'logs.most-active', 'for-user'
            ];

            if (!in_array($route_name, $pass)) {

                if ($user->id) {
                    $this->loggingService->log($request, $user->id);
                }
            }
        }

        return $next($request);
    }
}
